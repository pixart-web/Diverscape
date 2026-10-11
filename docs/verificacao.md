# Relatório de testes

Executado em 10/10/2026 no ambiente local (Docker, WordPress 7.1, PHP 8.3, MariaDB 11.4), modo demonstração ativo.

## Resultado

| Verificação | Ferramenta | Resultado |
|---|---|---|
| Testes end-to-end | Playwright 1.55 (`tools/tests/site.spec.mjs`) | **31/31 passam** |
| Blocos válidos no editor | `tools/editor-check.mjs all` | **272/272** conteúdos (páginas, ofertas, FAQ, 4 idiomas) sem blocos inválidos |
| Overflow horizontal | `tools/overflow-all.mjs` | **0** em 196 URLs × 360 e 768 px; testes cobrem 360/390/768/1024/1440 em 10 páginas-chave |
| Cobertura do catálogo | `wp diverscape status` | **49/49** linhas de origem com destino; 34 itens, 51 variantes, 4 idiomas completos |
| Acessibilidade automática | axe-core (WCAG 2.0/2.1/2.2 A+AA) em 9 páginas | 0 violações graves/críticas |
| Lighthouse (laboratório) | Lighthouse 12.6, Chromium local | ver tabela |

### O que os testes end-to-end cobrem

- Todas as 196 URLs do mapa do site: estado 200, um H1, título, descrição, `canonical` próprio, `hreflang` completo (en, pt-PT, de, es, x-default), `lang` correto e `noindex` (demo).
- Ligações internas sem erros (mais de 80 únicas, 13 páginas).
- Seletor de idioma leva à tradução da página atual (oferta PT → DE).
- Âncoras históricas (`#frontpa`, `#Gallery`, `#coursesprices`, `#location`) nas 4 homepages.
- 404 localizado (PT, ES) com estado 404.
- Demonstração não indexável (`X-Robots-Tag`, `robots.txt`).
- Catálogo: pesquisa, categoria, nível, estado vazio, limpar; filtros também **sem JavaScript**.
- **Preço central:** alterar o preço do Discover Scuba no catálogo muda homepage EN/PT/DE/ES, páginas de detalhe, tabela de preços e opções do formulário; o valor é reposto no fim.
- Pacotes de mergulho: escolha atualiza preço e ligação de pedido.
- Pedido: pré-seleção de item e variante a partir da página do curso, erros por passo (focados e anunciados), resumo, privacidade obrigatória, resultado «não enviado (demonstração)» com foco, **nenhum email no Mailpit**.
- «Ainda não sei» e textos/erros em português; envio **sem JavaScript** em alemão com validação no servidor.
- Servidor rejeita envio demasiado rápido e campo isco; limite de frequência (429) observado durante os testes.
- Galeria: lightbox por teclado, setas, legenda, contador, Escape e devolução de foco.
- Submenu por teclado (Enter, Escape, foco); menu móvel cobre o ecrã, com idiomas e chamada para ação.

## Lighthouse (dados de laboratório, não de utilizadores reais)

| Página | Perfil | Desempenho | Acessibilidade | Boas práticas | SEO | LCP | CLS | TBT |
|---|---|---|---|---|---|---|---|---|
| Homepage | mobile | 98 | 100 | 100 | 69* | 2,4 s | 0 | 0 ms |
| Homepage | desktop | 100 | 100 | 100 | 69* | 0,7 s | 0 | 0 ms |
| Catálogo | mobile | 95 | 99** | 100 | 69* | 2,9 s | 0 | 0 ms |
| Catálogo | desktop | 99 | 99** | 100 | 69* | 1,0 s | 0 | 0 ms |
| Discover Scuba | mobile | 99 | 100 | 100 | 69* | 2,0 s | 0 | 0 ms |
| Discover Scuba | desktop | 100 | 100 | 100 | 69* | 0,5 s | 0 | 0 ms |
| Contactos (PT) | mobile | 99 | 100 | 100 | 69* | 2,0 s | 0 | 0 ms |
| Contactos (PT) | desktop | 100 | 100 | 100 | 69* | 0,5 s | 0 | 0 ms |
| Catálogo, **sem modo demo** | mobile | 96 | 100 | 100 | **100** | — | — | — |

\* SEO 69 apenas pela auditoria `is-crawlable`: o `noindex` é intencional na demonstração. Com o modo demonstração desligado, SEO = 100.
\** Ordem de títulos no catálogo corrigida depois desta medição (100 na medição seguinte).

Medições num servidor local (sem latência de rede real nem CDN). Os valores em produção dependem do alojamento; recomenda-se medir de novo em staging e acompanhar dados reais (Core Web Vitals da Search Console) depois do lançamento. Relatórios JSON em `docs/testes/lighthouse/`.

## Verificações manuais

- Revisão visual a 360, 390, 768, 1024 e 1440 px de todas as páginas tipo (capturas em `docs/capturas/`).
- Corrigidos durante a revisão: menu móvel preso à altura do cabeçalho (`backdrop-filter`), contraste do botão no rodapé e do turquesa sobre areia, nome acessível do botão de menu ≤ 420 px, revalidação que deslocava o botão «Continuar», campos do formulário interpretados como variáveis de consulta no envio sem JS, mosaico da galeria com espaços vazios, sobreposição da tabela de pacotes em telemóvel.

## Não executado / limitações

- Envio real de email (modo demonstração por definição do briefing). O caminho «live» está implementado e documentado, mas só deve ser testado com destinatário de teste autorizado.
- Widget Tripadvisor não carregado nos testes (serviço externo; carregamento só a pedido do visitante). Existe mensagem de falha e ligação direta.
- Testes com leitores de ecrã reais (VoiceOver/NVDA) não executados; a estrutura foi validada com axe e testes de teclado.
- Publicação em staging (sem acesso ao alojamento nesta fase).
- Revisão por instrutor PADI/TecRec e revisão jurídica dos textos legais: pendentes (ver [dados-a-validar.md](dados-a-validar.md)).

## Repetir

```bash
cd tools && npm install
npx playwright test
node editor-check.mjs all
node overflow-all.mjs 360,768
node screenshots.mjs
```
