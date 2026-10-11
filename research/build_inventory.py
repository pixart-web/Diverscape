"""Gera o inventário de conteúdo e de media a partir da recolha (research/raw) e da exportação do WordPress
(research/export/site.json). Uso: python3 -I research/build_inventory.py  (a partir da raiz do projeto)."""
import csv, json, os, re, subprocess
from collections import Counter

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
RAW = os.path.join(ROOT, 'research', 'raw')
DOCS = os.path.join(ROOT, 'docs')
site = json.load(open(os.path.join(ROOT, 'research', 'export', 'site.json')))
BASE = 'https://diverscape.net'
SRC = {'en': BASE + '/', 'pt': BASE + '/pt/', 'de': BASE + '/de/', 'es': BASE + '/sp/'}
FILES = {'en': 'en.txt', 'pt': 'pt.txt', 'de': 'de.txt', 'es': 'sp.txt'}
LANG_NAMES = {'en': 'Inglês', 'pt': 'Português', 'de': 'Alemão', 'es': 'Espanhol'}
GROUPS = [('exp', 2, 'Experiências'), ('cur', 6, 'Formação de mergulho'), ('efr', 6, 'Primeiros socorros'),
          ('esp', 12, 'Especialidades'), ('tec', 11, 'Formação técnica'), ('sai', 12, 'Saídas e serviços')]
# Versões EN/DE/ES não têm «Mergulho em Corrente» (esp-8) nem «Mergulho Embarcado» (esp-12).
ESP_SHORT = [1, 2, 3, 4, 5, 6, 7, 9, 10, 11]


def source_rows(lang):
    rows = []
    for line in open(os.path.join(RAW, FILES[lang]), encoding='utf-8'):
        if not line.startswith('ROW | '):
            continue
        cells = [c.strip() for c in line[6:].rstrip('\n').split(' | ')]
        if len(cells) < 2 or cells[0] in ('Tipo', 'Type', '') or cells[0].startswith('*'):
            continue
        rows.append(cells + [''] * (3 - len(cells)))
    ids = []
    for prefix, n, _ in GROUPS:
        seq = ESP_SHORT if (prefix == 'esp' and lang != 'pt') else range(1, n + 1)
        ids += [f'{prefix}-{i}' for i in seq]
    assert len(ids) == len(rows), (lang, len(ids), len(rows))
    return list(zip(ids, rows))


# Índice: id da linha → (item, variante)
by_row = {}
for key, item in site['items'].items():
    for v in item['variants']:
        for r in v['rows']:
            by_row.setdefault(r, []).append((key, item, v))


def fmt_dur(d):
    if not d:
        return ''
    return {'half_day': 'meio dia', 'day': '1 dia', 'morning': 'manhã', 'half_morning': 'meia manhã'}.get(d['unit'], f"{d.get('qty')} dias")


def price_txt(v):
    if v['type'] == 'fixed' and v['price'] is not None:
        return f"{v['price']:g} €"
    return 'sob consulta' if v['type'] == 'on_request' else 'consultar (por validar)'


records = []
for lang in ('pt', 'en', 'de', 'es'):
    for rid, (title, desc, price) in source_rows(lang):
        dest = by_row.get(rid, [])
        if not dest:
            records.append([SRC[lang], LANG_NAMES[lang], rid, title, desc, price, '—', '—', '—', 'SEM DESTINO', ''])
            continue
        for key, item, v in dest:
            label = (v.get('label') or {}).get('pt') or 'variante única'
            dur = fmt_dur(v.get('duration') or item.get('duration')) or 'oculta/não indicada'
            state = 'Migrado'
            if item['conflicts']:
                state = 'Migrado — com conflito registado'
            if v['type'] == 'on_request':
                state += ' — preço sob consulta (como na fonte)'
            notes = ' '.join(item['conflicts'] + ([item['notes']] if item['notes'] else []))
            url = item['urls'][lang] + ('#options' if len(item['variants']) > 1 else '')
            records.append([SRC[lang], LANG_NAMES[lang], rid, title, desc, price, f"{item['titles'][lang]} · {label}", url,
                            f"{price_txt(v)} · duração: {dur}", state, notes])

os.makedirs(DOCS, exist_ok=True)
head = ['Origem', 'Idioma', 'ID linha', 'Título original', 'Descrição original', 'Preço original', 'Destino (oferta · variante)',
        'URL novo', 'Dados no catálogo central', 'Estado', 'Observações']
with open(os.path.join(DOCS, 'inventario-conteudo.csv'), 'w', newline='', encoding='utf-8') as f:
    w = csv.writer(f)
    w.writerow(head)
    w.writerows(records)

counts = Counter(r[1] for r in records)
missing = [r for r in records if r[9] == 'SEM DESTINO']
variants = sum(len(i['variants']) for i in site['items'].values())


def esc(s):
    return str(s).replace('|', '\\|').replace('\n', ' ')


