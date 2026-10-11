# DiversCape — proposta de website (Pixart)

Website completo para a DiversCape, centro de mergulho no Porto da Baleeira, Sagres. **WordPress**, quatro idiomas (EN na raiz, PT `/pt/`, DE `/de/`, ES `/sp/`), catálogo central de cursos/experiências/preços e pedido de disponibilidade. Conceito: **«Sagres, below the surface.»**

> **Estado: proposta em modo demonstração.** O pedido de disponibilidade valida mas não envia nem guarda dados; nenhum email sai do site; indexação bloqueada; faixa «site de demonstração» visível. Preços = publicados no site atual (09/10/2026), com aviso de confirmação. Ver [dados-a-validar.md](docs/dados-a-validar.md).

## Arranque

```bash
bin/setup.sh
```

<http://diverscape.localhost:8093/> · painel `/wp-admin/` (credenciais em `.credentials.local`) · emails locais <http://localhost:8029>. Detalhes e instalação noutro alojamento: [docs/instalacao.md](docs/instalacao.md).

## Em números

| | |
|---|---|
| Páginas | 15 por idioma × 4 = 60 (homepage, experiências, catálogo, certificados, técnico, preços, equipamento, Sagres, sobre, galeria, contactos/pedido, FAQ, privacidade, cookies, condições) + 404 localizada |
| Catálogo | 49/49 linhas de origem migradas → 34 ofertas distintas (136 páginas) e 51 variantes, uma só fonte de preços |
| Media | 19 fotografias do próprio centro + logótipo original + logótipos PADI; WebP responsivo |
| Testes | 31/31 Playwright, 272/272 conteúdos válidos no editor, 0 overflow em 196 URLs, axe sem violações graves, Lighthouse 95–100 |

## Estrutura

| Caminho | Conteúdo |
|---|---|
| `wp-content/themes/diverscape/` | Tema (clássico + `theme.json`): modelos, CSS/JS sem dependências, fontes locais, padrões |
| `wp-content/plugins/diverscape-core/` | Catálogo central, idiomas, SEO, pedido, blocos, importador WP-CLI; **todos os conteúdos e media em `content/`** |
| `wp-content/plugins/diverscape-demo-mode/` | Modo demonstração (desativar só no lançamento autorizado) |
| `research/` | Recolha do site atual (`raw/`, `media-original/`, não versionados), extração e geração do inventário |
| `tools/` | Testes Playwright + axe, verificação do editor, capturas, overflow |
| `docs/` | Documentação e capturas |
| `bin/` | `setup.sh` (instalação idempotente) e `build-package.sh` (pacote de entrega) |

## Documentação

1. [Instalação](docs/instalacao.md) · 2. [Editar conteúdos](docs/edicao-conteudos.md) · 3. [Arquitetura e decisões](docs/arquitetura.md)
4. [Inventário de conteúdo](docs/inventario-conteudo.md) ([CSV, 4 idiomas](docs/inventario-conteudo.csv)) · [Inventário de media](docs/inventario-media.md)
5. [Conflitos e dados a confirmar](docs/dados-a-validar.md) · 6. [Mapa de URLs, idiomas e redirecionamentos](docs/mapa-urls.md)
7. [Capturas](docs/capturas/) · 8. [Relatório de testes](docs/verificacao.md) · 9. [Roteiro de apresentação](docs/apresentacao.md)
