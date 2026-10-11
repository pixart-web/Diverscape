<?php
/**
 * Painel do catálogo central: edição de variantes, preços, durações e estado de validação.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'add_meta_boxes_dvs_item',
	static function () {
		add_meta_box( 'dvs-item', 'Dados comerciais (partilhados pelos quatro idiomas)', 'dvs_item_metabox', 'dvs_item', 'normal', 'high' );
		add_meta_box( 'dvs-item-pages', 'Páginas traduzidas', 'dvs_item_pages_metabox', 'dvs_item', 'side' );
	}
);

function dvs_item_metabox( WP_Post $post ): void {
	$d = array_merge( dvs_item_defaults(), (array) get_post_meta( $post->ID, '_dvs_item', true ) );
	wp_nonce_field( 'dvs_item_save', 'dvs_item_nonce' );
	wp_enqueue_media();
	$langs = dvs_languages();
	$units = array( '' => '— não indicar —', 'half_day' => 'Meio dia', 'day' => '1 dia', 'days' => 'N dias', 'morning' => 'Manhã', 'half_morning' => 'Meia manhã' );
	$types = array( 'fixed' => 'Preço fixo', 'on_request' => 'Sob consulta', 'pending' => 'Por validar (mostra «Consultar»)' );
	?>
	<style>
		.dvs-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px 20px}
		.dvs-grid label{font-weight:600;display:block;margin-bottom:4px}
		.dvs-variants{margin-top:16px;border-collapse:collapse;width:100%}
		.dvs-variants th,.dvs-variants td{padding:6px;border-bottom:1px solid #dcdcde;vertical-align:top;text-align:left}
		.dvs-variants input[type=text]{width:100%}
		.dvs-variants input[type=number]{width:90px}
		.dvs-note{background:#fcf9e8;border-left:4px solid #dba617;padding:8px 12px;margin:12px 0}
	</style>
	<p class="dvs-note">Valores vazios nunca são tratados como zero. Um preço «Por validar» aparece no site como «Consultar». Durações só devem ser preenchidas quando confirmadas.</p>
	<div class="dvs-grid">
		<p><label for="dvs_category">Categoria</label>
			<select id="dvs_category" name="dvs[category]">
			<?php foreach ( dvs_categories() as $k => $c ) : ?>
				<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $d['category'], $k ); ?>><?php echo esc_html( dvs_default_strings()[ $c['label'] ]['pt'] ?? $k ); ?></option>
			<?php endforeach; ?>
			</select></p>
		<p><label for="dvs_status">Estado comercial</label>
			<select id="dvs_status" name="dvs[status]">
				<option value="source" <?php selected( $d['status'], 'source' ); ?>>Publicado no site atual — a confirmar</option>
				<option value="validated" <?php selected( $d['status'], 'validated' ); ?>>Validado pela DiversCape</option>
			</select></p>
		<p><label for="dvs_min_age">Idade mínima (vazio = não indicar)</label>
			<input id="dvs_min_age" type="number" min="0" max="99" name="dvs[min_age]" value="<?php echo esc_attr( (string) ( $d['min_age'] ?? '' ) ); ?>"></p>
		<p><label for="dvs_cert">Certificação</label>
			<select id="dvs_cert" name="dvs[certification]">
				<option value="" <?php selected( $d['certification'], null ); ?>>Não indicar</option>
				<option value="yes" <?php selected( $d['certification'], 'yes' ); ?>>Curso com certificação</option>
				<option value="no" <?php selected( $d['certification'], 'no' ); ?>>Experiência sem certificação</option>
			</select></p>
		<p><label for="dvs_dur_unit">Duração</label>
			<select id="dvs_dur_unit" name="dvs[duration][unit]">
			<?php foreach ( $units as $k => $l ) : ?>
				<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $d['duration']['unit'] ?? '', $k ); ?>><?php echo esc_html( $l ); ?></option>
			<?php endforeach; ?>
			</select>
			<input type="number" min="2" max="30" name="dvs[duration][qty]" value="<?php echo esc_attr( (string) ( $d['duration']['qty'] ?? '' ) ); ?>" aria-label="Número de dias" style="width:70px"></p>
		<p><label>Imagem</label>
			<input type="hidden" id="dvs_image_id" name="dvs[image_id]" value="<?php echo esc_attr( (string) $d['image_id'] ); ?>">
			<span id="dvs_image_preview"><?php echo $d['image_id'] ? wp_get_attachment_image( (int) $d['image_id'], array( 120, 80 ) ) : ''; ?></span><br>
			<button type="button" class="button" id="dvs_image_btn">Escolher imagem</button></p>
		<p><label>Público</label>
			<?php foreach ( dvs_audiences() as $k => $s ) : ?>
				<label style="font-weight:400"><input type="checkbox" name="dvs[audience][]" value="<?php echo esc_attr( $k ); ?>" <?php checked( in_array( $k, (array) $d['audience'], true ) ); ?>> <?php echo esc_html( dvs_default_strings()[ $s ]['pt'] ?? $k ); ?></label>
			<?php endforeach; ?></p>
		<p><label>Opções</label>
			<label style="font-weight:400"><input type="checkbox" name="dvs[featured]" value="1" <?php checked( $d['featured'] ); ?>> Destaque na homepage</label>
			<label style="font-weight:400"><input type="checkbox" name="dvs[junior]" value="1" <?php checked( $d['junior'] ); ?>> Versão Junior disponível</label>
			<label style="font-weight:400"><input type="checkbox" name="dvs[package]" value="1" <?php checked( $d['package'] ); ?>> Pacote de mergulhos (escolha de nº e equipamento)</label></p>
	</div>

	<h3>Variantes e preços</h3>
	<table class="dvs-variants" id="dvs-variants">
		<thead><tr><th>Chave</th><th>Rótulo (vazio = variante única)</th><th>Preço (EUR)</th><th>Tipo</th><th>Duração</th><th>Pacote</th><th>Origem</th><th></th></tr></thead>
		<tbody>
		<?php
		$rows   = $d['variants'] ?: array( array( 'key' => 'standard' ) );
		$rows[] = array( '__template' => true );
		foreach ( $rows as $i => $v ) :
			$tpl  = ! empty( $v['__template'] );
			$name = $tpl ? 'dvs[variants][__i__]' : "dvs[variants][{$i}]";
			?>
			<tr <?php echo $tpl ? 'id="dvs-variant-template" hidden' : ''; ?>>
				<td><input type="text" name="<?php echo esc_attr( $name ); ?>[key]" value="<?php echo esc_attr( $v['key'] ?? '' ); ?>" <?php disabled( $tpl ); ?> pattern="[a-z0-9-]+" aria-label="Chave"></td>
				<td>
					<?php foreach ( $langs as $l => $cfg ) : ?>
						<input type="text" name="<?php echo esc_attr( $name ); ?>[label][<?php echo esc_attr( $l ); ?>]" value="<?php echo esc_attr( $v['label'][ $l ] ?? '' ); ?>" placeholder="<?php echo esc_attr( $cfg['short'] ); ?>" aria-label="Rótulo <?php echo esc_attr( $cfg['name'] ); ?>" <?php disabled( $tpl ); ?>>
					<?php endforeach; ?>
				</td>
				<td><input type="number" step="0.01" min="0" name="<?php echo esc_attr( $name ); ?>[price]" value="<?php echo esc_attr( isset( $v['price'] ) && null !== $v['price'] ? (string) $v['price'] : '' ); ?>" aria-label="Preço" <?php disabled( $tpl ); ?>></td>
				<td><select name="<?php echo esc_attr( $name ); ?>[type]" aria-label="Tipo de preço" <?php disabled( $tpl ); ?>>
					<?php foreach ( $types as $k => $l ) : ?>
						<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $v['type'] ?? 'fixed', $k ); ?>><?php echo esc_html( $l ); ?></option>
					<?php endforeach; ?>
				</select></td>
				<td><select name="<?php echo esc_attr( $name ); ?>[duration][unit]" aria-label="Duração" <?php disabled( $tpl ); ?>>
					<?php foreach ( $units as $k => $l ) : ?>
						<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $v['duration']['unit'] ?? '', $k ); ?>><?php echo esc_html( $l ); ?></option>
					<?php endforeach; ?>
				</select><input type="number" min="2" max="30" name="<?php echo esc_attr( $name ); ?>[duration][qty]" value="<?php echo esc_attr( (string) ( $v['duration']['qty'] ?? '' ) ); ?>" aria-label="Dias" <?php disabled( $tpl ); ?>></td>
				<td><input type="number" min="1" max="50" name="<?php echo esc_attr( $name ); ?>[dives]" value="<?php echo esc_attr( (string) ( $v['dives'] ?? '' ) ); ?>" placeholder="Nº" aria-label="Número de mergulhos" style="width:60px" <?php disabled( $tpl ); ?>>
					<select name="<?php echo esc_attr( $name ); ?>[equipment]" aria-label="Equipamento" <?php disabled( $tpl ); ?>>
						<option value="">—</option>
						<option value="1" <?php selected( isset( $v['equipment'] ) && true === $v['equipment'] ); ?>>Com equip.</option>
						<option value="0" <?php selected( isset( $v['equipment'] ) && false === $v['equipment'] ); ?>>Sem equip.</option>
					</select></td>
				<td><input type="hidden" name="<?php echo esc_attr( $name ); ?>[rows]" value="<?php echo esc_attr( implode( ',', (array) ( $v['rows'] ?? array() ) ) ); ?>" <?php disabled( $tpl ); ?>><code><?php echo esc_html( implode( ', ', (array) ( $v['rows'] ?? array() ) ) ?: 'nova' ); ?></code></td>
				<td><button type="button" class="button-link button-link-delete dvs-remove">Remover</button></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p><button type="button" class="button" id="dvs-add-variant">Adicionar variante</button></p>

	<h3>Notas internas e conflitos</h3>
	<p><label for="dvs_notes"><strong>Notas internas</strong> (não aparecem no site)</label>
		<textarea id="dvs_notes" class="widefat" rows="3" name="dvs[notes]"><?php echo esc_textarea( $d['notes'] ); ?></textarea></p>
	<p><label for="dvs_conflicts"><strong>Conflitos a resolver</strong> (um por linha)</label>
		<textarea id="dvs_conflicts" class="widefat" rows="3" name="dvs[conflicts]"><?php echo esc_textarea( implode( "\n", (array) $d['conflicts'] ) ); ?></textarea></p>
	<script>
	(() => {
		const body = document.querySelector('#dvs-variants tbody');
		const tpl = document.getElementById('dvs-variant-template');
		let n = body.children.length + 100;
		document.getElementById('dvs-add-variant').addEventListener('click', () => {
			const row = tpl.cloneNode(true);
			row.removeAttribute('id'); row.hidden = false; n++;
			row.querySelectorAll('[name]').forEach((f) => { f.name = f.name.replace('__i__', n); f.disabled = false; });
			body.insertBefore(row, tpl);
		});
		body.addEventListener('click', (e) => {
			if (e.target.classList.contains('dvs-remove') && confirm('Remover esta variante?')) e.target.closest('tr').remove();
		});
		const btn = document.getElementById('dvs_image_btn');
		btn && btn.addEventListener('click', () => {
			const frame = wp.media({ title: 'Imagem do item', multiple: false, library: { type: 'image' } });
			frame.on('select', () => {
				const a = frame.state().get('selection').first().toJSON();
				document.getElementById('dvs_image_id').value = a.id;
				document.getElementById('dvs_image_preview').innerHTML = '<img src="' + (a.sizes.thumbnail || a).url + '" width="120" alt="">';
			});
			frame.open();
		});
	})();
	</script>
	<?php
}

function dvs_item_pages_metabox( WP_Post $post ): void {
	$key = $post->post_name;
	echo '<ul>';
	foreach ( dvs_languages() as $code => $cfg ) {
		$id = $key ? dvs_group_post( 'offer:' . $key, $code, true ) : 0;
		if ( $id ) {
			printf( '<li>%s: <a href="%s">editar texto</a> · <a href="%s" target="_blank" rel="noopener">ver</a></li>', esc_html( $cfg['name'] ), esc_url( get_edit_post_link( $id ) ), esc_url( get_permalink( $id ) ) );
		} else {
			printf( '<li>%s: <em>sem página</em></li>', esc_html( $cfg['name'] ) );
		}
	}
	echo '</ul><p class="description">A chave estável deste item é o slug: <code>' . esc_html( $key ) . '</code>. Não alterar depois do lançamento.</p>';
}

add_action(
	'save_post_dvs_item',
	static function ( int $post_id ) {
		if ( ! isset( $_POST['dvs_item_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dvs_item_nonce'] ), 'dvs_item_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		$in   = isset( $_POST['dvs'] ) && is_array( $_POST['dvs'] ) ? wp_unslash( $_POST['dvs'] ) : array(); // phpcs:ignore
		$old  = (array) get_post_meta( $post_id, '_dvs_item', true );
		$data = dvs_sanitize_item( $in, $old );
		update_post_meta( $post_id, '_dvs_item', $data );
	}
);

/** Normaliza os dados do formulário do item. */
function dvs_sanitize_item( array $in, array $old = array() ): array {
	$dur = static function ( $d ) {
		$unit = sanitize_key( $d['unit'] ?? '' );
		if ( ! in_array( $unit, array( 'half_day', 'day', 'days', 'morning', 'half_morning' ), true ) ) {
			return null;
		}
		$out = array( 'unit' => $unit );
		if ( 'days' === $unit ) {
			$out['qty'] = max( 2, absint( $d['qty'] ?? 2 ) );
		}
		return $out;
	};
	$variants = array();
	foreach ( (array) ( $in['variants'] ?? array() ) as $v ) {
		$key = sanitize_title( $v['key'] ?? '' );
		if ( ! $key ) {
			continue;
		}
		$label = array();
		foreach ( array_keys( dvs_languages() ) as $l ) {
			$t = sanitize_text_field( $v['label'][ $l ] ?? '' );
			if ( '' !== $t ) {
				$label[ $l ] = $t;
			}
		}
		$price = isset( $v['price'] ) && '' !== trim( (string) $v['price'] ) && is_numeric( $v['price'] ) ? round( (float) $v['price'], 2 ) : null;
		$type  = in_array( $v['type'] ?? '', array( 'fixed', 'on_request', 'pending' ), true ) ? $v['type'] : 'pending';
		if ( 'fixed' === $type && null === $price ) {
			$type = 'pending';
		}
		$row = array(
			'key'   => $key,
			'label' => $label ?: null,
			'price' => $price,
			'type'  => $type,
			'rows'  => array_values( array_filter( array_map( 'sanitize_key', is_array( $v['rows'] ?? null ) ? $v['rows'] : explode( ',', (string) ( $v['rows'] ?? '' ) ) ) ) ),
		);
		$d = $dur( $v['duration'] ?? array() );
		if ( $d ) {
			$row['duration'] = $d;
		}
		if ( ! empty( $v['dives'] ) ) {
			$row['dives'] = absint( $v['dives'] );
		}
		if ( isset( $v['equipment'] ) && '' !== $v['equipment'] ) {
			$row['equipment'] = '1' === (string) $v['equipment'] || true === $v['equipment'];
		}
		$variants[] = $row;
	}
	$aud = array_values( array_intersect( (array) ( $in['audience'] ?? array() ), array_keys( dvs_audiences() ) ) );
	$age = isset( $in['min_age'] ) && '' !== (string) $in['min_age'] ? absint( $in['min_age'] ) : null;
	$cert = in_array( $in['certification'] ?? '', array( 'yes', 'no' ), true ) ? $in['certification'] : null;
	$conf = is_array( $in['conflicts'] ?? null ) ? $in['conflicts'] : preg_split( '/\r\n|\n/', (string) ( $in['conflicts'] ?? '' ) );
	return array(
		'category'      => array_key_exists( $in['category'] ?? '', dvs_categories() ) ? $in['category'] : 'courses',
		'order'         => isset( $in['order'] ) ? (int) $in['order'] : (int) ( $old['order'] ?? 0 ),
		'image'         => sanitize_file_name( $in['image'] ?? ( $old['image'] ?? '' ) ),
		'image_id'      => absint( $in['image_id'] ?? 0 ),
		'featured'      => ! empty( $in['featured'] ),
		'audience'      => $aud,
		'junior'        => ! empty( $in['junior'] ),
		'min_age'       => $age,
		'duration'      => $dur( $in['duration'] ?? array() ),
		'certification' => $cert,
		'package'       => ! empty( $in['package'] ),
		'variants'      => $variants,
		'status'        => 'validated' === ( $in['status'] ?? '' ) ? 'validated' : 'source',
		'notes'         => sanitize_textarea_field( (string) ( $in['notes'] ?? '' ) ),
		'conflicts'     => array_values( array_filter( array_map( 'sanitize_text_field', $conf ) ) ),
	);
}

