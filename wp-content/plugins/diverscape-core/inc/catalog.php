<?php
/**
 * Catálogo central: um registo `dvs_item` por curso/experiência, com variantes e preços.
 *
 * Os quatro idiomas leem daqui preços, variantes, durações e público. As páginas traduzidas
 * (`dvs_offer`) só guardam texto e ligam-se ao item pela chave estável (`_dvs_item`).
 * Uma alteração de preço reflete-se na homepage, catálogo, detalhe, tabela de preços e resumo
 * do formulário de todas as línguas.
 */

defined( 'ABSPATH' ) || exit;

/** Categorias do catálogo, por ordem, com a página onde vivem as respetivas ofertas. */
function dvs_categories(): array {
	return array(
		'experiences' => array( 'page' => 'page:experiences', 'label' => 'cat_experiences', 'short' => 'cat_experiences_short' ),
		'courses'     => array( 'page' => 'page:courses', 'label' => 'cat_courses', 'short' => 'cat_courses_short' ),
		'specialties' => array( 'page' => 'page:courses', 'label' => 'cat_specialties', 'short' => 'cat_specialties_short' ),
		'first-aid'   => array( 'page' => 'page:courses', 'label' => 'cat_first_aid', 'short' => 'cat_first_aid_short' ),
		'technical'   => array( 'page' => 'page:technical-diving', 'label' => 'cat_technical', 'short' => 'cat_technical_short' ),
		'certified'   => array( 'page' => 'page:certified-divers', 'label' => 'cat_certified', 'short' => 'cat_certified_short' ),
	);
}

/** Públicos usados no filtro (só os que o conteúdo recolhido permite atribuir com fiabilidade). */
function dvs_audiences(): array {
	return array(
		'first-time' => 'aud_first_time',
		'kids'       => 'aud_kids',
		'certified'  => 'aud_certified',
		'tec'        => 'aud_tec',
	);
}

/** Estrutura por omissão de um item. */
function dvs_item_defaults(): array {
	return array(
		'key'           => '',
		'category'      => 'courses',
		'order'         => 0,
		'image'         => '',
		'image_id'      => 0,
		'featured'      => false,
		'audience'      => array(),
		'junior'        => false,
		'min_age'       => null,
		'duration'      => null,
		'certification' => null,
		'package'       => false,
		'variants'      => array(),
		'status'        => 'source',
		'notes'         => '',
		'conflicts'     => array(),
	);
}

/** Todos os itens publicados, ordenados por categoria e ordem. */
function dvs_catalog(): array {
	static $items = null;
	if ( null !== $items ) {
		return $items;
	}
	$items = array();
	$posts = get_posts(
		array(
			'post_type'        => 'dvs_item',
			'post_status'      => 'publish',
			'numberposts'      => -1,
			'orderby'          => 'menu_order title',
			'order'            => 'ASC',
			'suppress_filters' => true,
		)
	);
	$cat_order = array_flip( array_keys( dvs_categories() ) );
	foreach ( $posts as $p ) {
		$data = get_post_meta( $p->ID, '_dvs_item', true );
		if ( ! is_array( $data ) ) {
			continue;
		}
		$item            = array_merge( dvs_item_defaults(), $data );
		$item['key']     = $p->post_name;
		$item['post_id'] = $p->ID;
		$item['name']    = $p->post_title;
		$item['order']   = (int) $p->menu_order;
		$items[ $item['key'] ] = $item;
	}
	uasort(
		$items,
		static fn( $a, $b ) => array( $cat_order[ $a['category'] ] ?? 99, (int) $a['order'] ) <=> array( $cat_order[ $b['category'] ] ?? 99, (int) $b['order'] )
	);
	return $items;
}

function dvs_catalog_flush(): void {
	// Os caches estáticos vivem por pedido; esta função existe para o importador.
	wp_cache_delete( 'dvs_catalog', 'dvs' );
}

function dvs_item( string $key ): ?array {
	return dvs_catalog()[ $key ] ?? null;
}

function dvs_items_by_category( string $category ): array {
	return array_filter( dvs_catalog(), static fn( $i ) => $i['category'] === $category );
}

