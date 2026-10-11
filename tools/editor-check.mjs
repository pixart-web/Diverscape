// Abre páginas no editor de blocos e confirma que não há blocos inválidos nem erros. Credenciais de teste locais (.credentials.local).
import { chromium } from 'playwright';
import { readFileSync } from 'node:fs';
import { execSync } from 'node:child_process';
import { chromiumPath } from './browser.mjs';
const base = process.env.DVS_URL || 'http://diverscape.localhost:8093';
const cred = Object.fromEntries(readFileSync(new URL('../.credentials.local', import.meta.url), 'utf8').trim().split('\n').map((l) => l.split('=')));
const ids = execSync(`docker-compose run --rm -T cli wp post list --post_type=page,dvs_offer,dvs_faq --post_status=any --format=ids`, { cwd: new URL('..', import.meta.url).pathname, stdio: ['ignore', 'pipe', 'ignore'] }).toString().trim().split(' ');
const b = await chromium.launch({ executablePath: chromiumPath() });
const page = await b.newPage({ viewport: { width: 1440, height: 900 } });
await page.goto(`${base}/wp-login.php`);
await page.fill('#user_login', cred.USER);
await page.fill('#user_pass', cred.PASS);
await page.click('#wp-submit');
await page.waitForURL(/wp-admin/);
const sample = process.argv[2] === 'all' ? ids : ids.filter((_, i) => i % 6 === 0);
const bad = [];
for (const id of sample) {
  await page.goto(`${base}/wp-admin/post.php?post=${id}&action=edit`);
  await page.waitForFunction(() => window.wp?.data?.select('core/block-editor')?.getBlocks().length > 0, null, { timeout: 30000 }).catch(() => {});
  const r = await page.evaluate(() => {
    const blocks = wp.data.select('core/block-editor').getBlocks();
    const flat = (bs) => bs.flatMap((x) => [x, ...flat(x.innerBlocks)]);
    const all = flat(blocks);
    return { n: all.length, invalid: all.filter((x) => !x.isValid).map((x) => x.name), title: wp.data.select('core/editor').getEditedPostAttribute('title') };
  });
  if (!r.n || r.invalid.length) bad.push(`${id} ${r.title}: blocos=${r.n} inválidos=${r.invalid.join(',')}`);
}
console.log(`Verificados ${sample.length} de ${ids.length} conteúdos no editor.`);
console.log(bad.length ? bad.join('\n') : 'Sem blocos inválidos.');
await b.close();
