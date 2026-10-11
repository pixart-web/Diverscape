# Mapa de URLs, idiomas e redirecionamentos

Gerado a partir da instalação (`research/export/site.json`). Inglês na raiz; português em `/pt/`, alemão em `/de/`, espanhol em `/sp/` (caminho histórico mantido; código de idioma `es`, `hreflang="es"`).

## Regras

- Cada página e oferta tem URL próprio por idioma, `canonical` próprio e `hreflang` recíproco para `en`, `pt-PT`, `de`, `es` + `x-default` (inglês).
- O seletor de idioma leva à tradução da página atual; só recorre à página inicial do idioma se não existir tradução publicada.
- Sem redirecionamento por IP ou idioma do navegador.
- Ofertas vivem por baixo da página da sua categoria: experiências em *Experiences*, cursos/especialidades/primeiros socorros em *Courses*, técnico em *Technical diving*, serviços em *Certified divers*.
- URLs gerados pelas APIs do WordPress (`get_permalink`, `home_url`); nenhum domínio fixo no código. Ligações internas no conteúdo usam o esquema `dvs:` (resolvido no idioma da página).

## URLs históricos

| URL atual | Situação no site novo | Ação |
|---|---|---|
| `https://diverscape.net/` | Homepage inglesa | Mantido |
| `/pt/`, `/de/`, `/sp/` | Homepages PT, DE, ES | Mantidos |
| `/#frontpa`, `/#Gallery`, `/#coursesprices`, `/#location` (e o mesmo em `/pt/`, `/de/`, `/sp/`) | Os fragmentos não chegam ao servidor | As homepages mantêm estes IDs nas secções equivalentes (apresentação, galeria, formação/preços, convite com contactos). Testado. |
| `/Extensions/Images/*` | Imagens antigas | Opcional: redirecionar 301 para `/wp-content/uploads/…` equivalente se houver ligações externas conhecidas (ver tabela de media). Não há páginas internas a redirecionar. |
| `/Extensions/App.css`, `/Extensions/Javascript.js` | Ficheiros antigos | Sem redirecionamento (devolvem 404). |
| `http://` e `www.` | — | Manter o redirecionamento atual para `https://diverscape.net/` no alojamento. |
| `/sitemap.xml` (antigo, 2019) | Substituído por `/wp-sitemap.xml` | Redirecionar 301 `/sitemap.xml` → `/wp-sitemap.xml` no lançamento. |

Exemplo para Apache (`.htaccess`, antes das regras do WordPress):

```apache
Redirect 301 /sitemap.xml /wp-sitemap.xml
```

## Páginas

| Chave | EN | PT | DE | ES |
|---|---|---|---|---|
| `home` | [Diving in Sagres](/) `/` | [Mergulho em Sagres](/pt/) `/pt/` | [Tauchen in Sagres](/de/) `/de/` | [Buceo en Sagres](/sp/) `/sp/` |
| `experiences` | [Experiences](/experiences/) `/experiences/` | [Experiências](/pt/experiencias/) `/pt/experiencias/` | [Erlebnisse](/de/erlebnisse/) `/de/erlebnisse/` | [Experiencias](/sp/experiencias/) `/sp/experiencias/` |
| `courses` | [Courses](/courses/) `/courses/` | [Cursos](/pt/cursos/) `/pt/cursos/` | [Kurse](/de/kurse/) `/de/kurse/` | [Cursos](/sp/cursos/) `/sp/cursos/` |
| `certified-divers` | [Certified divers](/certified-divers/) `/certified-divers/` | [Mergulhadores certificados](/pt/mergulhadores-certificados/) `/pt/mergulhadores-certificados/` | [Brevetierte Taucher](/de/brevetierte-taucher/) `/de/brevetierte-taucher/` | [Buceadores certificados](/sp/buceadores-certificados/) `/sp/buceadores-certificados/` |
| `technical-diving` | [Technical diving](/technical-diving/) `/technical-diving/` | [Mergulho técnico](/pt/mergulho-tecnico/) `/pt/mergulho-tecnico/` | [Technisches Tauchen](/de/technisches-tauchen/) `/de/technisches-tauchen/` | [Buceo técnico](/sp/buceo-tecnico/) `/sp/buceo-tecnico/` |
| `prices` | [Prices](/prices/) `/prices/` | [Preços](/pt/precos/) `/pt/precos/` | [Preise](/de/preise/) `/de/preise/` | [Precios](/sp/precios/) `/sp/precios/` |
| `equipment` | [Equipment](/equipment/) `/equipment/` | [Equipamento](/pt/equipamento/) `/pt/equipamento/` | [Ausrüstung](/de/ausruestung/) `/de/ausruestung/` | [Equipo](/sp/equipo/) `/sp/equipo/` |
| `discover-sagres` | [Diving in Sagres](/discover-sagres/) `/discover-sagres/` | [Mergulhar em Sagres](/pt/mergulhar-em-sagres/) `/pt/mergulhar-em-sagres/` | [Tauchen in Sagres](/de/tauchen-in-sagres/) `/de/tauchen-in-sagres/` | [Bucear en Sagres](/sp/bucear-en-sagres/) `/sp/bucear-en-sagres/` |
| `about` | [About us](/about/) `/about/` | [Sobre nós](/pt/sobre-nos/) `/pt/sobre-nos/` | [Über uns](/de/ueber-uns/) `/de/ueber-uns/` | [Sobre nosotros](/sp/sobre-nosotros/) `/sp/sobre-nosotros/` |
| `gallery` | [Gallery and reviews](/gallery/) `/gallery/` | [Galeria e avaliações](/pt/galeria/) `/pt/galeria/` | [Galerie und Bewertungen](/de/galerie/) `/de/galerie/` | [Galería y opiniones](/sp/galeria/) `/sp/galeria/` |
| `contact` | [Plan your dive](/contact/) `/contact/` | [Planeie o seu mergulho](/pt/contactos/) `/pt/contactos/` | [Tauchgang planen](/de/kontakt/) `/de/kontakt/` | [Planifique su inmersión](/sp/contacto/) `/sp/contacto/` |
| `faq` | [Frequently asked questions](/faq/) `/faq/` | [Perguntas frequentes](/pt/perguntas-frequentes/) `/pt/perguntas-frequentes/` | [Häufige Fragen](/de/haeufige-fragen/) `/de/haeufige-fragen/` | [Preguntas frecuentes](/sp/preguntas-frecuentes/) `/sp/preguntas-frecuentes/` |
| `privacy` | [Privacy policy](/privacy/) `/privacy/` | [Política de privacidade](/pt/privacidade/) `/pt/privacidade/` | [Datenschutzerklärung](/de/datenschutz/) `/de/datenschutz/` | [Política de privacidad](/sp/privacidad/) `/sp/privacidad/` |
| `cookies` | [Cookies](/cookies/) `/cookies/` | [Cookies](/pt/cookies/) `/pt/cookies/` | [Cookies](/de/cookies/) `/de/cookies/` | [Cookies](/sp/cookies/) `/sp/cookies/` |
| `request-terms` | [Request conditions](/request-conditions/) `/request-conditions/` | [Condições do pedido](/pt/condicoes-do-pedido/) `/pt/condicoes-do-pedido/` | [Anfragebedingungen](/de/anfragebedingungen/) `/de/anfragebedingungen/` | [Condiciones de la solicitud](/sp/condiciones-de-solicitud/) `/sp/condiciones-de-solicitud/` |