/** Variante por chave. */
function dvs_variant( array $item, string $vkey ): ?array {
	foreach ( $item['variants'] as $v ) {
		if ( $v['key'] === $vkey ) {
			return $v;
		}
	}
	return null;
}

/** Rótulo da variante no idioma atual (null = variante única sem rótulo). */
function dvs_variant_label( array $variant, ?string $lang = null ): string {
	$lang = $lang ?: dvs_lang();
	if ( empty( $variant['label'] ) ) {
		return '';
	}
	return (string) ( $variant['label'][ $lang ] ?? $variant['label']['en'] ?? '' );
}

/* ---------------------------------------------------------------------------------------------
 * Preços
 * ------------------------------------------------------------------------------------------- */

/** Modo de apresentação: published (preços de origem com aviso) | hidden (tudo «Consultar»). */
function dvs_price_mode(): string {
	$mode = get_option( 'dvs_price_mode', 'published' );
	return in_array( $mode, array( 'published', 'hidden' ), true ) ? $mode : 'published';
}

/** Valor em EUR formatado segundo o idioma. */
function dvs_money( float $amount, ?string $lang = null ): string {
	$lang = $lang ?: dvs_lang();
	$num  = number_format( $amount, ( floor( $amount ) == $amount ) ? 0 : 2, 'en' === $lang ? '.' : ',', 'en' === $lang ? ',' : ' ' ); // phpcs:ignore
	return 'en' === $lang ? '€' . $num : $num . ' €';
}

/** Uma variante tem preço público mostrável? */
function dvs_variant_has_price( array $v ): bool {
	return 'published' === dvs_price_mode() && 'fixed' === ( $v['type'] ?? '' ) && is_numeric( $v['price'] ?? null );
}

/** Texto do preço de uma variante. */
function dvs_variant_price_text( array $v ): string {
	if ( dvs_variant_has_price( $v ) ) {
		return dvs_money( (float) $v['price'] );
	}
	return 'on_request' === ( $v['type'] ?? '' ) ? dvs_t( 'price_on_request' ) : dvs_t( 'price_consult' );
}

/**
 * Resumo do preço de um item: valor único, «desde X» (só se houver variante com esse valor
 * e mais do que um preço), «Sob consulta» ou «Consultar». Nunca converte vazio em zero.
 *
 * @return array{text:string, amount:?float, from:bool, kind:string}
 */
function dvs_item_price( array $item ): array {
	$prices = array();
	$kinds  = array();
	foreach ( $item['variants'] as $v ) {
		$kinds[] = $v['type'] ?? 'pending';
		if ( dvs_variant_has_price( $v ) ) {
			$prices[] = (float) $v['price'];
		}
	}
	if ( $prices ) {
		$min  = min( $prices );
		$from = count( array_unique( $prices ) ) > 1 || count( $prices ) < count( $item['variants'] );
		return array(
			'text'   => $from ? dvs_t( 'price_from', dvs_money( $min ) ) : dvs_money( $min ),
			'amount' => $min,
			'from'   => $from,
			'kind'   => 'fixed',
		);
	}
	$on_request = $kinds && ! array_diff( $kinds, array( 'on_request' ) );
	return array(
		'text'   => $on_request ? dvs_t( 'price_on_request' ) : dvs_t( 'price_consult' ),
		'amount' => null,
		'from'   => false,
		'kind'   => $on_request ? 'on_request' : 'pending',
	);
}

/** Algum preço apresentado ainda não foi validado comercialmente? (mostra aviso discreto) */
function dvs_prices_need_notice( ?array $items = null ): bool {
	if ( 'published' !== dvs_price_mode() ) {
		return false;
	}
	foreach ( $items ?? dvs_catalog() as $i ) {
		if ( 'validated' !== $i['status'] && dvs_item_price( $i )['amount'] !== null ) {
			return true;
		}
	}
	return false;
}

/* ---------------------------------------------------------------------------------------------
 * Durações e factos
 * ------------------------------------------------------------------------------------------- */

