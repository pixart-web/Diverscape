#!/usr/bin/env bash
# Gera dist/diverscape-proposta-AAAAMMDD.zip: tema, plugins (com conteúdos e media), documentação e ambiente local.
# Também gera zips instaláveis pelo painel (Plugins/Temas → Adicionar → Carregar).
set -euo pipefail
cd "$(dirname "$0")/.."
STAMP="$(date +%Y%m%d)"
OUT="dist/diverscape-proposta-${STAMP}"
rm -rf "$OUT" && mkdir -p "$OUT/instalaveis"
( cd wp-content/themes && zip -qr "../../$OUT/instalaveis/diverscape-tema.zip" diverscape -x '*.DS_Store' )
( cd wp-content/plugins && zip -qr "../../$OUT/instalaveis/diverscape-core.zip" diverscape-core -x '*.DS_Store' )
( cd wp-content/plugins && zip -qr "../../$OUT/instalaveis/diverscape-demo-mode.zip" diverscape-demo-mode -x '*.DS_Store' )
cp -R README.md docker-compose.yml .env.example config bin docs "$OUT/"
mkdir -p "$OUT/wp-content/themes" "$OUT/wp-content/plugins" "$OUT/tools" "$OUT/research"
cp -R wp-content/themes/diverscape "$OUT/wp-content/themes/"
cp -R wp-content/plugins/diverscape-core wp-content/plugins/diverscape-demo-mode "$OUT/wp-content/plugins/"
cp tools/package.json tools/package-lock.json tools/*.mjs "$OUT/tools/" 2>/dev/null || true
cp -R tools/tests "$OUT/tools/"
cp research/*.py "$OUT/research/"
find "$OUT" -name '.DS_Store' -delete
# Verificação: todas as imagens referenciadas pelo importador existem no pacote.
missing=0
for f in $(grep -oE "^	'[a-z0-9-]+\.(jpg|png)' +=>" wp-content/plugins/diverscape-core/content/media.php | grep -oE "[a-z0-9-]+\.(jpg|png)" | sort -u); do
  [ -f "$OUT/wp-content/plugins/diverscape-core/content/media/$f" ] || { echo "Em falta: $f"; missing=1; }
done
[ "$missing" = 0 ] || exit 1
( cd dist && zip -qr "diverscape-proposta-${STAMP}.zip" "diverscape-proposta-${STAMP}" )
echo "Pacote: dist/diverscape-proposta-${STAMP}.zip ($(du -h "dist/diverscape-proposta-${STAMP}.zip" | cut -f1))"
ls -la "$OUT/instalaveis"
