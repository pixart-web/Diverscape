<?php
/**
 * Páginas do site nos quatro idiomas. A estrutura de blocos é comum; os textos são por idioma.
 * $T(en, pt, de, es) marca um valor traduzido. Ligações internas usam o esquema dvs: (ver blocks.php).
 *
 * Âncoras históricas da página única original mantidas na homepage de cada idioma:
 *   #frontpa (apresentação), #Gallery (galeria e avaliações), #coursesprices (cursos e preços), #location (localização e contactos).
 */

defined( 'ABSPATH' ) || exit;

$T = static fn( $en, $pt, $de, $es ) => array( '__i18n' => true, 'en' => $en, 'pt' => $pt, 'de' => $de, 'es' => $es );

/* Blocos reutilizados */
$invite = array(
	'diverscape/invite',
	array(
		'anchor'    => 'location',
		'title'     => $T( 'Your next dive starts here.', 'O próximo mergulho começa aqui.', 'Ihr nächster Tauchgang beginnt hier.', 'Su próxima inmersión empieza aquí.' ),
		'text'      => $T( 'Tell us what you would like to do and when. We check availability and get back to you — nothing is booked until we confirm.', 'Diga-nos o que gostaria de fazer e quando. Verificamos a disponibilidade e respondemos — nada fica reservado até confirmarmos.', 'Sagen Sie uns, was Sie machen möchten und wann. Wir prüfen die Verfügbarkeit und melden uns — gebucht ist erst nach unserer Bestätigung.', 'Díganos qué le gustaría hacer y cuándo. Comprobamos la disponibilidad y le respondemos — nada queda reservado hasta que lo confirmemos.' ),
		'image'     => 'sea-fan-reef.jpg',
		'btn1Label' => $T( 'Plan your dive', 'Planeie o seu mergulho', 'Tauchgang planen', 'Planifique su inmersión' ),
		'btn1Url'   => 'dvs:request',
	),
);
$plain_hero = static fn( $eyebrow, $title, $lead ) => array( 'diverscape/hero', array( 'variant' => 'page', 'eyebrow' => $eyebrow, 'title' => $title, 'lead' => $lead, 'image' => '' ) );
$draft_note = $T(
	'Draft prepared for the website proposal. To be reviewed and approved by DiversCape before launch.',
	'Rascunho preparado para a proposta do site. A rever e aprovar pela DiversCape antes do lançamento.',
	'Entwurf für den Website-Vorschlag. Vor dem Start von DiversCape zu prüfen und freizugeben.',
	'Borrador preparado para la propuesta del sitio. Debe ser revisado y aprobado por DiversCape antes del lanzamiento.'
);

