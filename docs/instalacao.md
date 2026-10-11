# Instalação

## Local (Docker) — recomendado para apresentação

Requisitos: Docker com `docker-compose`, porta 8093 livre, Internet na primeira instalação (imagens Docker e pacotes de idioma do WordPress).

```bash
bin/setup.sh
```

- Site: <http://diverscape.localhost:8093/> · PT `/pt/` · DE `/de/` · ES `/sp/`
- Painel: <http://diverscape.localhost:8093/wp-admin/> — credenciais geradas em `.credentials.local` (não versionar)
- Emails locais (Mailpit): <http://localhost:8029> — em demonstração não deve aparecer nenhum

```bash
bin/setup.sh --reset                     # apaga a base de dados local e reinstala
DVS_FORCE=1 bin/setup.sh                 # reaplica os conteúdos originais por cima de edições
docker-compose run --rm cli wp diverscape status   # resumo + cobertura das 49 linhas de origem
```

A importação é idempotente: correr de novo não duplica páginas, ofertas, perguntas, menus nem media.

## Alojamento WordPress (staging na Pixart ou produção)

Requisitos: WordPress 6.6+ (testado em 7.1), PHP 8.1+ (testado em 8.3), extensão GD com WebP, permalinks «Nome do artigo».

1. Instalar WordPress limpo (idioma do painel à escolha).
2. Copiar `wp-content/themes/diverscape`, `wp-content/plugins/diverscape-core` e `wp-content/plugins/diverscape-demo-mode` (ou usar o pacote `dist/diverscape-proposta-AAAAMMDD.zip`).
3. Ativar o tema **DiversCape** e os plugins **DiversCape Core** e **DiversCape Demo Mode**.
4. Importar conteúdos e media:
   ```bash
   wp diverscape seed
   wp rewrite flush
   wp diverscape status
   ```
   Sem WP-CLI: pedir ao alojamento para correr o comando uma vez, ou importar a base de dados local exportada (`wp db export`) com substituição do domínio (`wp search-replace`).
5. **Staging protegido:** definir no `wp-config.php` `define( 'DVS_DEMO_ACCESS_CODE', 'código-para-o-cliente' );` — o site pede o código (página localizada) antes de mostrar a proposta. Utilizadores com sessão iniciada passam diretamente.

## Lançamento (só com instrução explícita)

1. Resolver os pontos **A** de [dados-a-validar.md](dados-a-validar.md); marcar itens como «Validado».
2. Configurar envio de email (SMTP do domínio) e o destinatário em DiversCape → Dados e definições; testar com um endereço de teste autorizado.
3. Desativar **DiversCape Demo Mode** → formulário passa a enviar, indexação liberta, faixa desaparece.
4. Definições → Leitura: confirmar que a visibilidade para motores de pesquisa está ativa.
5. Redirecionar `/sitemap.xml` → `/wp-sitemap.xml`; manter o redirecionamento http/www → https.
6. Submeter `https://diverscape.net/wp-sitemap.xml` na Search Console.
