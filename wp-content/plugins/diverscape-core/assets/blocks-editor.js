/* Editor dos blocos dinâmicos DiversCape: pré-visualização do servidor + opções no painel lateral.
   As definições (atributos, rótulos, controlos) vêm do PHP em window.dvsBlocks. Sem passo de compilação. */
(({ blocks, element, components, blockEditor, serverSideRender: ServerSideRender }) => {
  const el = element.createElement;
  const { InspectorControls, useBlockProps, MediaUpload, MediaUploadCheck } = blockEditor;
  const { PanelBody, SelectControl, TextControl, TextareaControl, RangeControl, ToggleControl, Disabled, Button, BaseControl } = components;
  const cfg = window.dvsBlocks || { blocks: {}, lang: 'en' };

  const field = (props, key, a) => {
    const value = props.attributes[key];
    const onChange = (v) => props.setAttributes({ [key]: v });
    switch (a.control) {
      case 'media':
        return el(BaseControl, { key, label: a.label, id: `dvs-${key}` },
          el(MediaUploadCheck, null, el(MediaUpload, {
            allowedTypes: ['image'],
            value: /^\d+$/.test(value || '') ? Number(value) : undefined,
            onSelect: (m) => onChange(String(m.id)),
            render: ({ open }) => el('div', null,
              value ? el('p', { style: { margin: '0 0 6px', fontSize: '12px' } }, `Atual: ${value}`) : null,
              el(Button, { variant: 'secondary', onClick: open }, value ? 'Substituir imagem' : 'Escolher imagem'),
              value ? el(Button, { variant: 'link', isDestructive: true, onClick: () => onChange('') }, 'Remover') : null),
          })));
      case 'select':
        return el(SelectControl, { key, label: a.label, value: value ?? '', options: Object.entries(a.options).map(([v, l]) => ({ value: v, label: l })), onChange });
      case 'range':
        return el(RangeControl, { key, label: a.label, value, min: a.min, max: a.max, onChange });
      case 'toggle':
        return el(ToggleControl, { key, label: a.label, checked: !!value, onChange });
      case 'textarea':
        return el(TextareaControl, { key, label: a.label, value: value || '', onChange, rows: 4 });
      default:
        return el(TextControl, { key, label: a.label, value: value || '', onChange });
    }
  };

  Object.entries(cfg.blocks).forEach(([name, def]) => {
    blocks.registerBlockType(name, {
      apiVersion: 3,
      title: def.title,
      category: 'diverscape',
      icon: 'sos',
      edit: (props) => {
        const blockProps = useBlockProps();
        const keys = Object.keys(def.attrs);
        return el('div', blockProps,
          keys.length ? el(InspectorControls, null,
            el(PanelBody, { title: 'Conteúdo', initialOpen: true }, keys.map((k) => field(props, k, def.attrs[k])),
              el('p', { style: { fontSize: '12px', color: '#757575' } }, 'Ligações internas: use dvs:courses, dvs:offer:open-water-diver ou dvs:request — são resolvidas no idioma desta página.'))) : null,
          el(Disabled, null, el(ServerSideRender, { block: name, attributes: props.attributes, urlQueryArgs: { dvs_lang: cfg.lang } })));
      },
      save: () => null,
    });
  });
})(window.wp);
