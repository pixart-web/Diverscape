# Roteiro de apresentação à DiversCape (Pixart)

Duração sugerida: 20 minutos + perguntas. Abrir o site em `http://diverscape.localhost:8093/` (ou no staging protegido) e ter `docs/capturas/` à mão como plano B.

## 1. Abertura — o problema (2 min)

Mostrar lado a lado `capturas/00-antes-diverscape-net-desktop.png` e `capturas/01-homepage-desktop.png`.

> «Hoje o site é uma página única: o logótipo flutua sobre a fotografia, o menu está comprimido entre separadores e bandeiras, e não há uma frase que diga o que fazem nem um botão para reservar. Todo o catálogo está em tabelas fechadas. Queremos que quem chega sinta o oceano e saiba, em segundos, o que pode fazer convosco.»

Conceito: **«Sagres, below the surface.»**

## 2. Cinco melhorias concretas e demonstráveis

### ① Uma primeira impressão que vende (homepage)
- Hero com fotografia real do centro, mensagem curta nos 4 idiomas e dois botões claros.
- Logótipo original preservado numa aba branca — legível sobre qualquer imagem.
- Percorrer: «Por onde quer começar?» → destaques → **transição superfície → oceano** (ilhéus, linha de água, naufrágio) → formação → equipa → galeria → avaliações → convite final.
- *Demonstrar:* fazer scroll devagar; carregar em «Escolha a sua experiência».

### ② Escolher fica simples (catálogo com pesquisa e filtros)
- 34 cursos e experiências, 51 opções, em cartões com duração e preço.
- *Demonstrar:* em `/pt/cursos/` escrever «nitrox» → 2 resultados; escolher «Especialidades» → 12; «A sua experiência: Mergulhadores técnicos» → 8; escrever «xyz» → estado vazio útil com «Limpar filtros» e «Fazer uma pergunta».
- Cada curso tem página própria com «Para quem é», factos confirmados e opções (ex.: Discover Scuba costa/barco).

### ③ Um só lugar para os preços — em quatro línguas
- Os preços vivem num **catálogo central**: muda-se uma vez e atualiza homepage, catálogo, página do curso, tabela de preços, pacotes e formulário — em EN, PT, DE e ES.
- *Demonstrar no painel:* DiversCape → Catálogo e preços → Discover Scuba Diving → mudar 105 € para 99 € → guardar → abrir `/de/` e `/sp/precios/`. Repor.
- Conflitos tratados com rigor: ex. Advanced Open Water aparece com «2 dias» em PT e «5 days» em EN — o site esconde a duração até a DiversCape confirmar, em vez de escolher uma.

### ④ Pedido de disponibilidade em 4 passos (e honesto)
- Botão «Pedir disponibilidade» em cada opção abre o pedido **já preenchido** com o curso e a variante.
- *Demonstrar:* `/certified-divers/` → escolher «6 dives» + «Rental included» (preço muda para 335 €) → «Request availability» → avançar com um campo em falta (erro claro, foco no campo) → resumo → enviar → «Pedido verificado — não enviado (demonstração)».
- Diz claramente que **não é uma reserva** até a equipa confirmar. Aceita «Ainda não sei». Não pede dados clínicos nem documentos. Funciona sem JavaScript.

### ⑤ Quatro idiomas completos, prontos para o Google
- Seletor com nomes escritos que leva à **mesma página** noutra língua (ex.: Mergulho Profundo PT → Deep Diver DE).
- URLs próprios por idioma (`/pt/cursos/mergulho-profundo/`, `/de/kurse/deep-diver/`), `hreflang`, títulos e descrições únicos, mapa do site, dados estruturados.
- *Demonstrar:* numa página de curso, mudar para Deutsch e Español; mostrar `capturas/18-menu-mobile.png` ou o menu no telemóvel.

## 3. Detalhes que dão confiança (2 min)
- Telemóvel primeiro: testado a 360, 390, 768, 1024 e 1440 px, sem cortes.
- Rápido: Lighthouse 95–100 em desempenho (medição em laboratório).
- Acessível: navegação por teclado, contrastes AA, galeria com teclado e Escape.
- Fotografias **do próprio centro**, em alta resolução — nenhuma imagem de banco nem recifes tropicais.
- Sem cookies de rastreio; Tripadvisor só carrega quando o visitante pede.

## 4. O painel (3 min)
`capturas/21-painel-catalogo.png`, `22-painel-item-precos.png`, `23-painel-paginas-idioma.png`.
- Catálogo e preços com estado «a confirmar/validado» e conflitos visíveis.
- Páginas e cursos por idioma, com ligação às traduções.
- Galeria com legendas nos 4 idiomas; FAQ com respostas por validar em rascunho.

## 5. O que precisamos da DiversCape (3 min)
Levar [dados-a-validar.md](dados-a-validar.md) impresso. Prioridades A:
1. Tabela de preços aprovada e o que cada preço inclui (e decidir a taxa Covid).
2. Duração do Advanced Open Water e idades Junior; pré-requisitos.
3. Continuam em oferta Mergulho em Corrente e Mergulho Embarcado?
4. Nome da marca (DiversCape/Divers Cape), estatuto PADI/TecRec, RNAAT.
5. Autorização de imagem das pessoas nas fotografias; fotografias novas da equipa (opcional).
6. Destinatário do pedido e textos legais (NIF, denominação, prazos).

## Perguntas prováveis

| Pergunta | Resposta |
|---|---|
| «Podemos ter reservas com pagamento online?» | Sim, numa fase seguinte. Hoje não existe inventário de vagas integrado, por isso o site não finge disponibilidade imediata. |
| «Conseguimos mudar os preços sozinhos?» | Sim — um campo por opção, muda em todo o site e em todas as línguas. |
| «E se quisermos outra língua?» | O modelo suporta mais idiomas; implica traduzir páginas, cursos e textos da interface. |
| «Porque não aparecem os preços dos cursos técnicos?» | O site atual também os tem «sob consulta». Podem ser publicados quando quiserem. |
| «As avaliações do Tripadvisor?» | Ligação direta sempre visível; o widget carrega quando o visitante o pede (privacidade). Nada foi copiado. |
| «Vídeo no topo?» | Preparado para isso se houver vídeo com direitos; sem vídeo, a fotografia real funciona melhor do que um espaço vazio. |