## Cursos e experiências

| Item do catálogo | EN | PT | DE | ES |
|---|---|---|---|---|
| `bubblemaker` | `/experiences/bubblemaker/` | `/pt/experiencias/bubblemaker/` | `/de/erlebnisse/bubblemaker/` | `/sp/experiencias/bubblemaker/` |
| `discover-scuba-diving` | `/experiences/discover-scuba-diving/` | `/pt/experiencias/batismo-de-mergulho/` | `/de/erlebnisse/schnuppertauchen/` | `/sp/experiencias/bautismo-de-buceo/` |
| `scuba-diver` | `/courses/scuba-diver/` | `/pt/cursos/scuba-diver/` | `/de/kurse/scuba-diver/` | `/sp/cursos/scuba-diver/` |
| `open-water-diver` | `/courses/open-water-diver/` | `/pt/cursos/open-water-diver/` | `/de/kurse/open-water-diver/` | `/sp/cursos/open-water-diver/` |
| `adventure-diver` | `/courses/adventure-diver/` | `/pt/cursos/adventure-diver/` | `/de/kurse/adventure-diver/` | `/sp/cursos/adventure-diver/` |
| `advanced-open-water` | `/courses/advanced-open-water-diver/` | `/pt/cursos/advanced-open-water-diver/` | `/de/kurse/advanced-open-water-diver/` | `/sp/cursos/advanced-open-water-diver/` |
| `rescue-diver` | `/courses/rescue-diver/` | `/pt/cursos/rescue-diver/` | `/de/kurse/rescue-diver/` | `/sp/cursos/rescue-diver/` |
| `deep-diver` | `/courses/deep-diver/` | `/pt/cursos/mergulho-profundo/` | `/de/kurse/deep-diver/` | `/sp/cursos/buceo-profundo/` |
| `enriched-air-nitrox` | `/courses/enriched-air-nitrox/` | `/pt/cursos/nitrox/` | `/de/kurse/nitrox/` | `/sp/cursos/nitrox/` |
| `peak-performance-buoyancy` | `/courses/peak-performance-buoyancy/` | `/pt/cursos/flutuabilidade-perfeita/` | `/de/kurse/tarierung/` | `/sp/cursos/flotabilidad-perfecta/` |
| `equipment-specialist` | `/courses/equipment-specialist/` | `/pt/cursos/especialista-em-equipamento/` | `/de/kurse/equipment-specialist/` | `/sp/cursos/especialista-en-equipo/` |
| `night-diver` | `/courses/night-diver/` | `/pt/cursos/mergulho-noturno/` | `/de/kurse/nachttauchen/` | `/sp/cursos/buceo-nocturno/` |
| `sidemount-diver` | `/courses/sidemount-diver/` | `/pt/cursos/sidemount/` | `/de/kurse/sidemount/` | `/sp/cursos/sidemount/` |
| `search-and-recovery` | `/courses/search-and-recovery/` | `/pt/cursos/busca-e-recuperacao/` | `/de/kurse/suchen-und-bergen/` | `/sp/cursos/busqueda-y-recuperacion/` |
| `drift-diver` | `/courses/drift-diver/` | `/pt/cursos/mergulho-em-corrente/` | `/de/kurse/stroemungstauchen/` | `/sp/cursos/buceo-en-corriente/` |
| `digital-underwater-photographer` | `/courses/underwater-photography/` | `/pt/cursos/fotografia-subaquatica/` | `/de/kurse/unterwasserfotografie/` | `/sp/cursos/fotografia-submarina/` |
| `emergency-oxygen-provider` | `/courses/emergency-oxygen-provider/` | `/pt/cursos/oxigenio-de-emergencia/` | `/de/kurse/notfall-sauerstoff/` | `/sp/cursos/oxigeno-de-emergencia/` |
| `wreck-diver` | `/courses/wreck-diver/` | `/pt/cursos/mergulho-em-naufragios/` | `/de/kurse/wracktauchen/` | `/sp/cursos/buceo-en-pecios/` |
| `boat-diver` | `/courses/boat-diver/` | `/pt/cursos/mergulho-embarcado/` | `/de/kurse/bootstauchen/` | `/sp/cursos/buceo-desde-barco/` |
| `efr-primary-secondary` | `/courses/efr-primary-secondary-care/` | `/pt/cursos/primeiros-socorros-efr/` | `/de/kurse/erste-hilfe-efr/` | `/sp/cursos/primeros-auxilios-efr/` |
| `efr-care-for-children` | `/courses/efr-care-for-children/` | `/pt/cursos/primeiros-socorros-criancas/` | `/de/kurse/erste-hilfe-kinder/` | `/sp/cursos/primeros-auxilios-ninos/` |
| `efr-instructor` | `/courses/efr-instructor/` | `/pt/cursos/instrutor-efr/` | `/de/kurse/efr-instructor/` | `/sp/cursos/instructor-efr/` |
| `discover-tec` | `/technical-diving/discover-tec/` | `/pt/mergulho-tecnico/discover-tec/` | `/de/technisches-tauchen/discover-tec/` | `/sp/buceo-tecnico/discover-tec/` |
| `tec-40` | `/technical-diving/tec-40/` | `/pt/mergulho-tecnico/tec-40/` | `/de/technisches-tauchen/tec-40/` | `/sp/buceo-tecnico/tec-40/` |
| `tec-45` | `/technical-diving/tec-45/` | `/pt/mergulho-tecnico/tec-45/` | `/de/technisches-tauchen/tec-45/` | `/sp/buceo-tecnico/tec-45/` |
| `tec-50` | `/technical-diving/tec-50/` | `/pt/mergulho-tecnico/tec-50/` | `/de/technisches-tauchen/tec-50/` | `/sp/buceo-tecnico/tec-50/` |
| `tec-trimix-65` | `/technical-diving/tec-trimix-65/` | `/pt/mergulho-tecnico/tec-trimix-65/` | `/de/technisches-tauchen/tec-trimix-65/` | `/sp/buceo-tecnico/tec-trimix-65/` |
| `tec-trimix` | `/technical-diving/tec-trimix/` | `/pt/mergulho-tecnico/tec-trimix/` | `/de/technisches-tauchen/tec-trimix/` | `/sp/buceo-tecnico/tec-trimix/` |
| `tec-sidemount` | `/technical-diving/tec-sidemount/` | `/pt/mergulho-tecnico/tec-sidemount/` | `/de/technisches-tauchen/tec-sidemount/` | `/sp/buceo-tecnico/tec-sidemount/` |
| `gas-blender` | `/technical-diving/gas-blender/` | `/pt/mergulho-tecnico/gas-blender/` | `/de/technisches-tauchen/gas-blender/` | `/sp/buceo-tecnico/gas-blender/` |
| `boat-dives` | `/certified-divers/boat-dives/` | `/pt/mergulhadores-certificados/saidas-de-barco/` | `/de/brevetierte-taucher/bootstauchgaenge/` | `/sp/buceadores-certificados/salidas-en-barco/` |
| `equipment-rental` | `/certified-divers/equipment-rental/` | `/pt/mergulhadores-certificados/aluguer-de-equipamento/` | `/de/brevetierte-taucher/ausruestungsverleih/` | `/sp/buceadores-certificados/alquiler-de-equipo/` |
| `scuba-review` | `/certified-divers/scuba-review/` | `/pt/mergulhadores-certificados/refrescamento/` | `/de/brevetierte-taucher/scuba-review/` | `/sp/buceadores-certificados/repaso-de-habilidades/` |
| `reactivate` | `/certified-divers/reactivate/` | `/pt/mergulhadores-certificados/reactivate/` | `/de/brevetierte-taucher/reactivate/` | `/sp/buceadores-certificados/reactivate/` |
