<?php
/**
 * Media da DiversCape. Todas as fotografias vêm do site atual (https://diverscape.net/Extensions/Images/),
 * otimizadas a partir dos originais de maior resolução disponíveis (ver docs/inventario-media.md).
 * Nenhuma imagem ilustrativa de terceiros foi usada.
 *
 * Legendas descrevem o que é visível, sem identificar espécies apenas pelo nome do ficheiro.
 * `review` = nota interna para validação pela equipa.
 */

defined( 'ABSPATH' ) || exit;

$src = static fn( $f ) => 'https://diverscape.net/Extensions/Images/' . $f . ' — fotografia publicada no site atual da DiversCape';
$L   = static fn( $en, $pt, $de, $es ) => compact( 'en', 'pt', 'de', 'es' );

return array(
	'logo-diverscape.png' => array(
		'title'  => 'Logótipo DiversCape',
		'source' => $src( 'logo.png' ) . ' (logótipo original, 719×557, fundo transparente)',
		'alt'    => $L( 'DiversCape — Sagres, Portugal', 'DiversCape — Sagres, Portugal', 'DiversCape — Sagres, Portugal', 'DiversCape — Sagres, Portugal' ),
	),
	'padi-logo.png'       => array(
		'title'  => 'PADI',
		'source' => $src( 'Padi-Logo.png' ) . ' — marca de terceiros; confirmar estatuto de membro antes do lançamento',
		'alt'    => $L( 'PADI', 'PADI', 'PADI', 'PADI' ),
	),
	'padi-tecrec-logo.png' => array(
		'title'  => 'PADI TecRec',
		'source' => $src( 'PADI-TecRec-Logo-Black-Hires.png' ) . ' — marca de terceiros; confirmar estatuto antes do lançamento',
		'alt'    => $L( 'PADI TecRec', 'PADI TecRec', 'PADI TecRec', 'PADI TecRec' ),
	),
	'diver-anemone.jpg'   => array(
		'title'   => 'Mergulhador e anémona',
		'source'  => $src( 'explore.jpg' ),
		'alt'     => $L( 'A diver looks closely at an anemone beside a reef covered in red sponges', 'Um mergulhador observa de perto uma anémona junto a uma rocha coberta de esponjas vermelhas', 'Ein Taucher betrachtet eine Anemone neben einem Felsen voller roter Schwämme', 'Un buceador observa de cerca una anémona junto a una roca cubierta de esponjas rojas' ),
		'caption' => $L( 'Taking a closer look', 'Olhar de perto', 'Ganz nah dran', 'Mirar de cerca' ),
		'gallery' => 11,
	),
	'hero-shore-entry.jpg' => array(
		'title'   => 'Entrada pela costa',
		'source'  => $src( 'Poster.jpg' ) . ' (imagem principal do site atual)',
		'alt'     => $L( 'Two divers in full equipment smile at each other in shallow turquoise water', 'Dois mergulhadores equipados sorriem um para o outro em águas turquesa pouco profundas', 'Zwei Taucher in voller Ausrüstung lächeln sich im flachen, türkisfarbenen Wasser an', 'Dos buceadores equipados se sonríen en aguas turquesa poco profundas' ),
		'caption' => $L( 'Getting ready in the shallows', 'Preparação em águas pouco profundas', 'Vorbereitung im flachen Wasser', 'Preparándose en aguas poco profundas' ),
		'gallery' => 12,
	),
	'octopus.jpg'         => array(
		'title'   => 'Polvo',
		'source'  => $src( 'octo.jpg' ),
		'alt'     => $L( 'An octopus resting among rocks and pebbles', 'Um polvo entre rochas e seixos', 'Ein Oktopus zwischen Felsen und Kieseln', 'Un pulpo entre rocas y guijarros' ),
		'caption' => $L( 'Octopus among the rocks', 'Polvo entre as rochas', 'Oktopus zwischen den Felsen', 'Pulpo entre las rocas' ),
		'gallery' => 1,
	),
	'group-boat-cliffs.jpg' => array(
		'title'   => 'Grupo no barco junto às falésias',
		'source'  => $src( 'Groupe.jpg' ),
		'alt'     => $L( 'A smiling group of divers in wetsuits on a boat beneath sandstone cliffs', 'Um grupo sorridente de mergulhadores de fato no barco, por baixo das falésias', 'Eine lachende Gruppe von Tauchern im Neoprenanzug auf einem Boot unter den Klippen', 'Un grupo sonriente de buceadores en el barco, bajo los acantilados' ),
		'caption' => $L( 'On board beneath the cliffs', 'A bordo, junto às falésias', 'An Bord unter den Klippen', 'A bordo, junto a los acantilados' ),
		'gallery' => 10,
	),
	'sea-slug-white.jpg'  => array(
		'title'   => 'Lesma-do-mar',
		'source'  => $src( 'white.jpg' ),
		'alt'     => $L( 'A small white and orange sea slug on green algae', 'Uma pequena lesma-do-mar branca e laranja sobre algas verdes', 'Eine kleine weiß-orange Meeresschnecke auf grünen Algen', 'Una pequeña babosa de mar blanca y naranja sobre algas verdes' ),
		'caption' => $L( 'Sea slug on the algae', 'Lesma-do-mar sobre as algas', 'Meeresschnecke auf den Algen', 'Babosa de mar sobre las algas' ),
		'gallery' => 2,
		'review'  => 'Espécie não identificada (ficheiro white.jpg, alt original «Nudibranch»).',
	),
	'wreck-diver.jpg'     => array(
		'title'   => 'Mergulho em naufrágio',
		'source'  => $src( 'guy.jpg' ),
		'alt'     => $L( 'A diver with a torch swims through the structure of a wreck', 'Um mergulhador com lanterna percorre a estrutura de um naufrágio', 'Ein Taucher mit Lampe schwimmt durch die Struktur eines Wracks', 'Un buceador con linterna recorre la estructura de un pecio' ),
		'caption' => $L( 'Inside a wreck', 'No interior de um naufrágio', 'Im Inneren eines Wracks', 'En el interior de un pecio' ),
		'gallery' => 9,
		'review'  => 'Confirmar nome e localização do naufrágio antes de o mencionar.',
	),
	'lobster.jpg'         => array(
		'title'   => 'Lavagante',
		'source'  => $src( 'krb.jpg' ),
		'alt'     => $L( 'A lobster with large claws in a dark crevice', 'Um lavagante de grandes pinças numa fenda escura', 'Ein Hummer mit großen Scheren in einer dunklen Spalte', 'Un bogavante con grandes pinzas en una grieta oscura' ),
		'caption' => $L( 'A lobster at home', 'Um lavagante em casa', 'Ein Hummer zu Hause', 'Un bogavante en casa' ),
		'gallery' => 3,
		'review'  => 'Confirmar designação (lavagante) com a equipa.',
	),
	'cave-diver-light.jpg' => array(
		'title'   => 'Gruta subaquática',
		'source'  => $src( 'cave.jpg' ) . ' (alt original «Group divers», não corresponde à imagem)',
		'alt'     => $L( 'A diver lights up the rock walls of an underwater cave', 'Um mergulhador ilumina as paredes de uma gruta subaquática', 'Ein Taucher beleuchtet die Felswände einer Unterwasserhöhle', 'Un buceador ilumina las paredes de una cueva submarina' ),
		'caption' => $L( 'Torchlight on the rock', 'Luz sobre a rocha', 'Lampenlicht am Fels', 'Luz sobre la roca' ),
		'gallery' => 4,
	),
	'hermit-crab.jpg'     => array(
		'title'   => 'Caranguejo-eremita',
		'source'  => $src( 'hermitkrab.jpg' ),
		'alt'     => $L( 'A hermit crab in a shell covered with growth, on a rock', 'Um caranguejo-eremita numa concha coberta de vida, sobre uma rocha', 'Ein Einsiedlerkrebs in einem bewachsenen Schneckenhaus auf einem Felsen', 'Un cangrejo ermitaño en una concha cubierta de vida, sobre una roca' ),
		'caption' => $L( 'Hermit crab on the move', 'Caranguejo-eremita em movimento', 'Einsiedlerkrebs unterwegs', 'Cangrejo ermitaño en movimiento' ),
		'gallery' => 5,
	),
	'dive-centre-building.jpg' => array(
		'title'   => 'Centro de mergulho',
		'source'  => $src( 'divecenter.jpg' ),
		'alt'     => $L( 'The DiversCape dive centre building, with wetsuits drying outside below the cliff', 'O edifício do centro de mergulho DiversCape, com fatos a secar à porta, junto à falésia', 'Das Gebäude des Tauchzentrums DiversCape mit trocknenden Neoprenanzügen vor der Klippe', 'El edificio del centro de buceo DiversCape, con trajes secándose en la puerta, junto al acantilado' ),
		'caption' => $L( 'Our dive centre', 'O nosso centro de mergulho', 'Unser Tauchzentrum', 'Nuestro centro de buceo' ),
		'gallery' => 15,
	),
	'team-boat-harbour.jpg' => array(
		'title'   => 'A bordo no porto',
		'source'  => $src( 'GOPRO3.png' ) . ' (convertida de PNG 4088×2725)',
		'alt'     => $L( 'Six people in wetsuits pose on an orange boat in the harbour', 'Seis pessoas de fato de mergulho posam num barco laranja no porto', 'Sechs Personen in Neoprenanzügen posieren auf einem orangefarbenen Boot im Hafen', 'Seis personas con traje de buceo posan en un barco naranja en el puerto' ),
		'caption' => $L( 'Back in the harbour', 'De regresso ao porto', 'Zurück im Hafen', 'De vuelta en el puerto' ),
		'gallery' => 13,
		'review'  => 'Não identificar estas pessoas como equipa sem confirmação e autorização de imagem.',
	),
	'group-surface.jpg'   => array(
		'title'   => 'Grupo à superfície',
		'source'  => $src( 'group.jpg' ),
		'alt'     => $L( 'Five divers wave at the camera from calm turquoise water', 'Cinco mergulhadores acenam à câmara em água turquesa calma', 'Fünf Taucher winken im ruhigen türkisfarbenen Wasser in die Kamera', 'Cinco buceadores saludan a la cámara en agua turquesa tranquila' ),
		'caption' => $L( 'All smiles at the surface', 'Sorrisos à superfície', 'Gute Laune an der Oberfläche', 'Sonrisas en la superficie' ),
		'gallery' => 14,
	),
	'cave-opening-diver.jpg' => array(
		'title'   => 'Saída de gruta',
		'source'  => $src( 'dois.jpg' ),
		'alt'     => $L( 'A diver silhouetted against the blue light of a cave opening', 'A silhueta de um mergulhador contra a luz azul da abertura de uma gruta', 'Die Silhouette eines Tauchers vor dem blauen Licht eines Höhlenausgangs', 'La silueta de un buceador contra la luz azul de la boca de una cueva' ),
		'caption' => $L( 'Towards the light', 'Em direção à luz', 'Dem Licht entgegen', 'Hacia la luz' ),
		'gallery' => 8,
	),
	'sea-fan-reef.jpg'    => array(
		'title'   => 'Gorgónia',
		'source'  => $src( 'coral.jpg' ),
		'alt'     => $L( 'A yellow sea fan on a rocky seabed', 'Uma gorgónia amarela num fundo rochoso', 'Eine gelbe Gorgonie auf felsigem Meeresgrund', 'Una gorgonia amarilla sobre un fondo rocoso' ),
		'caption' => $L( 'Sea fan on the rocky seabed', 'Gorgónia no fundo rochoso', 'Gorgonie auf felsigem Grund', 'Gorgonia en el fondo rocoso' ),
		'gallery' => 16,
	),
	'sea-fan-shell.jpg'   => array(
		'title'   => 'Concha numa gorgónia',
		'source'  => $src( 'kr.jpg' ),
		'alt'     => $L( 'A small patterned shell resting on the branches of a sea fan', 'Uma pequena concha com padrões pousada nos ramos de uma gorgónia', 'Eine kleine gemusterte Schnecke auf den Ästen einer Gorgonie', 'Una pequeña concha con dibujos sobre las ramas de una gorgonia' ),
		'caption' => $L( 'Small details', 'Pequenos detalhes', 'Kleine Details', 'Pequeños detalles' ),
		'gallery' => 17,
	),
	'sea-slug-yellow.jpg' => array(
		'title'   => 'Lesma-do-mar amarela',
		'source'  => $src( 'nudi.JPG' ),
		'alt'     => $L( 'A yellow sea slug crawling over a reef covered in growth', 'Uma lesma-do-mar amarela sobre uma rocha coberta de vida', 'Eine gelbe Meeresschnecke auf einem bewachsenen Felsen', 'Una babosa de mar amarilla sobre una roca cubierta de vida' ),
		'caption' => $L( 'Colour on the reef', 'Cor na rocha', 'Farbe am Riff', 'Color en la roca' ),
		'gallery' => 7,
		'review'  => 'Espécie não identificada (ficheiro nudi.JPG).',
	),
	'scorpionfish.jpg'    => array(
		'title'   => 'Peixe camuflado',
		'source'  => $src( 'sto.jpg' ) . ' (alt original «stonefish»)',
		'alt'     => $L( 'A red, well-camouflaged fish resting on the reef', 'Um peixe vermelho, bem camuflado, pousado na rocha', 'Ein roter, gut getarnter Fisch auf dem Riff', 'Un pez rojo, bien camuflado, posado en la roca' ),
		'caption' => $L( 'Masters of camouflage', 'Mestres da camuflagem', 'Meister der Tarnung', 'Maestros del camuflaje' ),
		'gallery' => 6,
		'review'  => 'O site atual chama-lhe «stonefish». Aparenta ser um peixe-escorpião; confirmar a identificação antes de nomear.',
	),
	'sagres-islets-panorama.jpg' => array(
		'title'   => 'Ilhéus ao largo de Sagres',
		'source'  => $src( 'island.jpg' ) . ' (panorâmica 3888×845)',
		'alt'     => $L( 'Low rocky islets on a calm sea off the coast', 'Ilhéus rochosos baixos num mar calmo, junto à costa', 'Flache Felsinseln im ruhigen Meer vor der Küste', 'Islotes rocosos bajos en un mar en calma, junto a la costa' ),
		'caption' => $L( 'Islets off the coast', 'Ilhéus junto à costa', 'Inseln vor der Küste', 'Islotes junto a la costa' ),
		'gallery' => 18,
		'review'  => 'Possivelmente Ilhas do Martinhal (referidas na versão alemã). Confirmar antes de nomear.',
	),
	'dive-centre-map-wall.jpg' => array(
		'title'   => 'Parede com mapa',
		'source'  => $src( 'catwall.jpg' ),
		'alt'     => $L( 'A cat sits in front of a hand-drawn map on a wooden wall', 'Um gato sentado em frente a um mapa desenhado numa parede de madeira', 'Eine Katze sitzt vor einer handgezeichneten Karte an einer Holzwand', 'Un gato sentado frente a un mapa dibujado en una pared de madera' ),
		'caption' => $L( 'A curious visitor', 'Um visitante curioso', 'Ein neugieriger Besucher', 'Un visitante curioso' ),
		'gallery' => 19,
		'review'  => 'Confirmar se a fotografia foi tirada no centro e o que o mapa representa.',
	),
);