md = ['# Inventário de conteúdo — catálogo', '',
      'Gerado por `research/build_inventory.py` a partir das páginas servidas em 09/10/2026 (`research/raw/`) e da instalação WordPress',
      '(`research/export/site.json`). Versão completa, com as quatro línguas: [inventario-conteudo.csv](inventario-conteudo.csv).', '',
      '## Resumo', '',
      '| Idioma | Linhas de oferta na fonte | Com destino |', '|---|---|---|']
for lang in ('pt', 'en', 'de', 'es'):
    n = sum(1 for r in records if r[1] == LANG_NAMES[lang] and r[2] not in ('',))
    rows = len(source_rows(lang))
    md.append(f'| {LANG_NAMES[lang]} | {rows} | {rows - sum(1 for r in missing if r[1] == LANG_NAMES[lang])} |')
md += ['', f'- **{len(site["items"])} ofertas distintas** (uma página por oferta, em 4 idiomas = {len(site["items"]) * 4} páginas) e **{variants} variantes** no catálogo central.',
       '- As 49 linhas da versão portuguesa têm destino; as 47 de EN/DE/ES também. «Mergulho em Corrente» e «Mergulho Embarcado» só existiam em PT e foram preservados e traduzidos.',
       '- Pacotes (1/2/6/10 mergulhos × com/sem equipamento) e opções (costa/barco, Nitrox com/sem mergulhos, partes do EFR, Tec TMx, Gas Blender) são **variantes** de uma página — não páginas redundantes.',
       f'- Linhas sem destino: **{len(missing)}**.', '',
       '## Linhas da versão portuguesa (referência)', '',
       '| ID | Original | Preço original | Destino | URL | Catálogo | Estado |', '|---|---|---|---|---|---|---|']
for r in records:
    if r[1] == 'Português':
        md.append(f'| {r[2]} | {esc(r[3])} | {esc(r[5]) or "—"} | {esc(r[6])} | `{r[7]}` | {esc(r[8])} | {esc(r[9])} |')

md += ['', '## Outro conteúdo da página única original', '',
       '| Secção original (âncora) | Conteúdo | Destino novo | Estado |', '|---|---|---|---|',
       '| Apresentação `#frontpa` — «Sagres» / «Tauchen im Sagres» | Sagres, natureza, praias, porto natural; mergulho para todos os níveis, técnico e Trimix | Homepage (secção «Sagres acima e abaixo de água») e página «Mergulhar em Sagres» | Reescrito; «mergulhar todo o ano» não reproduzido como promessa (FAQ a validar) |',
       '| «Experiência» / «Our knowledge» | Equipa variada e dedicada; +15 anos (EN: «combined»); manutenção de equipamento a preço acessível | Homepage («Pessoas e confiança»), «Sobre nós», «Equipamento» | Migrado como experiência **acumulada** da equipa, não anos de empresa |',
       '| «Mergulhar em Sagres» / «Diving in Sagres» | Parque Natural do SW Alentejano e C. Vicentina; grutas, desfiladeiros, passagens (EN); Ilhas do Martinhal, grutas, naufrágios (DE); guia ou instrutor em todos os mergulhos (PT/ES) | «Mergulhar em Sagres», homepage, FAQ | Migrado sem prometer avistamentos, condições ou acesso de iniciantes a grutas; «Ilhas do Martinhal» a confirmar antes de nomear |',
       '| Galeria `#Gallery` | 12 fotografias + Tripadvisor | Galeria completa (19 fotografias), mosaico na homepage, lightbox | Migrado; legendas sem identificar espécies; ver inventário de media |',
       '| Avaliações (widget Tripadvisor, locationId 4009789) | Widget de terceiros | Bloco «Avaliações» com ligação direta e carregamento do widget só a pedido do visitante | Migrado; nenhuma classificação copiada |',
       '| Cursos & Preços `#coursesprices` — destaques DSD e Open Water | Textos introdutórios | Páginas das ofertas e homepage | Migrado |',
       '| Localização & Contactos `#location` | Porto da Baleeira; resposta em 24 h; telefone com nota de chamada; mapa Google incorporado; formulário (nome, email, assunto, mensagem) | Página «Planeie o seu mergulho» + rodapé + convite final | Mapa incorporado substituído por ligação «Como chegar»; formulário substituído pelo pedido de disponibilidade |',
       '| Rodapé | Email, telefone, morada, «Licença Turismo: RNAAT 19/2009», Facebook, Tripadvisor, © 2018 | Rodapé novo | Ano dinâmico; RNAAT mantido como publicado (não verificado) |',
       '| Ligações externas | Salema Beach Village (agora NAU Hotels), Sagres Sun Stay, My Tourist Maps, natural.pt, PADI, Turismo de Portugal, Tripadvisor | «Ligações úteis» (Mergulhar em Sagres) e rodapé | Migradas como texto; logótipos de terceiros não reutilizados |',
       '| Nota Covid-19 (+5 € com aluguer de regulador) | Publicada nas 4 versões | Registada em [dados-a-validar.md](dados-a-validar.md) | **Não aplicada** no novo fluxo; decisão comercial pendente |',
       '| Metadados (title, description, keywords) | Títulos mistos «Divers Cape»; meta EN refere «snorkeling tours» | Títulos e descrições novos por página e idioma | «Snorkeling tours» não reproduzido (só há aluguer de equipamento) |',
       '| Rastreio Metricool | Script de terceiros | — | Não migrado (sem consentimento configurado); decisão do cliente |']
