<?php
/**
 * Menus por idioma. Itens: [rótulo, ligação dvs:, [filhos]]. Criados como menus normais do WordPress
 * (Aspeto → Menus), um por idioma e localização, editáveis pelo cliente.
 */

defined( 'ABSPATH' ) || exit;

$T = static fn( $en, $pt, $de, $es ) => compact( 'en', 'pt', 'de', 'es' );

return array(
	'primary' => array(
		array( $T( 'Experiences', 'Experiências', 'Erlebnisse', 'Experiencias' ), 'dvs:experiences' ),
		array( $T( 'Courses', 'Cursos', 'Kurse', 'Cursos' ), 'dvs:courses', array(
			array( $T( 'All courses and experiences', 'Todos os cursos e experiências', 'Alle Kurse und Erlebnisse', 'Todos los cursos y experiencias' ), 'dvs:courses' ),
			array( $T( 'Dive courses', 'Cursos de mergulho', 'Tauchkurse', 'Cursos de buceo' ), 'dvs:courses?category=courses#catalog' ),
			array( $T( 'Specialty courses', 'Especialidades', 'Spezialkurse', 'Especialidades' ), 'dvs:courses?category=specialties#catalog' ),
			array( $T( 'First aid', 'Primeiros socorros', 'Erste Hilfe', 'Primeros auxilios' ), 'dvs:courses?category=first-aid#catalog' ),
			array( $T( 'Technical diving', 'Mergulho técnico', 'Technisches Tauchen', 'Buceo técnico' ), 'dvs:technical-diving' ),
			array( $T( 'Prices', 'Preços', 'Preise', 'Precios' ), 'dvs:prices' ),
		) ),
		array( $T( 'Certified divers', 'Certificados', 'Brevetierte Taucher', 'Certificados' ), 'dvs:certified-divers', array(
			array( $T( 'Boat dives', 'Saídas de barco', 'Bootstauchgänge', 'Salidas en barco' ), 'dvs:certified-divers#packages' ),
			array( $T( 'Equipment servicing and rental', 'Manutenção e aluguer de equipamento', 'Ausrüstungsservice und Verleih', 'Mantenimiento y alquiler de equipo' ), 'dvs:equipment' ),
			array( $T( 'Scuba Review and ReActivate', 'Refrescamento e ReActivate', 'Scuba Review und ReActivate', 'Repaso y ReActivate' ), 'dvs:courses?category=certified#catalog' ),
		) ),
		array( $T( 'Discover Sagres', 'Mergulhar em Sagres', 'Sagres entdecken', 'Bucear en Sagres' ), 'dvs:discover-sagres' ),
		array( $T( 'About', 'Sobre nós', 'Über uns', 'Sobre nosotros' ), 'dvs:about', array(
			array( $T( 'About DiversCape', 'Sobre a DiversCape', 'Über DiversCape', 'Sobre DiversCape' ), 'dvs:about' ),
			array( $T( 'Gallery and reviews', 'Galeria e avaliações', 'Galerie und Bewertungen', 'Galería y opiniones' ), 'dvs:gallery' ),
			array( $T( 'FAQ', 'Perguntas frequentes', 'Häufige Fragen', 'Preguntas frecuentes' ), 'dvs:faq' ),
		) ),
		array( $T( 'Contact', 'Contactos', 'Kontakt', 'Contacto' ), 'dvs:contact' ),
	),
	'footer'  => array(
		array( $T( 'Experiences', 'Experiências', 'Erlebnisse', 'Experiencias' ), 'dvs:experiences' ),
		array( $T( 'Courses', 'Cursos', 'Kurse', 'Cursos' ), 'dvs:courses' ),
		array( $T( 'Technical diving', 'Mergulho técnico', 'Technisches Tauchen', 'Buceo técnico' ), 'dvs:technical-diving' ),
		array( $T( 'Certified divers', 'Mergulhadores certificados', 'Brevetierte Taucher', 'Buceadores certificados' ), 'dvs:certified-divers' ),
		array( $T( 'Prices', 'Preços', 'Preise', 'Precios' ), 'dvs:prices' ),
		array( $T( 'Equipment', 'Equipamento', 'Ausrüstung', 'Equipo' ), 'dvs:equipment' ),
		array( $T( 'Discover Sagres', 'Mergulhar em Sagres', 'Sagres entdecken', 'Bucear en Sagres' ), 'dvs:discover-sagres' ),
		array( $T( 'About us', 'Sobre nós', 'Über uns', 'Sobre nosotros' ), 'dvs:about' ),
		array( $T( 'Gallery and reviews', 'Galeria e avaliações', 'Galerie und Bewertungen', 'Galería y opiniones' ), 'dvs:gallery' ),
		array( $T( 'FAQ', 'Perguntas frequentes', 'Häufige Fragen', 'Preguntas frecuentes' ), 'dvs:faq' ),
	),
	'legal'   => array(
		array( $T( 'Privacy', 'Privacidade', 'Datenschutz', 'Privacidad' ), 'dvs:privacy' ),
		array( $T( 'Cookies', 'Cookies', 'Cookies', 'Cookies' ), 'dvs:cookies' ),
		array( $T( 'Request conditions', 'Condições do pedido', 'Anfragebedingungen', 'Condiciones de la solicitud' ), 'dvs:request-terms' ),
	),
);