/* Colunas da lista do catálogo. */
add_filter(
	'manage_dvs_item_posts_columns',
	static function ( array $cols ): array {
		return array(
			'cb'           => $cols['cb'],
			'title'        => 'Item',
			'dvs_category' => 'Categoria',
			'dvs_prices'   => 'Variantes e preços',
			'dvs_status'   => 'Estado',
			'dvs_pages'    => 'Páginas',
		);
	}
);

add_action(
	'manage_dvs_item_posts_custom_column',
	static function ( string $col, int $id ) {
		$d = array_merge( dvs_item_defaults(), (array) get_post_meta( $id, '_dvs_item', true ) );
		switch ( $col ) {
			case 'dvs_category':
				$c = dvs_categories()[ $d['category'] ]['label'] ?? '';
				echo esc_html( dvs_default_strings()[ $c ]['pt'] ?? $d['category'] );
				break;
			case 'dvs_prices':
				foreach ( $d['variants'] as $v ) {
					$label = $v['label']['pt'] ?? 'Único';
					$price = 'fixed' === $v['type'] && null !== $v['price'] ? dvs_money( (float) $v['price'], 'pt' ) : ( 'on_request' === $v['type'] ? 'Sob consulta' : 'Por validar' );
					printf( '<div>%s — <strong>%s</strong></div>', esc_html( $label ), esc_html( $price ) );
				}
				break;
			case 'dvs_status':
				echo 'validated' === $d['status'] ? '<span style="color:#008a20">Validado</span>' : '<span style="color:#996800">A confirmar</span>';
				if ( $d['conflicts'] ) {
					printf( '<br><span style="color:#b32d2e">%d conflito(s)</span>', count( $d['conflicts'] ) );
				}
				break;
			case 'dvs_pages':
				$post = get_post( $id );
				$out  = array();
				foreach ( dvs_languages() as $code => $cfg ) {
					$oid   = dvs_group_post( 'offer:' . $post->post_name, $code, true );
					$out[] = $oid ? '<a href="' . esc_url( get_edit_post_link( $oid ) ) . '">' . esc_html( $cfg['short'] ) . '</a>' : '<span style="color:#b32d2e">' . esc_html( $cfg['short'] ) . '</span>';
				}
				echo implode( ' · ', $out ); // phpcs:ignore
				break;
		}
	},
	10,
	2
);