open(os.path.join(DOCS, 'inventario-conteudo.md'), 'w', encoding='utf-8').write('\n'.join(md) + '\n')

# Inventário de media
orig_dims = {}
mo = os.path.join(ROOT, 'research', 'media-original')
if os.path.isdir(mo):
    out = subprocess.run(['sips', '-g', 'pixelWidth', '-g', 'pixelHeight'] + [os.path.join(mo, f) for f in sorted(os.listdir(mo))], capture_output=True, text=True).stdout
    cur = None
    for line in out.splitlines():
        if not line.startswith(' '):
            cur = os.path.basename(line.strip())
        elif 'pixelWidth' in line:
            orig_dims[cur] = line.split()[-1]
        elif 'pixelHeight' in line:
            orig_dims[cur] += '×' + line.split()[-1]
mm = ['# Inventário de media', '',
      'Todas as fotografias usadas são do próprio centro, recolhidas do site atual nos originais de maior resolução e otimizadas',
      '(máx. 2400 px; os tamanhos gerados pelo WordPress são WebP). Ficheiros em `wp-content/plugins/diverscape-core/content/media/`,',
      'importados de forma reproduzível pelo comando `wp diverscape seed`. **Nenhuma imagem de banco de imagens ou gerada foi usada.**', '',
      '| Ficheiro novo | Origem | Dimensões (original → biblioteca) | Utilização | Galeria | A validar |', '|---|---|---|---|---|---|']
usage = {}
for k, i in site['items'].items():
    usage.setdefault(i['image'], []).append(k)
pages_src = open(os.path.join(ROOT, 'wp-content/plugins/diverscape-core/content/pages.php'), encoding='utf-8').read()
for m in sorted(site['media'], key=lambda x: (x['gallery'] or 99, x['key'])):
    src = re.search(r'Images/([^ ]+)', m['source'] or '')
    of = src.group(1) if src else ''
    uses = []
    if m['key'] in pages_src:
        uses.append('páginas')
    if m['key'] in usage:
        uses.append('ofertas: ' + ', '.join(usage[m['key']]))
    if m['key'].startswith('logo') or m['key'].startswith('padi'):
        uses.append('cabeçalho/rodapé/credenciais')
    mm.append(f"| `{m['key']}` | `{of}` | {orig_dims.get(of, '?')} → {m['w']}×{m['h']} (+{m['sizes']} tamanhos) | {esc('; '.join(uses) or '—')} | {m['gallery'] or '—'} | {esc(m['review']) or '—'} |")
used = {re.search(r'Images/([^ ]+)', m['source']).group(1) for m in site['media'] if m['source'] and 'Images/' in m['source']}
mm += ['', '## Ficheiros do site atual não reutilizados', '', '| Ficheiro | Dimensões | Motivo |', '|---|---|---|']
reasons = {'Tourism.png': 'Logótipo Turismo de Portugal — marca de terceiros; só usar com autorização/regras de utilização',
           'nature.png': 'Logótipo do parque natural — substituído por ligação de texto', 'nau.png': 'Logótipo de alojamento parceiro — ligação de texto',
           'sagressunstay.jpg': 'Logótipo de alojamento parceiro — ligação de texto', 'touristmap.png': 'Logótipo de terceiros — ligação de texto',
           'tripadvisor.png': 'Logótipo de terceiros — ligação de texto', 'favicon.ico': 'Favicon de 10×10 px; novo favicon gerado a partir do logótipo original',
           'Groupe.jpg': ''}
for f in sorted(orig_dims):
    if f not in used and f in reasons:
        mm.append(f'| `{f}` | {orig_dims[f]} | {reasons[f]} |')
mm += ['| `portugal.png`, `UK.png`, `Germany.png`, `Spain.png` | ícones | Bandeiras: o seletor usa nomes de idioma escritos |', '',
       'Vídeo: o site atual não tem vídeo. O hero usa fotografia real; não foi deixado nenhum espaço vazio à espera de ficheiros.']
open(os.path.join(DOCS, 'inventario-media.md'), 'w', encoding='utf-8').write('\n'.join(mm) + '\n')
print(f'{len(records)} linhas de inventário; sem destino: {len(missing)}; por idioma: {dict(counts)}')
