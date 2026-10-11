<?php
/**
 * Catálogo central DiversCape — dados comerciais partilhados pelos quatro idiomas.
 *
 * Recolhido de https://diverscape.net/ (EN), /pt/, /de/ e /sp/ em 09/10/2026, a partir das páginas
 * efetivamente servidas (não de cópias em cache). 49 linhas de oferta na versão portuguesa;
 * 47 nas versões inglesa, alemã e espanhola (sem «Mergulho em Corrente» nem «Mergulho Embarcado»).
 *
 * Regras:
 * - price: valor numérico em EUR ou null. Nunca 0 para «sem preço».
 * - type: fixed (valor publicado) | on_request (sob consulta) | pending (conflito ou validação; mostra «Consultar»).
 * - status do item: source = publicado no site atual, sujeito a confirmação comercial; validated = aprovado.
 * - duration: só preenchida quando as fontes concordam; conflitos ficam em `conflicts` e a duração é ocultada.
 * - rows: identificadores das linhas de origem (ver research/inventory) — garantem que nenhuma variante se perde.
 *
 * Unidades de duração: half_day, day, days (qty), morning, half_morning.
 */

defined( 'ABSPATH' ) || exit;

$L = static fn( $en, $pt, $de, $es ) => compact( 'en', 'pt', 'de', 'es' );

