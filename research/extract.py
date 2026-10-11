"""Extrai estrutura legível do HTML original: texto em ordem, tabelas, imagens e links."""
import sys, re
from html.parser import HTMLParser

class P(HTMLParser):
    def __init__(s):
        super().__init__(); s.out=[]; s.buf=''; s.skip=0; s.row=None; s.cell=None
    def flush(s):
        t=re.sub(r'\s+',' ',s.buf).strip()
        if t: s.out.append(t)
        s.buf=''
    def handle_starttag(s,tag,a):
        a=dict(a)
        if tag in('script','style'): s.skip+=1; return
        if tag=='tr': s.flush(); s.row=[]
        elif tag in('td','th'): s.flush(); s.cell=''
        elif tag in('h1','h2','h3','h4','h5','h6','p','div','li','br','section','button'):
            s.flush()
            if tag[0]=='h' and len(tag)==2: s.buf=f'[{tag}] '
            if tag in('section','div') and a.get('id'): s.out.append(f'#ID {a["id"]}')
        if tag=='img': s.flush(); s.out.append(f'[img] {a.get("src")} alt="{a.get("alt","")}"')
        if tag=='a' and a.get('href'): s.flush(); s.out.append(f'[a] {a.get("href")}')
        if tag=='iframe': s.flush(); s.out.append(f'[iframe] {a.get("src")}')
        if tag in('input','textarea','select'): s.flush(); s.out.append(f'[{tag}] name={a.get("name")} ph="{a.get("placeholder","")}" type={a.get("type")}')
    def handle_endtag(s,tag):
        if tag in('script','style'): s.skip-=1; return
        if tag in('td','th') and s.row is not None:
            s.row.append(re.sub(r'\s+',' ',(s.cell or '')+s.buf).strip()); s.buf=''; s.cell=None
        elif tag=='tr' and s.row is not None:
            s.out.append('ROW | '+' | '.join(s.row)); s.row=None
        elif tag in('h1','h2','h3','h4','h5','h6','p','div','li'): s.flush()
    def handle_data(s,d):
        if s.skip: return
        s.buf+=d
p=P(); p.feed(open(sys.argv[1],encoding='utf-8',errors='replace').read()); p.flush()
prev=None
for l in p.out:
    if l!=prev: print(l)
    prev=l
