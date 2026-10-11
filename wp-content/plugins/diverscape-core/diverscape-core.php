<?php
/**
 * Plugin Name:       DiversCape Core
 * Description:       Conteúdo de negócio da DiversCape: catálogo central (cursos, experiências, variantes e preços), quatro idiomas, SEO multilingue, pedido de disponibilidade, galeria, FAQ, blocos dinâmicos e importador WP-CLI. Separado da apresentação (tema).
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            Pixart
 * Text Domain:       diverscape-core
 * License:           GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

define( 'DVS_CORE_VERSION', '1.0.0' );
define( 'DVS_CORE_FILE', __FILE__ );
define( 'DVS_CORE_DIR', __DIR__ );
define( 'DVS_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once DVS_CORE_DIR . '/inc/i18n.php';
require_once DVS_CORE_DIR . '/inc/strings.php';
require_once DVS_CORE_DIR . '/inc/business.php';
require_once DVS_CORE_DIR . '/inc/catalog.php';
require_once DVS_CORE_DIR . '/inc/catalog-admin.php';
require_once DVS_CORE_DIR . '/inc/offers.php';
require_once DVS_CORE_DIR . '/inc/content-types.php';
require_once DVS_CORE_DIR . '/inc/media.php';
require_once DVS_CORE_DIR . '/inc/seo.php';
require_once DVS_CORE_DIR . '/inc/forms.php';
require_once DVS_CORE_DIR . '/inc/blocks.php';

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once DVS_CORE_DIR . '/inc/cli.php';
}

register_activation_hook(
	__FILE__,
	static function () {
		dvs_register_content_types();
		flush_rewrite_rules();
	}
);
