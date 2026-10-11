#!/usr/bin/env bash
# Instala a proposta DiversCape e importa catálogo, páginas, traduções e media (idempotente).
# Uso: bin/setup.sh            instala se necessário e (re)importa conteúdos sem duplicar
#      bin/setup.sh --reset    apaga a base de dados local e reinstala de raiz
#      DVS_FORCE=1 bin/setup.sh  reaplica os conteúdos originais por cima de edições locais
set -euo pipefail
cd "$(dirname "$0")/.."

[ -f .env ] || cp .env.example .env
set -a; . ./.env; set +a
if docker compose version >/dev/null 2>&1; then COMPOSE="docker compose"; else COMPOSE="docker-compose"; fi
DOMAIN="${DVS_DOMAIN:-diverscape.localhost}"
PORT="${WP_PORT:-8093}"
if [ "$PORT" = "80" ]; then URL="http://${DOMAIN}"; else URL="http://${DOMAIN}:${PORT}"; fi

if [ "${1:-}" = "--reset" ]; then $COMPOSE down -v --remove-orphans; fi
$COMPOSE up -d db wordpress mailpit

echo "A aguardar o servidor web em ${URL} ..."
for _ in $(seq 1 60); do curl -s -o /dev/null "http://localhost:${PORT}/wp-login.php" && break; sleep 2; done

wp() { $COMPOSE run --rm -T cli wp "$@"; }

if ! wp core is-installed >/dev/null 2>&1; then
  ADMIN_PASS="$(openssl rand -hex 12)"
  wp core install --url="${URL}" --title="DiversCape" --admin_user=diverscape-admin \
    --admin_password="${ADMIN_PASS}" --admin_email=admin@diverscape.test --skip-email
  printf 'URL=%s/wp-admin/\nUSER=diverscape-admin\nPASS=%s\n' "${URL}" "${ADMIN_PASS}" > .credentials.local
  chmod 600 .credentials.local
  echo "Credenciais locais em .credentials.local (não versionar)."
fi

# Pacotes de idioma do núcleo (datas, painel). Falha silenciosa se não houver Internet.
for loc in pt_PT de_DE es_ES en_GB; do wp language core install "$loc" >/dev/null 2>&1 || echo "Aviso: pacote $loc indisponível."; done
wp plugin delete akismet hello >/dev/null 2>&1 || true
wp theme activate diverscape
wp plugin activate diverscape-core diverscape-demo-mode
wp rewrite structure '/%postname%/' >/dev/null
wp option update timezone_string 'Europe/Lisbon' >/dev/null

FORCE="${DVS_FORCE:+--force}"
wp diverscape seed ${FORCE}
wp rewrite flush >/dev/null
wp diverscape status

echo
echo "Site:   ${URL}/   (PT ${URL}/pt/ · DE ${URL}/de/ · ES ${URL}/sp/)"
echo "Painel: ${URL}/wp-admin/ (credenciais em .credentials.local)"
echo "Emails locais (Mailpit): http://localhost:${MAILPIT_PORT:-8029}"
