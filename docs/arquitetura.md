# Arquitetura e decisões

## Peças

| Peça | Responsabilidade |
|---|---|
| Tema `diverscape` (clássico + `theme.json`) | Apresentação: cabeçalho/rodapé, modelos (página, oferta, 404), CSS e JS sem dependências, fontes locais Manrope + Inter (OFL), padrões de blocos. Não guarda dados de negócio. |
| Plugin `diverscape-core` | Conteúdo de negócio: catálogo central, idiomas, SEO, pedido de disponibilidade, media, FAQ, blocos dinâmicos, importador WP-CLI e todos os conteúdos de origem (`content/`). |
| Plugin `diverscape-demo-mode` | Modo demonstração: formulário nunca envia nem guarda, nenhum email sai, `noindex` em todas as vias, faixa visível, código de acesso opcional. |

Sem plugins de terceiros. Sem dependências pagas.

## Modelo de conteúdo

- **`dvs_item` — catálogo central** (não traduzível). Um item por curso/experiência distinto (34), com categoria, público, duração, idade mínima, certificação, «Junior», imagem, estado comercial (*a confirmar*/*validado*), notas internas, conflitos e **variantes** (51): rótulo nos 4 idiomas, preço numérico em EUR ou vazio, tipo (`fixed`, `on_request`, `pending`), duração, nº de mergulhos, equipamento e as linhas de origem que a variante representa.
- **`dvs_offer` — página de curso/experiência** (traduzível). Título, resumo, corpo, «para quem é», perguntas; liga ao item pela chave estável. **Não guarda preços.**
- **`page`** — páginas em blocos (core + 16 blocos dinâmicos DiversCape).
- **`dvs_faq`** — perguntas por idioma e tema; *publicada* = resposta confirmada, *rascunho* = a validar.
- **Media** — alt e legenda nos 4 idiomas, origem, posição na galeria, nota interna de validação.
- **Dados da empresa e definições** — opções editáveis (contactos, ligações, modo de preços, modo do formulário).
- **Textos da interface** — dicionário em 4 idiomas, com substituições editáveis no painel.

Uma alteração de preço no item reflete-se em homepage, catálogo, detalhe, tabela de preços, escolha de pacotes e resumo do formulário, nos 4 idiomas (testado automaticamente).

### Regras de preço

- `null` nunca vira 0 nem «Grátis». `fixed` sem valor é tratado como `pending`.
- «desde X» só quando existe uma variante com esse valor e mais de um preço/variante.
- `on_request` → «Sob consulta»; `pending` → «Consultar».
- Modo global «esconder preços» → tudo «Consultar».
- Aviso discreto «preços conforme publicados… sujeitos a confirmação» enquanto houver itens não validados.
- Nenhum total de reserva, desconto, imposto, caução ou disponibilidade é calculado.

## Idiomas — porque não Polylang/WPML

Camada própria e pequena (`inc/i18n.php`), documentada:

- Cada conteúdo tem `_dvs_lang` e `_dvs_group` (identificador comum às traduções).
- Inglês na raiz; PT/DE/ES são **páginas-filhas** das páginas `pt`, `de`, `sp` → URLs nativos do WordPress (`/pt/cursos/`), sem regras de reescrita frágeis.
- Ofertas vivem por baixo da página da categoria no seu idioma (`/pt/cursos/mergulho-profundo/`), resolvidas a partir do caminho pedido.
- O mesmo slug pode existir em idiomas diferentes (`/pt/experiencias/bubblemaker/`, `/sp/experiencias/bubblemaker/`).
- Painel: coluna e filtro de idioma, caixa «Idioma e traduções» com ligações às outras versões e ação «criar tradução» (duplica como rascunho no idioma pedido, sob a página-mãe equivalente).
- Menus WordPress normais por idioma (Aspeto → Menus).

Motivo: o Polylang gratuito não traduz bases de URL de tipos de conteúdo nem partilha slugs entre idiomas — teria produzido URLs mistos (`/pt/courses/…`) ou slugs com sufixos. O WPML é pago. A camada própria cobre exatamente o necessário e o modelo (`_dvs_lang`/`_dvs_group`) é simples de migrar para um plugin multilingue no futuro, se o cliente o preferir.

## SEO

Títulos e descrições por página e idioma (campos `_dvs_seo_title`/`_dvs_seo_desc`), um H1 por página, breadcrumbs visíveis e em JSON-LD, `canonical` próprio por tradução, `hreflang` recíproco + `x-default`, Open Graph/Twitter, `html lang` correto, mapa XML (`/wp-sitemap.xml`, só páginas e ofertas), JSON-LD `SportsActivityLocation` só com propriedades confirmadas (sem avaliações nem preços ativos) e `Course` nas formações. Em demonstração: `noindex`, `X-Robots-Tag`, `robots.txt` com `Disallow: /`.

## Pedido de disponibilidade

4 passos com melhoria progressiva (sem JS: formulário único, mesma validação no servidor). Pré-seleção por `?offer=…&variant=…`. «Ainda não sei» e «Pergunta geral». Validação no servidor sempre; campo isco, token assinado com tempo mínimo de 3 s e máximo de 4 h, limite de 8 envios/10 min por IP (só um hash, 10 min). Modo demo: valida e responde «nada foi enviado». Modo real: um email para o destinatário configurado, `Reply-To` do visitante; nada é guardado na base de dados. Não pede dados clínicos, documentos nem dados de menores.

## Desempenho e acessibilidade

Imagens WebP responsivas com dimensões explícitas, lazy-load fora do hero, hero com `fetchpriority=high`, fontes locais com `preload` e subconjunto latin (latin-ext sob pedido), CSS 68 KB (13 KB comprimido) e JS 22 KB (7 KB comprimido), sem dependências, nada de jQuery no público, serviços externos só a pedido (Tripadvisor) ou por ligação (Google Maps). Foco visível, skip link, submenus e seletor de idioma por teclado, lightbox `<dialog>` com Escape e devolução de foco, erros associados aos campos (`aria-invalid`, `aria-describedby`, sumário com ligações), `prefers-reduced-motion` respeitado, alvos ≥ 44 px.

## Movimento

Entradas subtis (450 ms), zoom lento da imagem do hero, transição superfície → oceano com ondas em SVG, parallax ligeiro (±36 px, só ≥ 900 px e sem redução de movimento). Sem scroll controlado, som, bolhas sobre texto ou cursores personalizados.