return array(

	/* ======================================================================== Homepage */
	'home' => array(
		'order'     => 0,
		'title'     => $T( 'Diving in Sagres', 'Mergulho em Sagres', 'Tauchen in Sagres', 'Buceo en Sagres' ),
		'slug'      => $T( 'home', 'pt', 'de', 'sp' ),
		'seo_title' => $T( 'DiversCape · Diving courses and boat dives in Sagres, Portugal', 'DiversCape · Cursos de mergulho e saídas de barco em Sagres', 'DiversCape · Tauchkurse und Bootstauchgänge in Sagres, Portugal', 'DiversCape · Cursos de buceo y salidas en barco en Sagres, Portugal' ),
		'seo_desc'  => $T( 'Dive centre in Sagres, Algarve: PADI experiences and courses, specialties, first aid, technical diving and boat dives for certified divers.', 'Centro de mergulho em Sagres, Algarve: experiências e cursos PADI, especialidades, primeiros socorros, mergulho técnico e saídas de barco.', 'Tauchzentrum in Sagres, Algarve: PADI-Schnuppertauchen und -Kurse, Spezialkurse, Erste Hilfe, technisches Tauchen und Bootstauchgänge.', 'Centro de buceo en Sagres, Algarve: experiencias y cursos PADI, especialidades, primeros auxilios, buceo técnico y salidas en barco.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'anchor'    => 'frontpa',
				'variant'   => 'home',
				'eyebrow'   => $T( 'Diving in Sagres · Algarve, Portugal', 'Mergulho em Sagres · Algarve, Portugal', 'Tauchen in Sagres · Algarve, Portugal', 'Buceo en Sagres · Algarve, Portugal' ),
				'title'     => $T( 'Discover Sagres. Beneath the surface.', 'Descubra Sagres. Debaixo de água.', 'Entdecken Sie Sagres. Unter der Oberfläche.', 'Descubra Sagres. Bajo la superficie.' ),
				'lead'      => $T( 'Find your next diving experience on Portugal’s Atlantic coast.', 'Encontre a sua próxima experiência de mergulho na costa atlântica portuguesa.', 'Finden Sie Ihr nächstes Taucherlebnis an Portugals Atlantikküste.', 'Encuentre su próxima experiencia de buceo en la costa atlántica de Portugal.' ),
				'image'     => 'diver-anemone.jpg',
				'focus'     => '62% 40%',
				'btn1Label' => $T( 'Find your experience', 'Escolha a sua experiência', 'Ihr Taucherlebnis finden', 'Elija su experiencia' ),
				'btn1Url'   => '#choose',
				'btn2Label' => $T( 'Explore courses', 'Conheça os cursos', 'Kurse entdecken', 'Ver los cursos' ),
				'btn2Url'   => 'dvs:courses',
			) ),
			array( 'diverscape/doors', array(
				'anchor'  => 'choose',
				'eyebrow' => $T( 'Start here', 'Comece aqui', 'Hier starten', 'Empiece aquí' ),
				'title'   => $T( 'Where would you like to begin?', 'Por onde quer começar?', 'Wo möchten Sie anfangen?', '¿Por dónde quiere empezar?' ),
				'd1Title' => $T( 'Try diving for the first time', 'Experimentar pela primeira vez', 'Zum ersten Mal tauchen', 'Probar por primera vez' ),
				'd1Text'  => $T( 'Half a day with an instructor: learn the basics and make your first dive in the sea. Children from 8 can try Bubblemaker.', 'Meio dia com um instrutor: aprenda o essencial e faça o primeiro mergulho no mar. Crianças a partir dos 8 anos podem experimentar o Bubblemaker.', 'Ein halber Tag mit Instruktor: Grundlagen lernen und erster Tauchgang im Meer. Kinder ab 8 Jahren können Bubblemaker ausprobieren.', 'Medio día con un instructor: aprenda lo básico y haga su primera inmersión en el mar. Los niños a partir de 8 años pueden probar Bubblemaker.' ),
				'd1Url'   => 'dvs:experiences',
				'd1Image' => 'group-surface.jpg',
				'd1Cta'   => $T( 'Experiences', 'Experiências', 'Erlebnisse', 'Experiencias' ),
				'd2Title' => $T( 'Learn and progress', 'Aprender e evoluir', 'Lernen und weiterkommen', 'Aprender y progresar' ),
				'd2Text'  => $T( 'From your first certification to Rescue Diver — plus twelve specialties and first aid courses.', 'Da primeira certificação ao Rescue Diver — e ainda doze especialidades e cursos de primeiros socorros.', 'Vom ersten Brevet bis zum Rescue Diver — dazu zwölf Spezialkurse und Erste-Hilfe-Kurse.', 'De la primera certificación al Rescue Diver — además de doce especialidades y cursos de primeros auxilios.' ),
				'd2Url'   => 'dvs:courses',
				'd2Image' => 'hero-shore-entry.jpg',
				'd2Cta'   => $T( 'Courses', 'Cursos', 'Kurse', 'Cursos' ),
				'd3Title' => $T( 'Dive as a certified diver', 'Mergulhar com certificação', 'Als brevetierter Taucher tauchen', 'Bucear con certificación' ),
				'd3Text'  => $T( 'Boat dives in packages of 1 to 10, with your own equipment or with rental equipment included.', 'Saídas de barco em pacotes de 1 a 10 mergulhos, com equipamento próprio ou de aluguer incluído.', 'Bootstauchgänge im Paket von 1 bis 10, mit eigener Ausrüstung oder inklusive Leihausrüstung.', 'Salidas en barco en paquetes de 1 a 10 inmersiones, con equipo propio o de alquiler incluido.' ),
				'd3Url'   => 'dvs:certified-divers',
				'd3Image' => 'group-boat-cliffs.jpg',
				'd3Cta'   => $T( 'Certified divers', 'Mergulhadores certificados', 'Brevetierte Taucher', 'Buceadores certificados' ),
			) ),
			array( 'diverscape/offers', array(
				'eyebrow'   => $T( 'Featured', 'Em destaque', 'Ausgewählt', 'Destacados' ),
				'title'     => $T( 'Good places to start', 'Bons pontos de partida', 'Gute Startpunkte', 'Buenos puntos de partida' ),
				'source'    => 'featured',
				'count'     => 4,
				'linkLabel' => $T( 'All courses and experiences', 'Todos os cursos e experiências', 'Alle Kurse und Erlebnisse', 'Todos los cursos y experiencias' ),
				'linkUrl'   => 'dvs:courses',
			) ),
			array( 'diverscape/surface', array(
				'eyebrow'    => $T( 'Sagres above and below the water', 'Sagres acima e abaixo de água', 'Sagres über und unter Wasser', 'Sagres sobre y bajo el agua' ),
				'title'      => $T( 'At the south-western tip of Europe', 'No extremo sudoeste da Europa', 'Am südwestlichsten Punkt Europas', 'En el extremo suroeste de Europa' ),
				'text'       => $T( 'Sagres is a place where nature comes first: some of Portugal’s most beautiful beaches, a natural harbour shaped by the coastline, and the open Atlantic all around.', 'Sagres é um lugar onde a natureza vem primeiro: algumas das mais belas praias de Portugal, um porto natural moldado pela costa e o Atlântico à volta.', 'Sagres ist ein Ort, an dem die Natur an erster Stelle steht: einige der schönsten Strände Portugals, ein von der Küste geformter Naturhafen und rundherum der offene Atlantik.', 'Sagres es un lugar donde la naturaleza es lo primero: algunas de las playas más bonitas de Portugal, un puerto natural formado por la costa y el Atlántico alrededor.' ),
				'above'      => 'sagres-islets-panorama.jpg',
				'below'      => 'wreck-diver.jpg',
				'belowTitle' => $T( 'Below the surface', 'Abaixo da superfície', 'Unter der Oberfläche', 'Bajo la superficie' ),
				'belowText'  => $T( 'Our diving takes place in the Natural Park of the South-West Alentejo and Vicentine Coast, at sites that include caves, canyons and passages — and wrecks to explore.', 'Os nossos mergulhos decorrem no Parque Natural do Sudoeste Alentejano e Costa Vicentina, em locais que incluem grutas, desfiladeiros e passagens — e naufrágios para explorar.', 'Wir tauchen im Naturpark Südwest-Alentejo und Costa Vicentina, an Plätzen mit Höhlen, Canyons und Durchgängen — und mit Wracks zum Erkunden.', 'Nuestras inmersiones se realizan en el Parque Natural del Suroeste Alentejano y Costa Vicentina, en lugares que incluyen cuevas, cañones y pasos — y pecios por explorar.' ),
				'linkLabel'  => $T( 'Discover diving in Sagres', 'Conheça o mergulho em Sagres', 'Tauchen in Sagres entdecken', 'Descubra el buceo en Sagres' ),
				'linkUrl'    => 'dvs:discover-sagres',
			) ),
			array( 'diverscape/training', array(
				'anchor'  => 'coursesprices',
				'eyebrow' => $T( 'Training', 'Formação', 'Ausbildung', 'Formación' ),
				'title'   => $T( 'Find your level', 'Encontre o seu nível', 'Finden Sie Ihr Level', 'Encuentre su nivel' ),
				'intro'   => $T( 'Five ways in. Each course page explains who it is for, and our team confirms the right next step with you. All prices are in one table on the <a href="dvs:prices">prices page</a>.', 'Cinco portas de entrada. Cada página explica para quem é o curso e a equipa confirma consigo o passo seguinte. Todos os preços estão numa só tabela na <a href="dvs:prices">página de preços</a>.', 'Fünf Einstiege. Jede Kursseite erklärt, für wen der Kurs ist, und unser Team stimmt den nächsten Schritt mit Ihnen ab. Alle Preise finden Sie in einer Tabelle auf der <a href="dvs:prices">Preisseite</a>.', 'Cinco puertas de entrada. Cada página explica para quién es el curso y el equipo confirma con usted el siguiente paso. Todos los precios están en una tabla en la <a href="dvs:prices">página de precios</a>.' ),
			) ),
			array( 'diverscape/trust', array(
				'eyebrow'   => $T( 'The dive centre', 'O centro', 'Das Tauchzentrum', 'El centro' ),
				'title'     => $T( 'A dedicated team at Porto da Baleeira', 'Uma equipa dedicada no Porto da Baleeira', 'Ein engagiertes Team am Porto da Baleeira', 'Un equipo dedicado en el Porto da Baleeira' ),
				'text'      => $T( 'A diverse and dedicated team means a broad range of knowledge and skills — with more than 15 years of combined experience in the diving industry.', 'Uma equipa variada e dedicada significa um leque alargado de conhecimentos e competências — com mais de 15 anos de experiência acumulada na indústria do mergulho.', 'Ein vielseitiges, engagiertes Team bedeutet breites Wissen und viele Fähigkeiten — mit zusammen mehr als 15 Jahren Erfahrung in der Tauchbranche.', 'Un equipo variado y dedicado significa un amplio abanico de conocimientos y habilidades — con más de 15 años de experiencia acumulada en el sector del buceo.' ),
				'points'    => $T( "PADI courses, from first experiences to TecRec technical training\nOur dives are accompanied by a guide or instructor\nEquipment servicing at an affordable price", "Cursos PADI, das primeiras experiências à formação técnica TecRec\nOs nossos mergulhos são acompanhados por um guia ou instrutor\nManutenção de equipamento a um preço acessível", "PADI-Kurse, vom Schnuppertauchen bis zur technischen TecRec-Ausbildung\nUnsere Tauchgänge werden von einem Guide oder Instruktor begleitet\nAusrüstungsservice zu einem günstigen Preis", "Cursos PADI, desde las primeras experiencias hasta la formación técnica TecRec\nNuestras inmersiones van acompañadas por un guía o instructor\nMantenimiento de equipo a un precio accesible" ),
				'image'     => 'team-boat-harbour.jpg',
				'image2'    => 'dive-centre-building.jpg',
				'linkLabel' => $T( 'About the dive centre', 'Sobre o centro', 'Über das Tauchzentrum', 'Sobre el centro' ),
				'linkUrl'   => 'dvs:about',
			) ),
			array( 'diverscape/gallery', array(
				'anchor'    => 'Gallery',
				'eyebrow'   => $T( 'Gallery', 'Galeria', 'Galerie', 'Galería' ),
				'title'     => $T( 'Moments from our dives', 'Momentos dos nossos mergulhos', 'Momente unserer Tauchgänge', 'Momentos de nuestras inmersiones' ),
				'count'     => 8,
				'linkLabel' => $T( 'See the full gallery', 'Ver a galeria completa', 'Zur ganzen Galerie', 'Ver la galería completa' ),
				'linkUrl'   => 'dvs:gallery',
			) ),
			array( 'diverscape/reviews', array(
				'eyebrow' => $T( 'Reviews', 'Avaliações', 'Bewertungen', 'Opiniones' ),
				'title'   => $T( 'What our divers say', 'O que dizem os nossos mergulhadores', 'Was unsere Taucher sagen', 'Lo que dicen nuestros buceadores' ),
				'text'    => $T( 'We take pride in our customer service, and our happy divers tell the story better than we can.', 'Temos orgulho no nosso atendimento, e os nossos mergulhadores contam a história melhor do que nós.', 'Wir sind stolz auf unseren Service — und unsere zufriedenen Taucher erzählen es besser als wir.', 'Estamos orgullosos de nuestra atención, y nuestros buceadores lo cuentan mejor que nosotros.' ),
			) ),
			$invite,
		),
	),

	/* ======================================================================== Experiências */
	'experiences' => array(
		'order'     => 10,
		'title'     => $T( 'Experiences', 'Experiências', 'Erlebnisse', 'Experiencias' ),
		'slug'      => $T( 'experiences', 'experiencias', 'erlebnisse', 'experiencias' ),
		'seo_title' => $T( 'Try scuba diving in Sagres · DiversCape', 'Experimentar o mergulho em Sagres · DiversCape', 'Schnuppertauchen in Sagres · DiversCape', 'Probar el buceo en Sagres · DiversCape' ),
		'seo_desc'  => $T( 'Never dived before? Discover Scuba Diving for adults and Bubblemaker for children from 8, in half a day with an instructor in Sagres.', 'Nunca mergulhou? Discover Scuba Diving para adultos e Bubblemaker para crianças a partir dos 8 anos, em meio dia com instrutor, em Sagres.', 'Noch nie getaucht? Discover Scuba Diving für Erwachsene und Bubblemaker für Kinder ab 8 — ein halber Tag mit Instruktor in Sagres.', '¿Nunca ha buceado? Discover Scuba Diving para adultos y Bubblemaker para niños desde 8 años, en medio día con instructor en Sagres.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow'   => $T( 'Experiences', 'Experiências', 'Erlebnisse', 'Experiencias' ),
				'title'     => $T( 'Try diving', 'Experimente o mergulho', 'Tauchen ausprobieren', 'Pruebe el buceo' ),
				'lead'      => $T( 'Never dived before? Start with half a day in the water with an instructor — for adults, or for children from 8.', 'Nunca mergulhou? Comece com meio dia na água com um instrutor — para adultos ou para crianças a partir dos 8 anos.', 'Noch nie getaucht? Beginnen Sie mit einem halben Tag im Wasser mit Instruktor — für Erwachsene oder Kinder ab 8 Jahren.', '¿Nunca ha buceado? Empiece con medio día en el agua con un instructor — para adultos o para niños a partir de 8 años.' ),
				'image'     => 'group-surface.jpg',
				'focus'     => '50% 60%',
				'btn1Label' => $T( 'Plan your dive', 'Planeie o seu mergulho', 'Tauchgang planen', 'Planifique su inmersión' ),
				'btn1Url'   => 'dvs:request',
			) ),
			array( 'diverscape/offers', array(
				'eyebrow' => $T( 'Two ways to start', 'Duas formas de começar', 'Zwei Wege zum Start', 'Dos formas de empezar' ),
				'title'   => $T( 'Your first breaths underwater', 'As primeiras respirações debaixo de água', 'Die ersten Atemzüge unter Wasser', 'Sus primeras respiraciones bajo el agua' ),
				'source'  => 'category',
				'category' => 'experiences',
				'layout'  => 'rows',
				'count'   => 4,
			) ),
			array( 'diverscape/story', array(
				'tone'      => 'sand',
				'eyebrow'   => $T( 'What next?', 'E depois?', 'Und dann?', '¿Y después?' ),
				'title'     => $T( 'Enjoyed your first dive?', 'Gostou do primeiro mergulho?', 'Hat Ihnen der erste Tauchgang gefallen?', '¿Le gustó su primera inmersión?' ),
				'text'      => $T( "The natural next step is to become a certified diver with the Open Water Diver course — or to start with Scuba Diver if you have less time.\n\nTell us what you enjoyed and how much time you have, and we will suggest the right course.", "O passo seguinte natural é tornar-se mergulhador certificado com o curso Open Water Diver — ou começar pelo Scuba Diver, se tiver menos tempo.\n\nDiga-nos o que mais gostou e quanto tempo tem, e sugerimos o curso certo.", "Der natürliche nächste Schritt ist das Brevet mit dem Open-Water-Diver-Kurs — oder Scuba Diver, wenn Sie weniger Zeit haben.\n\nErzählen Sie uns, was Ihnen gefallen hat und wie viel Zeit Sie haben — wir schlagen den passenden Kurs vor.", "El siguiente paso natural es convertirse en buceador certificado con el curso Open Water Diver — o empezar por Scuba Diver si tiene menos tiempo.\n\nCuéntenos qué le gustó y cuánto tiempo tiene, y le sugeriremos el curso adecuado." ),
				'image'     => 'hero-shore-entry.jpg',
				'linkLabel' => $T( 'Explore dive courses', 'Conheça os cursos de mergulho', 'Tauchkurse entdecken', 'Ver los cursos de buceo' ),
				'linkUrl'   => 'dvs:courses?category=courses#catalog',
			) ),
			array( 'diverscape/faq', array(
				'title' => $T( 'Questions about trying diving', 'Perguntas sobre experimentar o mergulho', 'Fragen zum Schnuppertauchen', 'Preguntas sobre probar el buceo' ),
				'topic' => 'experiences',
				'linkLabel' => $T( 'All questions and answers', 'Todas as perguntas e respostas', 'Alle Fragen und Antworten', 'Todas las preguntas y respuestas' ),
				'linkUrl' => 'dvs:faq',
			) ),
			$invite,
		),
	),

	/* ======================================================================== Catálogo de cursos */
	'courses' => array(
		'order'     => 20,
		'title'     => $T( 'Courses', 'Cursos', 'Kurse', 'Cursos' ),
		'slug'      => $T( 'courses', 'cursos', 'kurse', 'cursos' ),
		'seo_title' => $T( 'PADI diving courses in Sagres · DiversCape', 'Cursos de mergulho PADI em Sagres · DiversCape', 'PADI-Tauchkurse in Sagres · DiversCape', 'Cursos de buceo PADI en Sagres · DiversCape' ),
		'seo_desc'  => $T( 'Search every course and experience: Open Water, Advanced, Rescue, twelve specialties, Emergency First Response and PADI TecRec technical diving.', 'Pesquise todos os cursos e experiências: Open Water, Advanced, Rescue, doze especialidades, Emergency First Response e mergulho técnico PADI TecRec.', 'Alle Kurse und Erlebnisse durchsuchen: Open Water, Advanced, Rescue, zwölf Spezialkurse, Emergency First Response und technisches Tauchen mit PADI TecRec.', 'Busque todos los cursos y experiencias: Open Water, Advanced, Rescue, doce especialidades, Emergency First Response y buceo técnico PADI TecRec.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Courses and experiences', 'Cursos e experiências', 'Kurse und Erlebnisse', 'Cursos y experiencias' ),
				'title'   => $T( 'Find the right course', 'Encontre o curso certo', 'Finden Sie den richtigen Kurs', 'Encuentre el curso adecuado' ),
				'lead'    => $T( 'The full catalogue in one place — from first experiences to technical diving. Search by name, or filter by category and experience.', 'Todo o catálogo num só lugar — das primeiras experiências ao mergulho técnico. Pesquise pelo nome ou filtre por categoria e experiência.', 'Der ganze Katalog an einem Ort — vom Schnuppertauchen bis zum technischen Tauchen. Suchen Sie nach Namen oder filtern Sie nach Kategorie und Erfahrung.', 'Todo el catálogo en un solo lugar — desde las primeras experiencias hasta el buceo técnico. Busque por nombre o filtre por categoría y experiencia.' ),
				'image'   => 'group-boat-cliffs.jpg',
				'focus'   => '50% 45%',
			) ),
			array( 'diverscape/catalog', array() ),
			array( 'diverscape/story', array(
				'tone'      => 'sand',
				'eyebrow'   => $T( 'Not sure?', 'Na dúvida?', 'Unsicher?', '¿Dudas?' ),
				'title'     => $T( 'We will help you choose', 'Ajudamos a escolher', 'Wir helfen bei der Wahl', 'Le ayudamos a elegir' ),
				'text'      => $T( "Tell us about your diving experience, your dates and what you would like to do. Our team will recommend the right course and confirm the requirements with you.", "Fale-nos da sua experiência de mergulho, das suas datas e do que gostaria de fazer. A equipa recomenda o curso certo e confirma consigo os requisitos.", "Erzählen Sie uns von Ihrer Taucherfahrung, Ihren Terminen und Ihren Wünschen. Unser Team empfiehlt den passenden Kurs und klärt die Voraussetzungen mit Ihnen.", "Cuéntenos su experiencia de buceo, sus fechas y qué le gustaría hacer. El equipo le recomendará el curso adecuado y confirmará con usted los requisitos." ),
				'image'     => 'team-boat-harbour.jpg',
				'reverse'   => true,
				'linkLabel' => $T( 'Ask us — “not sure yet” is fine', 'Pergunte-nos — «ainda não sei» também serve', 'Fragen Sie uns — „noch unsicher“ ist in Ordnung', 'Pregúntenos — «aún no lo sé» también vale' ),
				'linkUrl'   => 'dvs:request',
			) ),
			$invite,
		),
	),

	/* ======================================================================== Mergulhadores certificados */
	'certified-divers' => array(
		'order'     => 30,
		'title'     => $T( 'Certified divers', 'Mergulhadores certificados', 'Brevetierte Taucher', 'Buceadores certificados' ),
		'slug'      => $T( 'certified-divers', 'mergulhadores-certificados', 'brevetierte-taucher', 'buceadores-certificados' ),
		'seo_title' => $T( 'Boat dives in Sagres for certified divers · DiversCape', 'Saídas de barco em Sagres para mergulhadores certificados · DiversCape', 'Bootstauchgänge in Sagres für brevetierte Taucher · DiversCape', 'Salidas en barco en Sagres para buceadores certificados · DiversCape' ),
		'seo_desc'  => $T( 'Boat dive packages of 1, 2, 6 or 10 dives, with or without rental equipment, plus equipment rental, Scuba Review and ReActivate.', 'Pacotes de 1, 2, 6 ou 10 mergulhos de barco, com ou sem equipamento de aluguer, aluguer de equipamento, refrescamento e ReActivate.', 'Bootstauchpakete mit 1, 2, 6 oder 10 Tauchgängen, mit oder ohne Leihausrüstung, dazu Ausrüstungsverleih, Scuba Review und ReActivate.', 'Paquetes de 1, 2, 6 o 10 inmersiones en barco, con o sin equipo de alquiler, además de alquiler de equipo, repaso y ReActivate.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow'   => $T( 'Certified divers', 'Mergulhadores certificados', 'Brevetierte Taucher', 'Buceadores certificados' ),
				'title'     => $T( 'Dive Sagres with us', 'Mergulhe connosco em Sagres', 'Tauchen Sie mit uns in Sagres', 'Bucee con nosotros en Sagres' ),
				'lead'      => $T( 'Boat dives in packages of 1 to 10, equipment rental, and a refresher if it has been a while.', 'Saídas de barco em pacotes de 1 a 10 mergulhos, aluguer de equipamento e um refrescamento se já passou algum tempo.', 'Bootstauchgänge im Paket von 1 bis 10, Ausrüstungsverleih und eine Auffrischung, falls es eine Weile her ist.', 'Salidas en barco en paquetes de 1 a 10 inmersiones, alquiler de equipo y un repaso si hace tiempo que no bucea.' ),
				'image'     => 'group-boat-cliffs.jpg',
				'focus'     => '50% 35%',
				'btn1Label' => $T( 'Choose a package', 'Escolher um pacote', 'Paket wählen', 'Elegir un paquete' ),
				'btn1Url'   => '#packages',
			) ),
			array( 'diverscape/packages', array(
				'eyebrow' => $T( 'Boat dives', 'Saídas de barco', 'Bootstauchgänge', 'Salidas en barco' ),
				'title'   => $T( 'Build your package', 'Monte o seu pacote', 'Stellen Sie Ihr Paket zusammen', 'Arme su paquete' ),
				'intro'   => $T( "Choose the number of boat dives and whether you bring your own equipment. Our dives are accompanied by a guide or instructor.", "Escolha o número de mergulhos de barco e se traz o seu equipamento. Os nossos mergulhos são acompanhados por um guia ou instrutor.", "Wählen Sie die Anzahl der Bootstauchgänge und ob Sie eigene Ausrüstung mitbringen. Unsere Tauchgänge werden von einem Guide oder Instruktor begleitet.", "Elija el número de inmersiones en barco y si trae su propio equipo. Nuestras inmersiones van acompañadas por un guía o instructor." ),
			) ),
			array( 'diverscape/offers', array(
				'eyebrow'  => $T( 'Before and around your dives', 'Antes e à volta dos mergulhos', 'Rund um Ihre Tauchgänge', 'Antes y alrededor de sus inmersiones' ),
				'title'    => $T( 'Rental and refreshers', 'Aluguer e refrescamento', 'Verleih und Auffrischung', 'Alquiler y repaso' ),
				'source'   => 'category',
				'category' => 'certified',
				'exclude'  => 'boat-dives',
				'count'    => 6,
			) ),
			array( 'diverscape/story', array(
				'tone'      => 'deep',
				'eyebrow'   => $T( 'Keep learning', 'Continue a aprender', 'Weiterlernen', 'Siga aprendiendo' ),
				'title'     => $T( 'Specialties while you are here', 'Especialidades durante a estadia', 'Spezialkurse während Ihres Aufenthalts', 'Especialidades durante su estancia' ),
				'text'      => $T( 'Nitrox, Deep, Wreck, Night, Peak Performance Buoyancy and more: specialty courses fit naturally between your boat dives.', 'Nitrox, Profundo, Naufrágios, Noturno, Flutuabilidade Perfeita e mais: as especialidades encaixam naturalmente entre as saídas de barco.', 'Nitrox, Deep, Wrack, Nacht, Peak Performance Buoyancy und mehr: Spezialkurse passen gut zwischen Ihre Bootstauchgänge.', 'Nitrox, Profundo, Pecios, Nocturno, Flotabilidad perfecta y más: las especialidades encajan de forma natural entre sus salidas en barco.' ),
				'image'     => 'wreck-diver.jpg',
				'linkLabel' => $T( 'See specialty courses', 'Ver cursos de especialidade', 'Spezialkurse ansehen', 'Ver cursos de especialidad' ),
				'linkUrl'   => 'dvs:courses?category=specialties#catalog',
			) ),
			array( 'diverscape/faq', array(
				'title' => $T( 'Questions from certified divers', 'Perguntas de mergulhadores certificados', 'Fragen brevetierter Taucher', 'Preguntas de buceadores certificados' ),
				'topic' => 'certified',
			) ),
			$invite,
		),
	),

	/* ======================================================================== Mergulho técnico */
	'technical-diving' => array(
		'order'     => 40,
		'title'     => $T( 'Technical diving', 'Mergulho técnico', 'Technisches Tauchen', 'Buceo técnico' ),
		'slug'      => $T( 'technical-diving', 'mergulho-tecnico', 'technisches-tauchen', 'buceo-tecnico' ),
		'seo_title' => $T( 'PADI TecRec technical diving courses in Sagres · DiversCape', 'Cursos de mergulho técnico PADI TecRec em Sagres · DiversCape', 'Technische Tauchkurse PADI TecRec in Sagres · DiversCape', 'Cursos de buceo técnico PADI TecRec en Sagres · DiversCape' ),
		'seo_desc'  => $T( 'Discover Tec, Tec 40 to Tec 50, Trimix, Tec Sidemount and Gas Blender courses. Each technical course is planned individually — ask us for information.', 'Discover Tec, Tec 40 a Tec 50, Trimix, Tec Sidemount e Gas Blender. Cada curso técnico é planeado individualmente — peça-nos informações.', 'Discover Tec, Tec 40 bis Tec 50, Trimix, Tec Sidemount und Gas Blender. Jeder technische Kurs wird individuell geplant — fragen Sie uns.', 'Discover Tec, Tec 40 a Tec 50, Trimix, Tec Sidemount y Gas Blender. Cada curso técnico se planifica individualmente — pídanos información.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow'   => $T( 'Technical diving', 'Mergulho técnico', 'Technisches Tauchen', 'Buceo técnico' ),
				'title'     => $T( 'PADI TecRec in Sagres', 'PADI TecRec em Sagres', 'PADI TecRec in Sagres', 'PADI TecRec en Sagres' ),
				'lead'      => $T( 'From Discover Tec to Tec Trimix. Every technical course is planned individually — ask us for information and prices.', 'Do Discover Tec ao Tec Trimix. Cada curso técnico é planeado individualmente — peça-nos informações e preços.', 'Von Discover Tec bis Tec Trimix. Jeder technische Kurs wird individuell geplant — fragen Sie uns nach Informationen und Preisen.', 'De Discover Tec a Tec Trimix. Cada curso técnico se planifica individualmente — pídanos información y precios.' ),
				'image'     => 'wreck-diver.jpg',
				'focus'     => '45% 50%',
				'btn1Label' => $T( 'Ask for information', 'Pedir informações', 'Informationen anfragen', 'Pedir información' ),
				'btn1Url'   => 'dvs:request',
			) ),
			array( 'diverscape/story', array(
				'tone'    => 'deep',
				'eyebrow' => $T( 'How it works', 'Como funciona', 'So funktioniert es', 'Cómo funciona' ),
				'title'   => $T( 'Planned with you, one diver at a time', 'Planeado consigo, mergulhador a mergulhador', 'Mit Ihnen geplant, Taucher für Taucher', 'Planificado con usted, buceador a buceador' ),
				'text'    => $T( "Technical diving calls for the right experience, equipment and preparation. Before any course, our team discusses your experience, your goals and the course requirements with you.\n\nTechnical courses are priced on request. Send us an information request and we will reply with the details.", "O mergulho técnico exige a experiência, o equipamento e a preparação certos. Antes de qualquer curso, a equipa analisa consigo a sua experiência, os seus objetivos e os requisitos do curso.\n\nOs cursos técnicos têm preço sob consulta. Envie-nos um pedido de informações e respondemos com os pormenores.", "Technisches Tauchen erfordert die richtige Erfahrung, Ausrüstung und Vorbereitung. Vor jedem Kurs bespricht unser Team mit Ihnen Ihre Erfahrung, Ihre Ziele und die Kursvoraussetzungen.\n\nTechnische Kurse gibt es auf Anfrage. Senden Sie uns eine Anfrage, wir antworten mit den Einzelheiten.", "El buceo técnico exige la experiencia, el equipo y la preparación adecuados. Antes de cualquier curso, el equipo analiza con usted su experiencia, sus objetivos y los requisitos del curso.\n\nLos cursos técnicos tienen precio bajo consulta. Envíenos una solicitud de información y le responderemos con los detalles." ),
				'image'   => 'cave-diver-light.jpg',
				'points'  => $T( "Courses from the PADI TecRec programme\nRequirements confirmed individually\nPrices on request", "Cursos do programa PADI TecRec\nRequisitos confirmados individualmente\nPreços sob consulta", "Kurse aus dem PADI-TecRec-Programm\nVoraussetzungen werden individuell geklärt\nPreise auf Anfrage", "Cursos del programa PADI TecRec\nRequisitos confirmados individualmente\nPrecios bajo consulta" ),
			) ),
			array( 'diverscape/offers', array(
				'eyebrow'  => $T( 'Courses', 'Cursos', 'Kurse', 'Cursos' ),
				'title'    => $T( 'Technical courses', 'Cursos técnicos', 'Technische Kurse', 'Cursos técnicos' ),
				'source'   => 'category',
				'category' => 'technical',
				'count'    => 12,
			) ),
			array( 'diverscape/faq', array(
				'title' => $T( 'Questions about technical diving', 'Perguntas sobre mergulho técnico', 'Fragen zum technischen Tauchen', 'Preguntas sobre buceo técnico' ),
				'topic' => 'technical',
			) ),
			$invite,
		),
	),

	/* ======================================================================== Preços */
	'prices' => array(
		'order'     => 50,
		'title'     => $T( 'Prices', 'Preços', 'Preise', 'Precios' ),
		'slug'      => $T( 'prices', 'precos', 'preise', 'precios' ),
		'seo_title' => $T( 'Prices: diving courses, experiences and boat dives · DiversCape', 'Preços: cursos, experiências e saídas de mergulho · DiversCape', 'Preise: Tauchkurse, Erlebnisse und Bootstauchgänge · DiversCape', 'Precios: cursos, experiencias y salidas de buceo · DiversCape' ),
		'seo_desc'  => $T( 'All DiversCape prices in one table: experiences, PADI courses, first aid, specialties, technical courses and boat dive packages.', 'Todos os preços DiversCape numa tabela: experiências, cursos PADI, primeiros socorros, especialidades, cursos técnicos e pacotes de mergulho.', 'Alle DiversCape-Preise in einer Tabelle: Erlebnisse, PADI-Kurse, Erste Hilfe, Spezialkurse, technische Kurse und Bootstauchpakete.', 'Todos los precios de DiversCape en una tabla: experiencias, cursos PADI, primeros auxilios, especialidades, cursos técnicos y paquetes de inmersiones.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Prices', 'Preços', 'Preise', 'Precios' ),
				'title'   => $T( 'All prices in one place', 'Todos os preços num só lugar', 'Alle Preise an einem Ort', 'Todos los precios en un solo lugar' ),
				'lead'    => $T( 'Every course, experience and package — the same prices shown on each page, side by side.', 'Todos os cursos, experiências e pacotes — os mesmos preços de cada página, lado a lado.', 'Alle Kurse, Erlebnisse und Pakete — dieselben Preise wie auf jeder Seite, nebeneinander.', 'Todos los cursos, experiencias y paquetes — los mismos precios de cada página, uno al lado del otro.' ),
				'image'   => 'sea-fan-shell.jpg',
			) ),
			array( 'diverscape/prices', array() ),
			$invite,
		),
	),

	/* ======================================================================== Equipamento */
	'equipment' => array(
		'order'     => 60,
		'title'     => $T( 'Equipment', 'Equipamento', 'Ausrüstung', 'Equipo' ),
		'slug'      => $T( 'equipment', 'equipamento', 'ausruestung', 'equipo' ),
		'seo_title' => $T( 'Diving equipment servicing and rental in Sagres · DiversCape', 'Manutenção e aluguer de equipamento de mergulho em Sagres · DiversCape', 'Service und Verleih von Tauchausrüstung in Sagres · DiversCape', 'Mantenimiento y alquiler de equipo de buceo en Sagres · DiversCape' ),
		'seo_desc'  => $T( 'We service your diving equipment at an affordable price and rent diving and snorkelling equipment by the day in Sagres.', 'Fazemos a manutenção do seu equipamento a um preço acessível e alugamos equipamento de mergulho e snorkel por dia, em Sagres.', 'Wir warten Ihre Tauchausrüstung zu einem günstigen Preis und verleihen Tauch- und Schnorchelausrüstung tageweise in Sagres.', 'Mantenemos su equipo de buceo a un precio accesible y alquilamos equipo de buceo y snorkel por días en Sagres.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Equipment', 'Equipamento', 'Ausrüstung', 'Equipo' ),
				'title'   => $T( 'Servicing and rental', 'Manutenção e aluguer', 'Service und Verleih', 'Mantenimiento y alquiler' ),
				'lead'    => $T( 'We service your diving equipment at an affordable price, and rent diving and snorkelling equipment by the day.', 'Fazemos a manutenção do seu equipamento de mergulho a um preço acessível e alugamos equipamento de mergulho e de snorkel por dia.', 'Wir warten Ihre Tauchausrüstung zu einem günstigen Preis und verleihen Tauch- und Schnorchelausrüstung tageweise.', 'Mantenemos su equipo de buceo a un precio accesible y alquilamos equipo de buceo y de snorkel por días.' ),
				'image'   => 'dive-centre-building.jpg',
				'focus'   => '50% 55%',
			) ),
			array( 'diverscape/story', array(
				'eyebrow'   => $T( 'Servicing', 'Manutenção', 'Service', 'Mantenimiento' ),
				'title'     => $T( 'Look after your equipment', 'Cuide do seu equipamento', 'Pflegen Sie Ihre Ausrüstung', 'Cuide de su equipo' ),
				'text'      => $T( "Besides our courses, we offer servicing of your diving equipment at an affordable price.\n\nTell us what equipment you would like serviced and when you are in Sagres, and we will reply with the details.", "Para além dos cursos, fazemos a manutenção do seu equipamento de mergulho a um preço acessível.\n\nDiga-nos que equipamento quer rever e quando vai estar em Sagres, e respondemos com os pormenores.", "Neben unseren Kursen bieten wir den Service Ihrer Tauchausrüstung zu einem günstigen Preis an.\n\nSagen Sie uns, welche Ausrüstung gewartet werden soll und wann Sie in Sagres sind — wir melden uns mit den Einzelheiten.", "Además de los cursos, ofrecemos el mantenimiento de su equipo de buceo a un precio accesible.\n\nDíganos qué equipo quiere revisar y cuándo estará en Sagres, y le responderemos con los detalles." ),
				'image'     => 'group-boat-cliffs.jpg',
				'linkLabel' => $T( 'Ask about servicing', 'Pedir informação sobre manutenção', 'Nach dem Service fragen', 'Preguntar por el mantenimiento' ),
				'linkUrl'   => 'dvs:request',
			) ),
			array( 'diverscape/offers', array(
				'eyebrow'  => $T( 'Rental', 'Aluguer', 'Verleih', 'Alquiler' ),
				'title'    => $T( 'Rent by the day', 'Aluguer por dia', 'Tageweise leihen', 'Alquiler por días' ),
				'source'   => 'category',
				'category' => 'certified',
				'exclude'  => 'boat-dives,scuba-review,reactivate',
				'layout'   => 'rows',
				'count'    => 2,
				'tone'     => 'sand',
			) ),
			array( 'diverscape/story', array(
				'eyebrow'   => $T( 'Learn more', 'Saber mais', 'Mehr lernen', 'Aprender más' ),
				'title'     => $T( 'Know your gear inside out', 'Conheça o seu equipamento por dentro', 'Kennen Sie Ihre Ausrüstung genau', 'Conozca su equipo a fondo' ),
				'text'      => $T( 'The Equipment Specialist course shows you how your scuba equipment works and how to look after it.', 'O curso Especialista em Equipamento mostra como funciona o seu equipamento de mergulho e como cuidar dele.', 'Der Kurs Equipment Specialist zeigt Ihnen, wie Ihre Tauchausrüstung funktioniert und wie Sie sie pflegen.', 'El curso Especialista en equipo le muestra cómo funciona su equipo de buceo y cómo cuidarlo.' ),
				'image'     => 'dive-centre-map-wall.jpg',
				'reverse'   => true,
				'linkLabel' => $T( 'Equipment Specialist course', 'Curso Especialista em Equipamento', 'Kurs Equipment Specialist', 'Curso Especialista en equipo' ),
				'linkUrl'   => 'dvs:offer:equipment-specialist',
			) ),
			$invite,
		),
	),

	/* ======================================================================== Mergulhar em Sagres */
	'discover-sagres' => array(
		'order'     => 70,
		'title'     => $T( 'Diving in Sagres', 'Mergulhar em Sagres', 'Tauchen in Sagres', 'Bucear en Sagres' ),
		'slug'      => $T( 'discover-sagres', 'mergulhar-em-sagres', 'tauchen-in-sagres', 'bucear-en-sagres' ),
		'seo_title' => $T( 'Diving in Sagres, Algarve · DiversCape', 'Mergulhar em Sagres, Algarve · DiversCape', 'Tauchen in Sagres, Algarve · DiversCape', 'Bucear en Sagres, Algarve · DiversCape' ),
		'seo_desc'  => $T( 'Sagres, at the south-western tip of Portugal: diving in the Natural Park of the South-West Alentejo and Vicentine Coast, with caves, canyons and wrecks.', 'Sagres, no extremo sudoeste de Portugal: mergulho no Parque Natural do Sudoeste Alentejano e Costa Vicentina, com grutas, desfiladeiros e naufrágios.', 'Sagres am südwestlichsten Punkt Portugals: Tauchen im Naturpark Südwest-Alentejo und Costa Vicentina, mit Höhlen, Canyons und Wracks.', 'Sagres, en el extremo suroeste de Portugal: buceo en el Parque Natural del Suroeste Alentejano y Costa Vicentina, con cuevas, cañones y pecios.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Discover Sagres', 'Descubra Sagres', 'Sagres entdecken', 'Descubra Sagres' ),
				'title'   => $T( 'Where nature comes first', 'Onde a natureza vem primeiro', 'Wo die Natur an erster Stelle steht', 'Donde la naturaleza es lo primero' ),
				'lead'    => $T( 'At the south-western tip of Portugal, Sagres is a refuge for those who seek nature — above and below the water.', 'No extremo sudoeste de Portugal, Sagres é um refúgio para quem procura a natureza — acima e abaixo de água.', 'Am südwestlichsten Punkt Portugals ist Sagres ein Rückzugsort für alle, die Natur suchen — über und unter Wasser.', 'En el extremo suroeste de Portugal, Sagres es un refugio para quien busca naturaleza — sobre y bajo el agua.' ),
				'image'   => 'sagres-islets-panorama.jpg',
				'focus'   => '55% 60%',
			) ),
			array( 'diverscape/story', array(
				'eyebrow' => $T( 'The village', 'A vila', 'Der Ort', 'La villa' ),
				'title'   => $T( 'A village unlike any other in Portugal', 'Uma vila única em Portugal', 'Ein Ort wie kein anderer in Portugal', 'Una villa única en Portugal' ),
				'text'    => $T( "Sagres sits at the far south-west of Portugal — for many, “the end of Europe”. It has some of the country’s most beautiful beaches and a natural harbour shaped by the geology of the coast.\n\nOur dive centre is at Porto da Baleeira, just a few steps from the water.", "Sagres fica no extremo sudoeste de Portugal — para muitos, «o fim da Europa». Tem algumas das mais belas praias do país e um porto natural moldado pela geologia da costa.\n\nO nosso centro de mergulho fica no Porto da Baleeira, a poucos passos da água.", "Sagres liegt ganz im Südwesten Portugals — für viele „am Ende Europas“. Hier finden Sie einige der schönsten Strände des Landes und einen von der Geologie der Küste geformten Naturhafen.\n\nUnser Tauchzentrum liegt am Porto da Baleeira, nur wenige Schritte vom Wasser entfernt.", "Sagres está en el extremo suroeste de Portugal — para muchos, «el fin de Europa». Tiene algunas de las playas más bonitas del país y un puerto natural formado por la geología de la costa.\n\nNuestro centro de buceo está en el Porto da Baleeira, a pocos pasos del agua." ),
				'image'   => 'group-surface.jpg',
			) ),
			array( 'diverscape/story', array(
				'tone'    => 'deep',
				'reverse' => true,
				'eyebrow' => $T( 'Below the surface', 'Abaixo da superfície', 'Unter der Oberfläche', 'Bajo la superficie' ),
				'title'   => $T( 'Diving in a natural park', 'Mergulhar num parque natural', 'Tauchen im Naturpark', 'Bucear en un parque natural' ),
				'text'    => $T( "All our diving takes place in the Natural Park of the South-West Alentejo and Vicentine Coast. Dive sites range from caves and canyons to passages, and there are wrecks to explore.\n\nWe offer different types of diving for every level, from beginners to those looking for a new challenge — and our dives are accompanied by a guide or instructor.", "Todos os nossos mergulhos decorrem no Parque Natural do Sudoeste Alentejano e Costa Vicentina. Os locais de mergulho vão de grutas e desfiladeiros a passagens, e há naufrágios para explorar.\n\nOferecemos vários tipos de mergulho para todos os níveis, de iniciantes a quem procura um novo desafio — e os nossos mergulhos são acompanhados por um guia ou instrutor.", "Wir tauchen ausschließlich im Naturpark Südwest-Alentejo und Costa Vicentina. Die Tauchplätze reichen von Höhlen und Canyons bis zu Durchgängen, und es gibt Wracks zu erkunden.\n\nWir bieten verschiedene Arten des Tauchens für jedes Level, vom Anfänger bis zu allen, die eine neue Herausforderung suchen — und unsere Tauchgänge werden von einem Guide oder Instruktor begleitet.", "Todas nuestras inmersiones se realizan en el Parque Natural del Suroeste Alentejano y Costa Vicentina. Los puntos de buceo van desde cuevas y cañones hasta pasos, y hay pecios por explorar.\n\nOfrecemos distintos tipos de buceo para todos los niveles, desde principiantes hasta quien busca un nuevo reto — y nuestras inmersiones van acompañadas por un guía o instructor." ),
				'image'   => 'cave-opening-diver.jpg',
				'image2'  => 'wreck-diver.jpg',
			) ),
			array( 'diverscape/gallery', array(
				'eyebrow'   => $T( 'Gallery', 'Galeria', 'Galerie', 'Galería' ),
				'title'     => $T( 'Moments from our dives', 'Momentos dos nossos mergulhos', 'Momente unserer Tauchgänge', 'Momentos de nuestras inmersiones' ),
				'count'     => 8,
				'linkLabel' => $T( 'See the full gallery', 'Ver a galeria completa', 'Zur ganzen Galerie', 'Ver la galería completa' ),
				'linkUrl'   => 'dvs:gallery',
			) ),
			array( 'diverscape/links', array(
				'eyebrow' => $T( 'Plan your stay', 'Planeie a estadia', 'Aufenthalt planen', 'Planifique su estancia' ),
				'title'   => $T( 'Useful links', 'Ligações úteis', 'Nützliche Links', 'Enlaces útiles' ),
				'items'   => $T(
					"Natural Park of the South-West Alentejo and Vicentine Coast | https://natural.pt/portal/en/AreaProtegida/Item/12 | Information about the protected area\nSagres Sun Stay | https://sagressunstay.com/ | Accommodation in Sagres\nNAU Salema Beach Village | https://www.salemabeachvillage.com/en/ | Accommodation in Salema\nMy Tourist Maps | https://mytouristmaps.com/ | Maps of the region",
					"Parque Natural do Sudoeste Alentejano e Costa Vicentina | https://natural.pt/portal/en/AreaProtegida/Item/12 | Informação sobre a área protegida\nSagres Sun Stay | https://sagressunstay.com/ | Alojamento em Sagres\nNAU Salema Beach Village | https://www.salemabeachvillage.com/en/ | Alojamento na Salema\nMy Tourist Maps | https://mytouristmaps.com/ | Mapas da região",
					"Naturpark Südwest-Alentejo und Costa Vicentina | https://natural.pt/portal/en/AreaProtegida/Item/12 | Informationen zum Schutzgebiet\nSagres Sun Stay | https://sagressunstay.com/ | Unterkunft in Sagres\nNAU Salema Beach Village | https://www.salemabeachvillage.com/en/ | Unterkunft in Salema\nMy Tourist Maps | https://mytouristmaps.com/ | Karten der Region",
					"Parque Natural del Suroeste Alentejano y Costa Vicentina | https://natural.pt/portal/en/AreaProtegida/Item/12 | Información sobre el área protegida\nSagres Sun Stay | https://sagressunstay.com/ | Alojamiento en Sagres\nNAU Salema Beach Village | https://www.salemabeachvillage.com/en/ | Alojamiento en Salema\nMy Tourist Maps | https://mytouristmaps.com/ | Mapas de la región"
				),
			) ),
			$invite,
		),
	),

	/* ======================================================================== Sobre */
	'about' => array(
		'order'     => 80,
		'title'     => $T( 'About us', 'Sobre nós', 'Über uns', 'Sobre nosotros' ),
		'slug'      => $T( 'about', 'sobre-nos', 'ueber-uns', 'sobre-nosotros' ),
		'seo_title' => $T( 'About DiversCape · Dive centre at Porto da Baleeira, Sagres', 'Sobre a DiversCape · Centro de mergulho no Porto da Baleeira, Sagres', 'Über DiversCape · Tauchzentrum am Porto da Baleeira, Sagres', 'Sobre DiversCape · Centro de buceo en el Porto da Baleeira, Sagres' ),
		'seo_desc'  => $T( 'A diverse and dedicated team with more than 15 years of combined experience in the diving industry, at Porto da Baleeira in Sagres.', 'Uma equipa variada e dedicada, com mais de 15 anos de experiência acumulada na indústria do mergulho, no Porto da Baleeira, em Sagres.', 'Ein vielseitiges, engagiertes Team mit zusammen mehr als 15 Jahren Erfahrung in der Tauchbranche, am Porto da Baleeira in Sagres.', 'Un equipo variado y dedicado con más de 15 años de experiencia acumulada en el sector del buceo, en el Porto da Baleeira de Sagres.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'About us', 'Sobre nós', 'Über uns', 'Sobre nosotros' ),
				'title'   => $T( 'DiversCape, Sagres', 'DiversCape, Sagres', 'DiversCape, Sagres', 'DiversCape, Sagres' ),
				'lead'    => $T( 'A diverse and dedicated team at Porto da Baleeira, a few steps from the sea.', 'Uma equipa variada e dedicada no Porto da Baleeira, a poucos passos do mar.', 'Ein vielseitiges, engagiertes Team am Porto da Baleeira, wenige Schritte vom Meer.', 'Un equipo variado y dedicado en el Porto da Baleeira, a pocos pasos del mar.' ),
				'image'   => 'team-boat-harbour.jpg',
				'focus'   => '50% 40%',
			) ),
			array( 'diverscape/trust', array(
				'eyebrow' => $T( 'Our knowledge', 'O nosso conhecimento', 'Unser Wissen', 'Nuestro conocimiento' ),
				'title'   => $T( 'Always learning, always diving', 'Sempre a aprender, sempre a mergulhar', 'Immer lernen, immer tauchen', 'Siempre aprendiendo, siempre buceando' ),
				'text'    => $T( "Our team keeps expanding its knowledge. Thanks to a diverse and dedicated team, we have a broad range of knowledge and skills — with more than 15 years of combined experience in the diving industry.\n\nThat lets us offer a wide range of services: courses from first experiences to technical diving, boat dives for certified divers, and equipment servicing at an affordable price.", "A nossa equipa continua a expandir os seus conhecimentos. Graças a uma equipa variada e dedicada, reunimos um leque alargado de conhecimentos e competências — com mais de 15 anos de experiência acumulada na indústria do mergulho.\n\nIsso permite-nos oferecer um leque variado de serviços: cursos das primeiras experiências ao mergulho técnico, saídas de barco para mergulhadores certificados e manutenção de equipamento a um preço acessível.", "Unser Team erweitert sein Wissen ständig. Dank eines vielseitigen und engagierten Teams verfügen wir über breites Wissen und viele Fähigkeiten — mit zusammen mehr als 15 Jahren Erfahrung in der Tauchbranche.\n\nSo können wir Ihnen vielfältige Leistungen anbieten: Kurse vom Schnuppertauchen bis zum technischen Tauchen, Bootstauchgänge für brevetierte Taucher und Ausrüstungsservice zu einem günstigen Preis.", "Nuestro equipo amplía continuamente sus conocimientos. Gracias a un equipo variado y dedicado, reunimos un amplio abanico de conocimientos y habilidades — con más de 15 años de experiencia acumulada en el sector del buceo.\n\nEso nos permite ofrecer una amplia variedad de servicios: cursos desde las primeras experiencias hasta el buceo técnico, salidas en barco para buceadores certificados y mantenimiento de equipo a un precio accesible." ),
				'points'  => $T( "Our dives are accompanied by a guide or instructor\nPADI and PADI TecRec courses\nEquipment servicing", "Os nossos mergulhos são acompanhados por um guia ou instrutor\nCursos PADI e PADI TecRec\nManutenção de equipamento", "Unsere Tauchgänge werden von einem Guide oder Instruktor begleitet\nPADI- und PADI-TecRec-Kurse\nAusrüstungsservice", "Nuestras inmersiones van acompañadas por un guía o instructor\nCursos PADI y PADI TecRec\nMantenimiento de equipo" ),
				'image'   => 'group-boat-cliffs.jpg',
				'image2'  => 'hero-shore-entry.jpg',
			) ),
			array( 'diverscape/story', array(
				'eyebrow'   => $T( 'The dive centre', 'O centro de mergulho', 'Das Tauchzentrum', 'El centro de buceo' ),
				'title'     => $T( 'At Porto da Baleeira', 'No Porto da Baleeira', 'Am Porto da Baleeira', 'En el Porto da Baleeira' ),
				'text'      => $T( 'Our dive centre is at Porto da Baleeira in Sagres, just a few steps from access to the sea.', 'O nosso centro de mergulho fica no Porto da Baleeira, em Sagres, a poucos passos do acesso ao mar.', 'Unser Tauchzentrum liegt am Porto da Baleeira in Sagres, nur wenige Schritte vom Zugang zum Meer entfernt.', 'Nuestro centro de buceo está en el Porto da Baleeira de Sagres, a pocos pasos del acceso al mar.' ),
				'image'     => 'dive-centre-building.jpg',
				'reverse'   => true,
				'tone'      => 'sand',
				'linkLabel' => $T( 'Contact and directions', 'Contactos e como chegar', 'Kontakt und Anfahrt', 'Contacto y cómo llegar' ),
				'linkUrl'   => 'dvs:contact',
			) ),
			array( 'diverscape/reviews', array(
				'eyebrow' => $T( 'Reviews', 'Avaliações', 'Bewertungen', 'Opiniones' ),
				'title'   => $T( 'What our divers say', 'O que dizem os nossos mergulhadores', 'Was unsere Taucher sagen', 'Lo que dicen nuestros buceadores' ),
				'text'    => $T( 'We take pride in our customer service, and our happy divers tell the story better than we can.', 'Temos orgulho no nosso atendimento, e os nossos mergulhadores contam a história melhor do que nós.', 'Wir sind stolz auf unseren Service — und unsere zufriedenen Taucher erzählen es besser als wir.', 'Estamos orgullosos de nuestra atención, y nuestros buceadores lo cuentan mejor que nosotros.' ),
			) ),
			$invite,
		),
	),

	/* ======================================================================== Galeria e avaliações */
	'gallery' => array(
		'order'     => 90,
		'title'     => $T( 'Gallery and reviews', 'Galeria e avaliações', 'Galerie und Bewertungen', 'Galería y opiniones' ),
		'slug'      => $T( 'gallery', 'galeria', 'galerie', 'galeria' ),
		'seo_title' => $T( 'Gallery and reviews · DiversCape Sagres', 'Galeria e avaliações · DiversCape Sagres', 'Galerie und Bewertungen · DiversCape Sagres', 'Galería y opiniones · DiversCape Sagres' ),
		'seo_desc'  => $T( 'Photos from our dives and from the dive centre in Sagres, and reviews from our divers on Tripadvisor.', 'Fotografias dos nossos mergulhos e do centro em Sagres, e avaliações dos nossos mergulhadores no Tripadvisor.', 'Fotos von unseren Tauchgängen und vom Tauchzentrum in Sagres sowie Bewertungen unserer Taucher auf Tripadvisor.', 'Fotos de nuestras inmersiones y del centro en Sagres, y opiniones de nuestros buceadores en Tripadvisor.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Gallery', 'Galeria', 'Galerie', 'Galería' ),
				'title'   => $T( 'Gallery and reviews', 'Galeria e avaliações', 'Galerie und Bewertungen', 'Galería y opiniones' ),
				'lead'    => $T( 'Moments from our dives and from the dive centre.', 'Momentos dos nossos mergulhos e do centro.', 'Momente unserer Tauchgänge und aus dem Tauchzentrum.', 'Momentos de nuestras inmersiones y del centro.' ),
				'image'   => 'octopus.jpg',
				'focus'   => '45% 50%',
			) ),
			array( 'diverscape/gallery', array( 'count' => 0 ) ),
			array( 'diverscape/reviews', array(
				'eyebrow' => $T( 'Reviews', 'Avaliações', 'Bewertungen', 'Opiniones' ),
				'title'   => $T( 'What our divers say', 'O que dizem os nossos mergulhadores', 'Was unsere Taucher sagen', 'Lo que dicen nuestros buceadores' ),
				'text'    => $T( 'We take pride in our customer service, and our happy divers tell the story better than we can.', 'Temos orgulho no nosso atendimento, e os nossos mergulhadores contam a história melhor do que nós.', 'Wir sind stolz auf unseren Service — und unsere zufriedenen Taucher erzählen es besser als wir.', 'Estamos orgullosos de nuestra atención, y nuestros buceadores lo cuentan mejor que nosotros.' ),
			) ),
			$invite,
		),
	),

	/* ======================================================================== Contactos e pedido */
	'contact' => array(
		'order'     => 100,
		'title'     => $T( 'Plan your dive', 'Planeie o seu mergulho', 'Tauchgang planen', 'Planifique su inmersión' ),
		'slug'      => $T( 'contact', 'contactos', 'kontakt', 'contacto' ),
		'seo_title' => $T( 'Contact and availability request · DiversCape Sagres', 'Contactos e pedido de disponibilidade · DiversCape Sagres', 'Kontakt und Verfügbarkeitsanfrage · DiversCape Sagres', 'Contacto y solicitud de disponibilidad · DiversCape Sagres' ),
		'seo_desc'  => $T( 'Send an availability request or contact DiversCape at Porto da Baleeira, Sagres: dive@diverscape.net, +351 965 559 073.', 'Envie um pedido de disponibilidade ou contacte a DiversCape no Porto da Baleeira, Sagres: dive@diverscape.net, +351 965 559 073.', 'Senden Sie eine Verfügbarkeitsanfrage oder kontaktieren Sie DiversCape am Porto da Baleeira, Sagres: dive@diverscape.net, +351 965 559 073.', 'Envíe una solicitud de disponibilidad o contacte con DiversCape en el Porto da Baleeira, Sagres: dive@diverscape.net, +351 965 559 073.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Contact', 'Contactos', 'Kontakt', 'Contacto' ),
				'title'   => $T( 'Plan your dive', 'Planeie o seu mergulho', 'Planen Sie Ihren Tauchgang', 'Planifique su inmersión' ),
				'lead'    => $T( 'Send an availability request in four short steps, or get in touch directly. Not sure what to choose? That is fine — just ask.', 'Envie um pedido de disponibilidade em quatro passos curtos ou contacte-nos diretamente. Não sabe o que escolher? Não faz mal — pergunte.', 'Senden Sie eine Verfügbarkeitsanfrage in vier kurzen Schritten oder kontaktieren Sie uns direkt. Noch unsicher? Kein Problem — fragen Sie einfach.', 'Envíe una solicitud de disponibilidad en cuatro pasos cortos o contáctenos directamente. ¿No sabe qué elegir? No pasa nada — pregúntenos.' ),
				'image'   => 'hero-shore-entry.jpg',
				'focus'   => '50% 40%',
			) ),
			array( 'diverscape/request', array() ),
			array( 'diverscape/contact', array(
				'title' => $T( 'Find us', 'Onde estamos', 'So finden Sie uns', 'Dónde estamos' ),
				'text'  => $T( 'Our dive centre is at Porto da Baleeira in Sagres, just a few steps from the sea.', 'O nosso centro de mergulho fica no Porto da Baleeira, em Sagres, a poucos passos do mar.', 'Unser Tauchzentrum liegt am Porto da Baleeira in Sagres, nur wenige Schritte vom Meer.', 'Nuestro centro de buceo está en el Porto da Baleeira de Sagres, a pocos pasos del mar.' ),
			) ),
			array( 'diverscape/faq', array(
				'title' => $T( 'About requests', 'Sobre os pedidos', 'Zu Anfragen', 'Sobre las solicitudes' ),
				'topic' => 'request',
			) ),
		),
	),

	/* ======================================================================== FAQ */
	'faq' => array(
		'order'     => 110,
		'title'     => $T( 'Frequently asked questions', 'Perguntas frequentes', 'Häufige Fragen', 'Preguntas frecuentes' ),
		'slug'      => $T( 'faq', 'perguntas-frequentes', 'haeufige-fragen', 'preguntas-frecuentes' ),
		'seo_title' => $T( 'Frequently asked questions · DiversCape Sagres', 'Perguntas frequentes · DiversCape Sagres', 'Häufige Fragen · DiversCape Sagres', 'Preguntas frecuentes · DiversCape Sagres' ),
		'seo_desc'  => $T( 'Answers about diving with DiversCape in Sagres: experiences, courses, equipment rental, boat dives and availability requests.', 'Respostas sobre mergulhar com a DiversCape em Sagres: experiências, cursos, aluguer de equipamento, saídas de barco e pedidos.', 'Antworten rund ums Tauchen mit DiversCape in Sagres: Erlebnisse, Kurse, Ausrüstungsverleih, Bootstauchgänge und Anfragen.', 'Respuestas sobre bucear con DiversCape en Sagres: experiencias, cursos, alquiler de equipo, salidas en barco y solicitudes.' ),
		'blocks'    => array(
			array( 'diverscape/hero', array(
				'eyebrow' => $T( 'Help', 'Ajuda', 'Hilfe', 'Ayuda' ),
				'title'   => $T( 'Frequently asked questions', 'Perguntas frequentes', 'Häufige Fragen', 'Preguntas frecuentes' ),
				'lead'    => $T( 'Answers to the questions we hear most. Can’t find yours? Ask us.', 'Respostas às perguntas que mais ouvimos. Não encontra a sua? Pergunte-nos.', 'Antworten auf die häufigsten Fragen. Ihre ist nicht dabei? Fragen Sie uns.', 'Respuestas a las preguntas más habituales. ¿No encuentra la suya? Pregúntenos.' ),
				'image'   => 'sea-slug-yellow.jpg',
			) ),
			array( 'diverscape/faq', array() ),
			$invite,
		),
	),

	/* ======================================================================== Privacidade */
	'privacy' => array(
		'order'     => 200,
		'footer'    => true,
		'title'     => $T( 'Privacy policy', 'Política de privacidade', 'Datenschutzerklärung', 'Política de privacidad' ),
		'slug'      => $T( 'privacy', 'privacidade', 'datenschutz', 'privacidad' ),
		'seo_desc'  => $T( 'How DiversCape handles the personal data you send through this website.', 'Como a DiversCape trata os dados pessoais enviados através deste site.', 'Wie DiversCape mit den über diese Website übermittelten personenbezogenen Daten umgeht.', 'Cómo trata DiversCape los datos personales enviados a través de este sitio.' ),
		'blocks'    => array(
			$plain_hero( $T( 'Legal', 'Informação legal', 'Rechtliches', 'Información legal' ), $T( 'Privacy policy', 'Política de privacidade', 'Datenschutzerklärung', 'Política de privacidad' ), $draft_note ),
			array( 'prose', array(
				'en' => array(
					array( 'h2', 'Who is responsible' ),
					array( 'p', 'DiversCape, Porto da Baleeira, 8650-368 Sagres, Portugal — dive@diverscape.net, +351 965 559 073. [Legal name and tax number to be completed by DiversCape.]' ),
					array( 'h2', 'What we collect and why' ),
					array( 'p', 'When you send an availability request we receive the activity you chose, your preferred date and flexibility, the number of participants, your diving experience level, your name, email address, optional phone number, preferred language and your message. We use these details only to answer your request and, if you then book, to organise the activity.' ),
					array( 'p', 'The legal basis is taking steps at your request before a possible contract (Article 6(1)(b) of the GDPR). Please do not send medical information or documents through the form.' ),
					array( 'h2', 'How long we keep it' ),
					array( 'p', 'The website does not store your request: it is delivered by email to our team. We keep that email only for as long as needed to answer you and to meet any legal obligations. [Retention period to be defined by DiversCape.]' ),
					array( 'h2', 'Who else is involved' ),
					array( 'ul', array( 'Our hosting and email providers, who process data on our behalf. [Providers to be listed before launch.]', 'Tripadvisor, only if you choose to load the reviews widget on our pages.', 'Google Maps, only if you follow the “Get directions” link, which opens Google’s own website.' ) ),
					array( 'p', 'We do not use your details for marketing and we do not use analytics or advertising tools on this website.' ),
					array( 'h2', 'Your rights' ),
					array( 'p', 'You can ask to access, correct or delete your data, to restrict or object to its use, and to receive it in a portable format, by writing to dive@diverscape.net. You can also complain to the Portuguese data protection authority, CNPD (www.cnpd.pt).' ),
				),
				'pt' => array(
					array( 'h2', 'Responsável pelo tratamento' ),
					array( 'p', 'DiversCape, Porto da Baleeira, 8650-368 Sagres, Portugal — dive@diverscape.net, +351 965 559 073. [Denominação legal e NIF a completar pela DiversCape.]' ),
					array( 'h2', 'Que dados recolhemos e para quê' ),
					array( 'p', 'Quando envia um pedido de disponibilidade recebemos a atividade escolhida, a data preferida e a flexibilidade, o número de participantes, a sua experiência de mergulho, o nome, o email, o telefone (opcional), o idioma preferido e a mensagem. Usamos estes dados apenas para responder ao pedido e, se avançar com a reserva, para organizar a atividade.' ),
					array( 'p', 'O fundamento é a realização de diligências pré-contratuais a seu pedido (artigo 6.º, n.º 1, alínea b) do RGPD). Não envie informação clínica nem documentos através do formulário.' ),
					array( 'h2', 'Durante quanto tempo' ),
					array( 'p', 'O site não guarda o seu pedido: este é entregue por email à nossa equipa. Conservamos esse email apenas o tempo necessário para lhe responder e para cumprir obrigações legais. [Prazo de conservação a definir pela DiversCape.]' ),
					array( 'h2', 'Quem mais intervém' ),
					array( 'ul', array( 'Os fornecedores de alojamento e de email, que tratam dados por nossa conta. [Fornecedores a indicar antes do lançamento.]', 'O Tripadvisor, apenas se optar por carregar o widget de avaliações nas nossas páginas.', 'O Google Maps, apenas se seguir a ligação «Como chegar», que abre o site da Google.' ) ),
					array( 'p', 'Não usamos os seus dados para marketing nem utilizamos ferramentas de análise ou publicidade neste site.' ),
					array( 'h2', 'Os seus direitos' ),
					array( 'p', 'Pode pedir o acesso, a retificação ou o apagamento dos seus dados, a limitação ou oposição ao tratamento e a portabilidade, escrevendo para dive@diverscape.net. Pode também apresentar reclamação à CNPD — Comissão Nacional de Proteção de Dados (www.cnpd.pt).' ),
				),
				'de' => array(
					array( 'h2', 'Verantwortliche Stelle' ),
					array( 'p', 'DiversCape, Porto da Baleeira, 8650-368 Sagres, Portugal — dive@diverscape.net, +351 965 559 073. [Firmenname und Steuernummer von DiversCape zu ergänzen.]' ),
					array( 'h2', 'Welche Daten wir erheben und wozu' ),
					array( 'p', 'Wenn Sie eine Verfügbarkeitsanfrage senden, erhalten wir die gewählte Aktivität, Ihren Wunschtermin und Ihre Flexibilität, die Anzahl der Teilnehmenden, Ihre Taucherfahrung, Ihren Namen, Ihre E-Mail-Adresse, optional Ihre Telefonnummer, Ihre bevorzugte Sprache und Ihre Nachricht. Wir verwenden diese Angaben nur, um Ihre Anfrage zu beantworten und — falls Sie buchen — die Aktivität zu organisieren.' ),
					array( 'p', 'Rechtsgrundlage ist die Durchführung vorvertraglicher Maßnahmen auf Ihre Anfrage (Art. 6 Abs. 1 lit. b DSGVO). Bitte senden Sie über das Formular keine medizinischen Angaben oder Dokumente.' ),
					array( 'h2', 'Speicherdauer' ),
					array( 'p', 'Die Website speichert Ihre Anfrage nicht: Sie wird per E-Mail an unser Team zugestellt. Diese E-Mail bewahren wir nur so lange auf, wie es zur Beantwortung und zur Erfüllung gesetzlicher Pflichten nötig ist. [Speicherdauer von DiversCape festzulegen.]' ),
					array( 'h2', 'Wer sonst beteiligt ist' ),
					array( 'ul', array( 'Unsere Hosting- und E-Mail-Anbieter, die Daten in unserem Auftrag verarbeiten. [Anbieter vor dem Start anzugeben.]', 'Tripadvisor, nur wenn Sie das Bewertungs-Widget auf unseren Seiten laden.', 'Google Maps, nur wenn Sie dem Link „Route planen“ folgen, der die Website von Google öffnet.' ) ),
					array( 'p', 'Wir nutzen Ihre Daten nicht für Werbung und setzen auf dieser Website keine Analyse- oder Werbetools ein.' ),
					array( 'h2', 'Ihre Rechte' ),
					array( 'p', 'Sie können Auskunft, Berichtigung oder Löschung Ihrer Daten, die Einschränkung oder den Widerspruch gegen die Verarbeitung sowie Datenübertragbarkeit verlangen — schreiben Sie an dive@diverscape.net. Sie können sich außerdem bei der portugiesischen Datenschutzbehörde CNPD (www.cnpd.pt) beschweren.' ),
				),
				'es' => array(
					array( 'h2', 'Responsable del tratamiento' ),
					array( 'p', 'DiversCape, Porto da Baleeira, 8650-368 Sagres, Portugal — dive@diverscape.net, +351 965 559 073. [Denominación legal y NIF a completar por DiversCape.]' ),
					array( 'h2', 'Qué datos recogemos y para qué' ),
					array( 'p', 'Cuando envía una solicitud de disponibilidad recibimos la actividad elegida, la fecha preferida y la flexibilidad, el número de participantes, su experiencia de buceo, su nombre, su correo electrónico, el teléfono (opcional), el idioma preferido y su mensaje. Usamos estos datos solo para responder a la solicitud y, si después reserva, para organizar la actividad.' ),
					array( 'p', 'La base jurídica es la aplicación de medidas precontractuales a petición suya (artículo 6.1.b del RGPD). No envíe información médica ni documentos a través del formulario.' ),
					array( 'h2', 'Durante cuánto tiempo' ),
					array( 'p', 'El sitio no guarda su solicitud: se entrega por correo electrónico a nuestro equipo. Conservamos ese correo solo el tiempo necesario para responderle y cumplir obligaciones legales. [Plazo de conservación a definir por DiversCape.]' ),
					array( 'h2', 'Quién más interviene' ),
					array( 'ul', array( 'Nuestros proveedores de alojamiento y de correo, que tratan datos por cuenta nuestra. [Proveedores a indicar antes del lanzamiento.]', 'Tripadvisor, solo si decide cargar el widget de opiniones en nuestras páginas.', 'Google Maps, solo si sigue el enlace «Cómo llegar», que abre el sitio de Google.' ) ),
					array( 'p', 'No usamos sus datos para marketing ni utilizamos herramientas de análisis o publicidad en este sitio.' ),
					array( 'h2', 'Sus derechos' ),
					array( 'p', 'Puede solicitar el acceso, la rectificación o la supresión de sus datos, la limitación u oposición al tratamiento y la portabilidad, escribiendo a dive@diverscape.net. También puede reclamar ante la autoridad portuguesa de protección de datos, CNPD (www.cnpd.pt).' ),
				),
			) ),
		),
	),

	/* ======================================================================== Cookies */
	'cookies' => array(
		'order'     => 210,
		'footer'    => true,
		'title'     => $T( 'Cookies', 'Cookies', 'Cookies', 'Cookies' ),
		'slug'      => $T( 'cookies', 'cookies', 'cookies', 'cookies' ),
		'seo_desc'  => $T( 'Which cookies this website uses.', 'Que cookies este site utiliza.', 'Welche Cookies diese Website verwendet.', 'Qué cookies utiliza este sitio.' ),
		'blocks'    => array(
			$plain_hero( $T( 'Legal', 'Informação legal', 'Rechtliches', 'Información legal' ), $T( 'Cookies', 'Cookies', 'Cookies', 'Cookies' ), $draft_note ),
			array( 'prose', array(
				'en' => array(
					array( 'p', 'This website does not use analytics, advertising or social media tracking cookies, so we do not show a cookie banner.' ),
					array( 'h2', 'Strictly necessary' ),
					array( 'p', 'Cookies are only set for people who log in to manage the website. Visitors browsing the site and sending a request do not receive any cookies from us.' ),
					array( 'h2', 'Third-party content you choose to load' ),
					array( 'p', 'The Tripadvisor reviews widget is only loaded if you press “Show Tripadvisor reviews here”. Tripadvisor may then set its own cookies, under its own policy. Links to Google Maps, Facebook and Tripadvisor open those websites, where their own policies apply.' ),
				),
				'pt' => array(
					array( 'p', 'Este site não utiliza cookies de análise, publicidade ou redes sociais, pelo que não apresentamos um aviso de cookies.' ),
					array( 'h2', 'Estritamente necessários' ),
					array( 'p', 'Só são definidos cookies para quem inicia sessão para gerir o site. Quem visita o site e envia um pedido não recebe cookies nossos.' ),
					array( 'h2', 'Conteúdos de terceiros que escolhe carregar' ),
					array( 'p', 'O widget de avaliações do Tripadvisor só é carregado se carregar em «Mostrar aqui as avaliações do Tripadvisor». O Tripadvisor pode então definir os seus próprios cookies, segundo a sua política. As ligações para o Google Maps, Facebook e Tripadvisor abrem esses sites, onde se aplicam as respetivas políticas.' ),
				),
				'de' => array(
					array( 'p', 'Diese Website verwendet keine Analyse-, Werbe- oder Social-Media-Cookies, daher zeigen wir kein Cookie-Banner.' ),
					array( 'h2', 'Unbedingt erforderlich' ),
					array( 'p', 'Cookies werden nur für Personen gesetzt, die sich zur Verwaltung der Website anmelden. Besucher, die die Website ansehen und eine Anfrage senden, erhalten von uns keine Cookies.' ),
					array( 'h2', 'Inhalte Dritter, die Sie selbst laden' ),
					array( 'p', 'Das Tripadvisor-Bewertungs-Widget wird nur geladen, wenn Sie auf „Tripadvisor-Bewertungen hier anzeigen“ klicken. Tripadvisor kann dann eigene Cookies nach eigener Richtlinie setzen. Links zu Google Maps, Facebook und Tripadvisor öffnen diese Websites, für die deren eigene Richtlinien gelten.' ),
				),
				'es' => array(
					array( 'p', 'Este sitio no utiliza cookies de análisis, publicidad ni redes sociales, por lo que no mostramos un aviso de cookies.' ),
					array( 'h2', 'Estrictamente necesarias' ),
					array( 'p', 'Solo se instalan cookies para quien inicia sesión para gestionar el sitio. Quien visita el sitio y envía una solicitud no recibe cookies nuestras.' ),
					array( 'h2', 'Contenido de terceros que decide cargar' ),
					array( 'p', 'El widget de opiniones de Tripadvisor solo se carga si pulsa «Mostrar aquí las opiniones de Tripadvisor». Tripadvisor puede entonces instalar sus propias cookies, según su política. Los enlaces a Google Maps, Facebook y Tripadvisor abren esos sitios, donde se aplican sus propias políticas.' ),
				),
			) ),
		),
	),

	/* ======================================================================== Condições do pedido */
	'request-terms' => array(
		'order'     => 220,
		'footer'    => true,
		'title'     => $T( 'Request conditions', 'Condições do pedido', 'Anfragebedingungen', 'Condiciones de la solicitud' ),
		'slug'      => $T( 'request-conditions', 'condicoes-do-pedido', 'anfragebedingungen', 'condiciones-de-solicitud' ),
		'seo_desc'  => $T( 'How availability requests work at DiversCape.', 'Como funcionam os pedidos de disponibilidade na DiversCape.', 'So funktionieren Verfügbarkeitsanfragen bei DiversCape.', 'Cómo funcionan las solicitudes de disponibilidad en DiversCape.' ),
		'blocks'    => array(
			$plain_hero( $T( 'Legal', 'Informação legal', 'Rechtliches', 'Información legal' ), $T( 'Request conditions', 'Condições do pedido', 'Anfragebedingungen', 'Condiciones de la solicitud' ), $draft_note ),
			array( 'prose', array(
				'en' => array(
					array( 'h2', 'A request is not a booking' ),
					array( 'p', 'The form on this website sends an availability request. No place is reserved and no payment is taken online. Your activity is confirmed only when our team replies to you.' ),
					array( 'h2', 'Prices' ),
					array( 'p', 'Prices on this website are in euros and are those published on our current website. They are confirmed by our team when we reply. Technical courses are priced on request.' ),
					array( 'h2', 'Before your activity' ),
					array( 'p', 'Our team will explain any requirements for the activity you choose and what you need to bring. Booking, payment and cancellation conditions are communicated by the team when confirming your activity. [Conditions to be defined by DiversCape before launch.]' ),
				),
				'pt' => array(
					array( 'h2', 'Um pedido não é uma reserva' ),
					array( 'p', 'O formulário deste site envia um pedido de disponibilidade. Nenhuma vaga fica reservada e não é feito qualquer pagamento online. A atividade só fica confirmada quando a nossa equipa lhe responder.' ),
					array( 'h2', 'Preços' ),
					array( 'p', 'Os preços deste site estão em euros e são os publicados no nosso site atual. São confirmados pela equipa na resposta. Os cursos técnicos têm preço sob consulta.' ),
					array( 'h2', 'Antes da atividade' ),
					array( 'p', 'A equipa explica os requisitos da atividade escolhida e o que deve trazer. As condições de reserva, pagamento e cancelamento são comunicadas pela equipa ao confirmar a atividade. [Condições a definir pela DiversCape antes do lançamento.]' ),
				),
				'de' => array(
					array( 'h2', 'Eine Anfrage ist keine Buchung' ),
					array( 'p', 'Das Formular auf dieser Website sendet eine Verfügbarkeitsanfrage. Es wird kein Platz reserviert und online nichts bezahlt. Ihre Aktivität ist erst bestätigt, wenn unser Team Ihnen antwortet.' ),
					array( 'h2', 'Preise' ),
					array( 'p', 'Die Preise auf dieser Website sind in Euro angegeben und entsprechen den auf unserer bisherigen Website veröffentlichten Preisen. Unser Team bestätigt sie in seiner Antwort. Technische Kurse gibt es auf Anfrage.' ),
					array( 'h2', 'Vor Ihrer Aktivität' ),
					array( 'p', 'Unser Team erklärt Ihnen die Voraussetzungen der gewählten Aktivität und was Sie mitbringen sollten. Buchungs-, Zahlungs- und Stornobedingungen teilt Ihnen das Team bei der Bestätigung mit. [Bedingungen vor dem Start von DiversCape festzulegen.]' ),
				),
				'es' => array(
					array( 'h2', 'Una solicitud no es una reserva' ),
					array( 'p', 'El formulario de este sitio envía una solicitud de disponibilidad. No se reserva ninguna plaza ni se realiza ningún pago en línea. Su actividad solo queda confirmada cuando nuestro equipo le responde.' ),
					array( 'h2', 'Precios' ),
					array( 'p', 'Los precios de este sitio están en euros y son los publicados en nuestro sitio actual. El equipo los confirma en su respuesta. Los cursos técnicos tienen precio bajo consulta.' ),
					array( 'h2', 'Antes de su actividad' ),
					array( 'p', 'El equipo le explicará los requisitos de la actividad elegida y qué debe traer. Las condiciones de reserva, pago y cancelación las comunica el equipo al confirmar la actividad. [Condiciones a definir por DiversCape antes del lanzamiento.]' ),
				),
			) ),
		),
	),
);