return array(

	/* ------------------------------------------------------------------ Experiências (2 linhas) */
	array(
		'key'       => 'bubblemaker',
		'category'  => 'experiences',
		'order'     => 10,
		'image'     => 'group-surface.jpg',
		'featured'  => false,
		'audience'  => array( 'first-time', 'kids' ),
		'min_age'   => 8,
		'duration'  => array( 'unit' => 'half_day' ),
		'certification' => 'no',
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 85, 'type' => 'fixed', 'rows' => array( 'exp-1' ) ),
		),
		'notes'     => 'Fonte: «águas confinadas» (PT/ES) vs «in the pool» (EN/DE). Confirmar local (piscina ou águas confinadas naturais).',
		'conflicts' => array( 'Local da atividade: piscina (EN/DE) ou águas confinadas (PT/ES).' ),
	),
	array(
		'key'       => 'discover-scuba-diving',
		'category'  => 'experiences',
		'order'     => 20,
		'image'     => 'diver-anemone.jpg',
		'featured'  => true,
		'audience'  => array( 'first-time' ),
		'duration'  => array( 'unit' => 'half_day' ),
		'certification' => 'no',
		'variants'  => array(
			array( 'key' => 'shore', 'label' => $L( 'Shore dive', 'Saída da costa', 'Vom Ufer', 'Salida desde la costa' ), 'price' => 105, 'type' => 'fixed', 'rows' => array( 'exp-2' ) ),
			array( 'key' => 'boat', 'label' => $L( 'Boat dive', 'Saída de barco', 'Vom Boot', 'Salida en barco' ), 'price' => 115, 'type' => 'fixed', 'rows' => array( 'exp-2' ) ),
		),
		'notes'     => 'Nome na tabela: «Discover Scuba Experience»; nos textos: «PADI Discover Scuba Diving». Usar o nome oficial do programa.',
	),

	/* --------------------------------------------------------------- Formação de mergulho (6 linhas) */
	array(
		'key'       => 'scuba-diver',
		'category'  => 'courses',
		'order'     => 10,
		'image'     => 'group-boat-cliffs.jpg',
		'audience'  => array( 'first-time', 'kids' ),
		'junior'    => true,
		'duration'  => array( 'unit' => 'days', 'qty' => 2 ),
		'certification' => 'yes',
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 345, 'type' => 'fixed', 'rows' => array( 'cur-1' ) ),
		),
	),
	array(
		'key'       => 'open-water-diver',
		'category'  => 'courses',
		'order'     => 20,
		'image'     => 'hero-shore-entry.jpg',
		'featured'  => true,
		'audience'  => array( 'first-time', 'kids' ),
		'junior'    => true,
		'duration'  => array( 'unit' => 'days', 'qty' => 4 ),
		'certification' => 'yes',
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 575, 'type' => 'fixed', 'rows' => array( 'cur-2' ) ),
		),
		'notes'     => 'Texto de origem: «até 18 metros com um parceiro» — manter sujeito a revisão por instrutor.',
	),
	array(
		'key'       => 'adventure-diver',
		'category'  => 'courses',
		'order'     => 30,
		'image'     => 'cave-opening-diver.jpg',
		'audience'  => array( 'certified', 'kids' ),
		'junior'    => true,
		'duration'  => array( 'unit' => 'day' ),
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 325, 'type' => 'fixed', 'rows' => array( 'cur-3' ) ),
		),
	),
	array(
		'key'       => 'advanced-open-water',
		'category'  => 'courses',
		'order'     => 40,
		'image'     => 'wreck-diver.jpg',
		'featured'  => true,
		'audience'  => array( 'certified', 'kids' ),
		'junior'    => true,
		'duration'  => null,
		'certification' => 'yes',
		'variants'  => array(
			array( 'key' => 'full', 'label' => $L( 'Full course (5 adventure dives)', 'Curso completo (5 mergulhos de aventura)', 'Kompletter Kurs (5 Abenteuertauchgänge)', 'Curso completo (5 inmersiones de aventura)' ), 'price' => 425, 'type' => 'fixed', 'rows' => array( 'cur-4' ) ),
			array( 'key' => 'from-adventure', 'label' => $L( 'Completion for Adventure Divers', 'Conclusão para Adventure Divers', 'Abschluss für Adventure Diver', 'Finalización para Adventure Divers' ), 'price' => 225, 'type' => 'fixed', 'duration' => array( 'unit' => 'day' ), 'rows' => array( 'cur-5' ) ),
		),
		'conflicts' => array( 'Duração do curso completo: «2 dias» (PT, ES) vs «5 days» (EN, DE). Não confundir 5 mergulhos com 5 dias. Duração ocultada até confirmação.' ),
	),
	array(
		'key'       => 'rescue-diver',
		'category'  => 'courses',
		'order'     => 50,
		'image'     => 'group-boat-cliffs.jpg',
		'audience'  => array( 'certified' ),
		'duration'  => array( 'unit' => 'days', 'qty' => 3 ),
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 435, 'type' => 'fixed', 'rows' => array( 'cur-6' ) ),
		),
	),

	/* ------------------------------------------------------------------ Primeiros socorros (6 linhas) */
	array(
		'key'       => 'efr-primary-secondary',
		'category'  => 'first-aid',
		'order'     => 10,
		'image'     => 'team-boat-harbour.jpg',
		'audience'  => array( 'first-time', 'certified' ),
		'duration'  => null,
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'primary-secondary', 'label' => $L( 'Primary and Secondary Care', 'Cuidados Primários e Secundários', 'Primary and Secondary Care', 'Cuidados primarios y secundarios' ), 'price' => 165, 'type' => 'fixed', 'duration' => array( 'unit' => 'day' ), 'rows' => array( 'efr-1' ) ),
			array( 'key' => 'primary', 'label' => $L( 'Primary Care', 'Cuidados Primários', 'Primary Care', 'Cuidados primarios' ), 'price' => 130, 'type' => 'fixed', 'rows' => array( 'efr-2' ) ),
			array( 'key' => 'secondary', 'label' => $L( 'Secondary Care', 'Cuidados Secundários', 'Secondary Care', 'Cuidados secundarios' ), 'price' => 124, 'type' => 'fixed', 'duration' => array( 'unit' => 'half_day' ), 'rows' => array( 'efr-3' ) ),
			array( 'key' => 'primary-aed', 'label' => $L( 'Primary Care and AED', 'Cuidados Primários e DAE', 'Primary Care und AED', 'Cuidados primarios y DEA' ), 'price' => 140, 'type' => 'fixed', 'duration' => array( 'unit' => 'day' ), 'rows' => array( 'efr-5' ) ),
		),
		'notes'     => 'Na versão espanhola a linha «Cuidados Primários» está em português. Certificação EFR: confirmar emissão do cartão.',
	),
	array(
		'key'       => 'efr-care-for-children',
		'category'  => 'first-aid',
		'order'     => 20,
		'image'     => 'group-surface.jpg',
		'audience'  => array( 'first-time', 'certified' ),
		'duration'  => array( 'unit' => 'day' ),
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 224, 'type' => 'fixed', 'rows' => array( 'efr-4' ) ),
		),
	),
	array(
		'key'       => 'efr-instructor',
		'category'  => 'first-aid',
		'order'     => 30,
		'image'     => 'team-boat-harbour.jpg',
		'audience'  => array( 'certified' ),
		'duration'  => array( 'unit' => 'day' ),
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 525, 'type' => 'fixed', 'rows' => array( 'efr-6' ) ),
		),
		'notes'     => 'Duração «1 dia» para formação de instrutor publicada nas quatro versões; confirmar pré-requisitos com a equipa.',
	),

	/* ------------------------------------------------------------------- Especialidades (12 linhas) */
	array( 'key' => 'deep-diver', 'category' => 'specialties', 'order' => 10, 'image' => 'cave-opening-diver.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'days', 'qty' => 2 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 315, 'type' => 'fixed', 'rows' => array( 'esp-1' ) ) ) ),
	array( 'key' => 'enriched-air-nitrox', 'category' => 'specialties', 'order' => 20, 'image' => 'sea-fan-reef.jpg', 'featured' => false, 'audience' => array( 'certified' ), 'duration' => null, 'certification' => null,
		'variants' => array(
			array( 'key' => 'no-dives', 'label' => $L( 'Without dives', 'Sem mergulhos', 'Ohne Tauchgänge', 'Sin inmersiones' ), 'price' => 175, 'type' => 'fixed', 'rows' => array( 'esp-2' ) ),
			array( 'key' => 'two-dives', 'label' => $L( 'With 2 dives', 'Com 2 mergulhos', 'Mit 2 Tauchgängen', 'Con 2 inmersiones' ), 'price' => 215, 'type' => 'fixed', 'rows' => array( 'esp-2' ) ),
		),
		'conflicts' => array( 'Duração publicada como «1 dia/2 dias» sem indicar a que variante corresponde cada uma. Ocultada até confirmação.' ) ),
	array( 'key' => 'peak-performance-buoyancy', 'category' => 'specialties', 'order' => 30, 'image' => 'sea-fan-shell.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'day' ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 195, 'type' => 'fixed', 'rows' => array( 'esp-3' ) ) ) ),
	array( 'key' => 'equipment-specialist', 'category' => 'specialties', 'order' => 40, 'image' => 'dive-centre-building.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'day' ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 175, 'type' => 'fixed', 'rows' => array( 'esp-4' ) ) ) ),
	array( 'key' => 'night-diver', 'category' => 'specialties', 'order' => 50, 'image' => 'lobster.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'days', 'qty' => 2 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 275, 'type' => 'fixed', 'rows' => array( 'esp-5' ) ) ) ),
	array( 'key' => 'sidemount-diver', 'category' => 'specialties', 'order' => 60, 'image' => 'cave-diver-light.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'days', 'qty' => 2 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 345, 'type' => 'fixed', 'rows' => array( 'esp-6' ) ) ) ),
	array( 'key' => 'search-and-recovery', 'category' => 'specialties', 'order' => 70, 'image' => 'hermit-crab.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'days', 'qty' => 2 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 315, 'type' => 'fixed', 'rows' => array( 'esp-7' ) ) ) ),
	array( 'key' => 'drift-diver', 'category' => 'specialties', 'order' => 80, 'image' => 'group-boat-cliffs.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'day' ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 170, 'type' => 'fixed', 'rows' => array( 'esp-8' ) ) ),
		'notes' => 'Só na versão portuguesa («Mergulho em Corrente»). A descrição de origem mistura português e inglês («An exciting way of diving»). Preservado e traduzido; confirmar se continua em oferta.' ),
	array( 'key' => 'digital-underwater-photographer', 'category' => 'specialties', 'order' => 90, 'image' => 'octopus.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'day' ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 235, 'type' => 'fixed', 'rows' => array( 'esp-9' ) ) ) ),
	array( 'key' => 'emergency-oxygen-provider', 'category' => 'specialties', 'order' => 100, 'image' => 'team-boat-harbour.jpg', 'audience' => array( 'first-time', 'certified' ), 'duration' => array( 'unit' => 'half_day' ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 175, 'type' => 'fixed', 'rows' => array( 'esp-10' ) ) ),
		'notes' => 'Duração «meio dia» só na versão inglesa/alemã; PT e ES não indicam duração.' ),
	array( 'key' => 'wreck-diver', 'category' => 'specialties', 'order' => 110, 'image' => 'wreck-diver.jpg', 'featured' => false, 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'days', 'qty' => 2 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 345, 'type' => 'fixed', 'rows' => array( 'esp-11' ) ) ) ),
	array( 'key' => 'boat-diver', 'category' => 'specialties', 'order' => 120, 'image' => 'team-boat-harbour.jpg', 'audience' => array( 'certified' ), 'duration' => array( 'unit' => 'day' ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => 150, 'type' => 'fixed', 'rows' => array( 'esp-12' ) ) ),
		'notes' => 'Só na versão portuguesa («Mergulho Embarcado»). Confirmar se continua em oferta.' ),

	/* ----------------------------------------------------------------- Formação técnica (11 linhas) */
	array( 'key' => 'discover-tec', 'category' => 'technical', 'order' => 10, 'image' => 'cave-diver-light.jpg', 'audience' => array( 'certified', 'tec' ), 'duration' => array( 'unit' => 'half_day' ), 'certification' => 'no',
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => null, 'type' => 'on_request', 'rows' => array( 'tec-1' ) ) ) ),
	array( 'key' => 'tec-40', 'category' => 'technical', 'order' => 20, 'image' => 'cave-opening-diver.jpg', 'audience' => array( 'tec' ), 'duration' => array( 'unit' => 'days', 'qty' => 3 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => null, 'type' => 'on_request', 'rows' => array( 'tec-2' ) ) ) ),
	array( 'key' => 'tec-45', 'category' => 'technical', 'order' => 30, 'image' => 'wreck-diver.jpg', 'audience' => array( 'tec' ), 'duration' => null, 'certification' => null,
		'variants' => array(
			array( 'key' => 'tec-45', 'label' => $L( 'Tec 45', 'Tec 45', 'Tec 45', 'Tec 45' ), 'price' => null, 'type' => 'on_request', 'duration' => array( 'unit' => 'days', 'qty' => 3 ), 'rows' => array( 'tec-3' ) ),
			array( 'key' => 'tec-45-tmx', 'label' => $L( 'Tec 45 Trimix (TMx)', 'Tec 45 Trimix (TMx)', 'Tec 45 Trimix (TMx)', 'Tec 45 Trimix (TMx)' ), 'price' => null, 'type' => 'on_request', 'duration' => array( 'unit' => 'day' ), 'rows' => array( 'tec-4' ) ),
		) ),
	array( 'key' => 'tec-50', 'category' => 'technical', 'order' => 40, 'image' => 'wreck-diver.jpg', 'audience' => array( 'tec' ), 'duration' => null, 'certification' => null,
		'variants' => array(
			array( 'key' => 'tec-50', 'label' => $L( 'Tec 50', 'Tec 50', 'Tec 50', 'Tec 50' ), 'price' => null, 'type' => 'on_request', 'duration' => array( 'unit' => 'days', 'qty' => 4 ), 'rows' => array( 'tec-5' ) ),
			array( 'key' => 'tec-50-tmx', 'label' => $L( 'Tec 50 Trimix (TMx)', 'Tec 50 Trimix (TMx)', 'Tec 50 Trimix (TMx)', 'Tec 50 Trimix (TMx)' ), 'price' => null, 'type' => 'on_request', 'duration' => array( 'unit' => 'day' ), 'rows' => array( 'tec-6' ) ),
		) ),
	array( 'key' => 'tec-trimix-65', 'category' => 'technical', 'order' => 50, 'image' => 'cave-diver-light.jpg', 'audience' => array( 'tec' ), 'duration' => array( 'unit' => 'days', 'qty' => 4 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => null, 'type' => 'on_request', 'rows' => array( 'tec-7' ) ) ) ),
	array( 'key' => 'tec-trimix', 'category' => 'technical', 'order' => 60, 'image' => 'cave-opening-diver.jpg', 'audience' => array( 'tec' ), 'duration' => array( 'unit' => 'days', 'qty' => 4 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => null, 'type' => 'on_request', 'rows' => array( 'tec-8' ) ) ),
		'notes' => 'Texto de origem com linguagem absoluta («sem limite de tempo e sem limite de profundidade»). Não reproduzido; descrição a rever por instrutor TecRec.' ),
	array( 'key' => 'tec-sidemount', 'category' => 'technical', 'order' => 70, 'image' => 'cave-diver-light.jpg', 'audience' => array( 'tec' ), 'duration' => array( 'unit' => 'days', 'qty' => 3 ), 'certification' => null,
		'variants' => array( array( 'key' => 'standard', 'label' => null, 'price' => null, 'type' => 'on_request', 'rows' => array( 'tec-9' ) ) ) ),
	array( 'key' => 'gas-blender', 'category' => 'technical', 'order' => 80, 'image' => 'dive-centre-building.jpg', 'audience' => array( 'tec' ), 'duration' => null, 'certification' => null,
		'variants' => array(
			array( 'key' => 'nitrox', 'label' => $L( 'Gas Blender Nitrox', 'Gas Blender Nitrox', 'Gas Blender Nitrox', 'Gas Blender Nitrox' ), 'price' => null, 'type' => 'on_request', 'duration' => array( 'unit' => 'days', 'qty' => 2 ), 'rows' => array( 'tec-10' ) ),
			array( 'key' => 'trimix', 'label' => $L( 'Gas Blender Trimix', 'Gas Blender Trimix', 'Gas Blender Trimix', 'Gas Blender Trimix' ), 'price' => null, 'type' => 'on_request', 'duration' => array( 'unit' => 'days', 'qty' => 3 ), 'rows' => array( 'tec-11' ) ),
		) ),

	/* ---------------------------------------- Saídas e serviços para mergulhadores certificados (12 linhas) */
	array(
		'key'       => 'boat-dives',
		'category'  => 'certified',
		'order'     => 10,
		'image'     => 'group-boat-cliffs.jpg',
		'featured'  => true,
		'audience'  => array( 'certified' ),
		'duration'  => null,
		'certification' => 'no',
		'package'   => true,
		'variants'  => array(
			array( 'key' => '1-own', 'dives' => 1, 'equipment' => false, 'label' => $L( '1 boat dive · own equipment', '1 mergulho de barco · equipamento próprio', '1 Bootstauchgang · eigene Ausrüstung', '1 inmersión en barco · equipo propio' ), 'price' => 65, 'type' => 'fixed', 'rows' => array( 'sai-1' ) ),
			array( 'key' => '1-equip', 'dives' => 1, 'equipment' => true, 'label' => $L( '1 boat dive · equipment included', '1 mergulho de barco · equipamento incluído', '1 Bootstauchgang · Ausrüstung inklusive', '1 inmersión en barco · equipo incluido' ), 'price' => 75, 'type' => 'fixed', 'rows' => array( 'sai-2' ) ),
			array( 'key' => '2-own', 'dives' => 2, 'equipment' => false, 'label' => $L( '2 boat dives · own equipment', '2 mergulhos de barco · equipamento próprio', '2 Bootstauchgänge · eigene Ausrüstung', '2 inmersiones en barco · equipo propio' ), 'price' => 95, 'type' => 'fixed', 'duration' => array( 'unit' => 'morning' ), 'rows' => array( 'sai-3' ) ),
			array( 'key' => '2-equip', 'dives' => 2, 'equipment' => true, 'label' => $L( '2 boat dives · equipment included', '2 mergulhos de barco · equipamento incluído', '2 Bootstauchgänge · Ausrüstung inklusive', '2 inmersiones en barco · equipo incluido' ), 'price' => 125, 'type' => 'fixed', 'duration' => array( 'unit' => 'morning' ), 'rows' => array( 'sai-4' ) ),
			array( 'key' => '6-own', 'dives' => 6, 'equipment' => false, 'label' => $L( '6 boat dives · own equipment', '6 mergulhos de barco · equipamento próprio', '6 Bootstauchgänge · eigene Ausrüstung', '6 inmersiones en barco · equipo propio' ), 'price' => 270, 'type' => 'fixed', 'duration' => array( 'unit' => 'days', 'qty' => 3 ), 'rows' => array( 'sai-5' ) ),
			array( 'key' => '6-equip', 'dives' => 6, 'equipment' => true, 'label' => $L( '6 boat dives · equipment included', '6 mergulhos de barco · equipamento incluído', '6 Bootstauchgänge · Ausrüstung inklusive', '6 inmersiones en barco · equipo incluido' ), 'price' => 335, 'type' => 'fixed', 'duration' => array( 'unit' => 'days', 'qty' => 3 ), 'rows' => array( 'sai-6' ) ),
			array( 'key' => '10-own', 'dives' => 10, 'equipment' => false, 'label' => $L( '10 boat dives · own equipment', '10 mergulhos de barco · equipamento próprio', '10 Bootstauchgänge · eigene Ausrüstung', '10 inmersiones en barco · equipo propio' ), 'price' => 400, 'type' => 'fixed', 'rows' => array( 'sai-7' ) ),
			array( 'key' => '10-equip', 'dives' => 10, 'equipment' => true, 'label' => $L( '10 boat dives · equipment included', '10 mergulhos de barco · equipamento incluído', '10 Bootstauchgänge · Ausrüstung inklusive', '10 inmersiones en barco · equipo incluido' ), 'price' => 500, 'type' => 'fixed', 'rows' => array( 'sai-8' ) ),
		),
		'conflicts' => array(
			'1 mergulho: «Meia Manhã» (PT, ES) vs «Morning» (EN, DE). Duração ocultada.',
			'10 mergulhos sem equipamento: «5 Días» (ES) vs «3 dias» (PT, EN, DE). 10 mergulhos em 3 dias também merece confirmação. Duração ocultada para ambas as variantes.',
			'Nota Covid-19 (+5 € em atividades com aluguer de regulador) publicada nas quatro versões. Não aplicada no novo fluxo; decisão comercial pendente.',
		),
	),
	array(
		'key'       => 'equipment-rental',
		'category'  => 'certified',
		'order'     => 20,
		'image'     => 'dive-centre-building.jpg',
		'audience'  => array( 'certified' ),
		'duration'  => null,
		'certification' => 'no',
		'variants'  => array(
			array( 'key' => 'scuba', 'label' => $L( 'Diving equipment · 1 day', 'Equipamento de mergulho · 1 dia', 'Tauchausrüstung · 1 Tag', 'Equipo de buceo · 1 día' ), 'price' => 40, 'type' => 'fixed', 'rows' => array( 'sai-9' ) ),
			array( 'key' => 'snorkel', 'label' => $L( 'Snorkelling equipment · 1 day', 'Equipamento de snorkel · 1 dia', 'Schnorchelausrüstung · 1 Tag', 'Equipo de snorkel · 1 día' ), 'price' => 40, 'type' => 'fixed', 'rows' => array( 'sai-10' ) ),
		),
		'notes'     => 'Aluguer de snorkel é apenas aluguer de equipamento — não é uma excursão guiada. A meta descrição EN antiga refere «snorkeling tours»: não reproduzido. Aluguer de mergulho sem duração na versão EN/DE; «1 dia» em PT/ES.',
	),
	array(
		'key'       => 'scuba-review',
		'category'  => 'certified',
		'order'     => 30,
		'image'     => 'hero-shore-entry.jpg',
		'audience'  => array( 'certified' ),
		'duration'  => array( 'unit' => 'half_day' ),
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 55, 'type' => 'fixed', 'rows' => array( 'sai-11' ) ),
		),
	),
	array(
		'key'       => 'reactivate',
		'category'  => 'certified',
		'order'     => 40,
		'image'     => 'group-surface.jpg',
		'audience'  => array( 'certified' ),
		'duration'  => array( 'unit' => 'day' ),
		'certification' => null,
		'variants'  => array(
			array( 'key' => 'standard', 'label' => null, 'price' => 115, 'type' => 'fixed', 'rows' => array( 'sai-12' ) ),
		),
		'notes'     => 'PADI ReActivate inclui normalmente atualização de cartão; não afirmado no site atual — confirmar antes de mencionar.',
	),
);