function dvs_format_duration( ?array $d ): string {
	if ( ! $d || empty( $d['unit'] ) ) {
		return '';
	}
	switch ( $d['unit'] ) {
		case 'half_day':
			return dvs_t( 'dur_half_day' );
		case 'day':
			return dvs_t( 'dur_day' );
		case 'days':
			return dvs_t( 'dur_days', (int) ( $d['qty'] ?? 2 ) );
		case 'morning':
			return dvs_t( 'dur_morning' );
		case 'half_morning':
			return dvs_t( 'dur_half_morning' );
	}
	return '';
}

/** Duração apresentável do item (do item ou, se todas as variantes concordarem, das variantes). */
function dvs_item_duration( array $item ): string {
	if ( ! empty( $item['duration'] ) ) {
		return dvs_format_duration( $item['duration'] );
	}
	return '';
}

/**
 * Factos confirmados (só o que tem suporte na fonte). Cada facto: [rótulo, valor].
 */
function dvs_item_facts( array $item ): array {
	$facts = array();
	$dur   = dvs_item_duration( $item );
	if ( $dur ) {
		$facts['duration'] = array( dvs_t( 'fact_duration' ), $dur );
	}
	if ( $item['min_age'] ) {
		$facts['min_age'] = array( dvs_t( 'fact_min_age' ), dvs_t( 'age_from', (int) $item['min_age'] ) );
	}
	if ( 'yes' === $item['certification'] ) {
		$facts['certification'] = array( dvs_t( 'fact_certification' ), dvs_t( 'cert_yes' ) );
	} elseif ( 'no' === $item['certification'] && 'certified' !== $item['category'] ) {
		$facts['certification'] = array( dvs_t( 'fact_certification' ), dvs_t( 'cert_no' ) );
	}
	if ( $item['package'] ) {
		$facts['equipment'] = array( dvs_t( 'fact_equipment' ), dvs_t( 'equip_variant' ) );
	}
	if ( $item['junior'] ) {
		$facts['junior'] = array( dvs_t( 'fact_junior' ), dvs_t( 'junior_available' ) );
	}
	$price            = dvs_item_price( $item );
	$facts['price']   = array( dvs_t( 'fact_price' ), $price['text'] );
	return $facts;
}

/* ---------------------------------------------------------------------------------------------
 * Ligação item ↔ páginas de oferta traduzidas
 * ------------------------------------------------------------------------------------------- */

function dvs_offer_id( string $item_key, ?string $lang = null ): int {
	return dvs_group_post( 'offer:' . $item_key, $lang );
}

function dvs_offer_item( int $offer_id ): ?array {
	$key = (string) get_post_meta( $offer_id, '_dvs_item', true );
	return $key ? dvs_item( $key ) : null;
}

/** Nome do item no idioma atual (título da página da oferta; recorre ao nome interno). */
function dvs_item_title( array $item, ?string $lang = null ): string {
	$id = dvs_offer_id( $item['key'], $lang );
	return $id ? get_the_title( $id ) : $item['name'];
}

function dvs_item_url( array $item, ?string $lang = null ): string {
	$id = dvs_offer_id( $item['key'], $lang );
	return $id ? get_permalink( $id ) : dvs_url( dvs_categories()[ $item['category'] ]['page'] ?? 'page:courses', $lang );
}

/** Página-mãe da oferta (página da categoria, no idioma da oferta). */
function dvs_offer_parent_page( int $offer_id ): int {
	$key  = (string) get_post_meta( $offer_id, '_dvs_item', true );
	$lang = dvs_post_lang( $offer_id ) ?: dvs_default_lang();
	$cat  = (string) get_post_meta( $offer_id, '_dvs_category', true );
	if ( ! $cat && $key ) {
		$item = dvs_item( $key );
		$cat  = $item['category'] ?? 'courses';
	}
	$page = dvs_categories()[ $cat ]['page'] ?? 'page:courses';
	return dvs_group_post( $page, $lang );
}

/** URL do pedido de disponibilidade com item/variante pré-selecionados. */
function dvs_request_url( ?array $item = null, string $variant = '', ?string $lang = null ): string {
	$args = array();
	if ( $item ) {
		$args['offer'] = $item['key'];
		if ( $variant ) {
			$args['variant'] = $variant;
		}
	}
	return dvs_url( 'page:contact', $lang, $args ) . '#request';
}
