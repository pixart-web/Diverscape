<?php
/**
 * Perguntas frequentes. `publish` = resposta suportada pelo conteúdo do site atual;
 * `draft` = pergunta comum cuja resposta tem de ser dada pela DiversCape (fica no painel, não no site).
 * Formato: [chave, tema, estado, [pergunta por idioma], [resposta por idioma]]
 */

defined( 'ABSPATH' ) || exit;

$L       = static fn( $en, $pt, $de, $es ) => compact( 'en', 'pt', 'de', 'es' );
$pending = $L( 'Answer to be confirmed by the DiversCape team before publishing.', 'Resposta a confirmar pela equipa DiversCape antes de publicar.', 'Antwort vor der Veröffentlichung vom DiversCape-Team zu bestätigen.', 'Respuesta a confirmar por el equipo de DiversCape antes de publicar.' );

return array(
	array( 'where', 'general', 'publish',
		$L( 'Where is the dive centre?', 'Onde fica o centro de mergulho?', 'Wo befindet sich das Tauchzentrum?', '¿Dónde está el centro de buceo?' ),
		$L( 'At Porto da Baleeira in Sagres (8650-368 Sagres, Portugal), just a few steps from the sea.', 'No Porto da Baleeira, em Sagres (8650-368 Sagres, Portugal), a poucos passos do mar.', 'Am Porto da Baleeira in Sagres (8650-368 Sagres, Portugal), nur wenige Schritte vom Meer.', 'En el Porto da Baleeira de Sagres (8650-368 Sagres, Portugal), a pocos pasos del mar.' ) ),
	array( 'courses', 'general', 'publish',
		$L( 'Which courses do you offer?', 'Que cursos oferecem?', 'Welche Kurse bieten Sie an?', '¿Qué cursos ofrecen?' ),
		$L( 'PADI experiences (Bubblemaker and Discover Scuba Diving), courses from Scuba Diver to Rescue Diver, twelve specialties, Emergency First Response, and PADI TecRec technical courses. You can see them all in the course catalogue.', 'Experiências PADI (Bubblemaker e Discover Scuba Diving), cursos do Scuba Diver ao Rescue Diver, doze especialidades, Emergency First Response e cursos técnicos PADI TecRec. Pode vê-los todos no catálogo de cursos.', 'PADI-Erlebnisse (Bubblemaker und Discover Scuba Diving), Kurse vom Scuba Diver bis zum Rescue Diver, zwölf Spezialkurse, Emergency First Response und technische PADI-TecRec-Kurse. Alle finden Sie im Kurskatalog.', 'Experiencias PADI (Bubblemaker y Discover Scuba Diving), cursos desde Scuba Diver hasta Rescue Diver, doce especialidades, Emergency First Response y cursos técnicos PADI TecRec. Puede verlos todos en el catálogo de cursos.' ) ),
	array( 'where-dive', 'general', 'publish',
		$L( 'Where do you dive?', 'Onde mergulham?', 'Wo tauchen Sie?', '¿Dónde bucean?' ),
		$L( 'All our diving takes place in the Natural Park of the South-West Alentejo and Vicentine Coast, at sites that include caves, canyons, passages and wrecks.', 'Todos os nossos mergulhos decorrem no Parque Natural do Sudoeste Alentejano e Costa Vicentina, em locais que incluem grutas, desfiladeiros, passagens e naufrágios.', 'Wir tauchen ausschließlich im Naturpark Südwest-Alentejo und Costa Vicentina, an Plätzen mit Höhlen, Canyons, Durchgängen und Wracks.', 'Todas nuestras inmersiones se realizan en el Parque Natural del Suroeste Alentejano y Costa Vicentina, en lugares que incluyen cuevas, cañones, pasos y pecios.' ) ),
	array( 'bubblemaker-age', 'experiences', 'publish',
		$L( 'From what age can children try diving?', 'A partir de que idade podem as crianças experimentar?', 'Ab welchem Alter können Kinder tauchen?', '¿A partir de qué edad pueden probar los niños?' ),
		$L( 'Bubblemaker is for children from 8 years old. It takes half a day, in confined water.', 'O Bubblemaker é para crianças a partir dos 8 anos. Dura meio dia, em águas confinadas.', 'Bubblemaker ist für Kinder ab 8 Jahren. Es dauert einen halben Tag, in begrenztem Wasser.', 'Bubblemaker es para niños a partir de 8 años. Dura medio día, en aguas confinadas.' ) ),
	array( 'dsd-experience', 'experiences', 'publish',
		$L( 'Do I need experience to try Discover Scuba Diving?', 'Preciso de experiência para o Discover Scuba Diving?', 'Brauche ich Erfahrung für Discover Scuba Diving?', '¿Necesito experiencia para Discover Scuba Diving?' ),
		$L( 'No. You learn the basic skills with an instructor and then make a dive in the sea, all in half a day.', 'Não. Aprende as técnicas básicas com um instrutor e depois faz um mergulho no mar, tudo em meio dia.', 'Nein. Sie lernen die Grundfertigkeiten mit einem Instruktor und tauchen dann im Meer — alles an einem halben Tag.', 'No. Aprende las técnicas básicas con un instructor y después hace una inmersión en el mar, todo en medio día.' ) ),
	array( 'rental', 'certified', 'publish',
		$L( 'Can I rent equipment?', 'Posso alugar equipamento?', 'Kann ich Ausrüstung leihen?', '¿Puedo alquilar equipo?' ),
		$L( 'Yes. We rent diving equipment and snorkelling equipment by the day, and our boat dive packages are available with equipment included.', 'Sim. Alugamos equipamento de mergulho e de snorkel por dia, e os pacotes de saídas de barco estão disponíveis com equipamento incluído.', 'Ja. Wir verleihen Tauch- und Schnorchelausrüstung tageweise, und unsere Bootstauchpakete gibt es auch inklusive Ausrüstung.', 'Sí. Alquilamos equipo de buceo y de snorkel por días, y los paquetes de salidas en barco están disponibles con equipo incluido.' ) ),
	array( 'servicing', 'certified', 'publish',
		$L( 'Do you service diving equipment?', 'Fazem manutenção de equipamento?', 'Warten Sie Tauchausrüstung?', '¿Hacen mantenimiento de equipo?' ),
		$L( 'Yes, we offer equipment servicing at an affordable price. Tell us what you need and we will reply with the details.', 'Sim, fazemos manutenção de equipamento a um preço acessível. Diga-nos o que precisa e respondemos com os pormenores.', 'Ja, wir bieten Ausrüstungsservice zu einem günstigen Preis. Sagen Sie uns, was Sie brauchen — wir melden uns mit den Einzelheiten.', 'Sí, ofrecemos mantenimiento de equipo a un precio accesible. Díganos qué necesita y le responderemos con los detalles.' ) ),
	array( 'guide', 'certified', 'publish',
		$L( 'Is there a guide on the dives?', 'Há um guia nos mergulhos?', 'Gibt es bei den Tauchgängen einen Guide?', '¿Hay un guía en las inmersiones?' ),
		$L( 'Yes. Our dives are accompanied by a guide or an instructor.', 'Sim. Os nossos mergulhos são acompanhados por um guia ou instrutor.', 'Ja. Unsere Tauchgänge werden von einem Guide oder Instruktor begleitet.', 'Sí. Nuestras inmersiones van acompañadas por un guía o instructor.' ) ),
	array( 'tec-price', 'technical', 'publish',
		$L( 'How much do technical courses cost?', 'Quanto custam os cursos técnicos?', 'Was kosten technische Kurse?', '¿Cuánto cuestan los cursos técnicos?' ),
		$L( 'Technical courses are priced on request. Send us an information request with your experience and the course you are interested in.', 'Os cursos técnicos têm preço sob consulta. Envie-nos um pedido de informações com a sua experiência e o curso que lhe interessa.', 'Technische Kurse gibt es auf Anfrage. Senden Sie uns eine Anfrage mit Ihrer Erfahrung und dem gewünschten Kurs.', 'Los cursos técnicos tienen precio bajo consulta. Envíenos una solicitud con su experiencia y el curso que le interesa.' ) ),
	array( 'request-booking', 'request', 'publish',
		$L( 'Is my place booked when I send a request?', 'A vaga fica reservada quando envio um pedido?', 'Ist mein Platz mit der Anfrage gebucht?', '¿Mi plaza queda reservada al enviar la solicitud?' ),
		$L( 'No. The form sends an availability request. Your place is confirmed only when our team replies.', 'Não. O formulário envia um pedido de disponibilidade. A vaga só fica confirmada quando a equipa responder.', 'Nein. Das Formular sendet eine Verfügbarkeitsanfrage. Ihr Platz ist erst mit der Antwort unseres Teams bestätigt.', 'No. El formulario envía una solicitud de disponibilidad. Su plaza solo queda confirmada cuando el equipo responda.' ) ),
	array( 'request-reply', 'request', 'publish',
		$L( 'How quickly do you reply?', 'Quanto tempo demoram a responder?', 'Wie schnell antworten Sie?', '¿Cuánto tardan en responder?' ),
		$L( 'We aim to reply to emails within 24 hours. You can also call us on +351 965 559 073 (call to a Portuguese mobile network).', 'Procuramos responder a emails em 24 horas. Pode também ligar para o +351 965 559 073 (chamada para a rede móvel nacional).', 'Wir beantworten E-Mails in der Regel innerhalb von 24 Stunden. Sie können uns auch unter +351 965 559 073 anrufen (Anruf in ein portugiesisches Mobilfunknetz).', 'Intentamos responder a los correos en 24 horas. También puede llamarnos al +351 965 559 073 (llamada a una red móvil portuguesa).' ) ),
	array( 'request-prices', 'request', 'publish',
		$L( 'Are the prices on the website final?', 'Os preços do site são finais?', 'Sind die Preise auf der Website verbindlich?', '¿Los precios del sitio son definitivos?' ),
		$L( 'They are the prices published on our current website. Our team confirms the price when replying to your request.', 'São os preços publicados no nosso site atual. A equipa confirma o preço quando responde ao seu pedido.', 'Es sind die auf unserer bisherigen Website veröffentlichten Preise. Unser Team bestätigt den Preis in der Antwort auf Ihre Anfrage.', 'Son los precios publicados en nuestro sitio actual. El equipo confirma el precio al responder a su solicitud.' ) ),

	/* A validar — rascunhos no painel */
	array( 'languages', 'general', 'draft', $L( 'Which languages does the team speak?', 'Que idiomas fala a equipa?', 'Welche Sprachen spricht das Team?', '¿Qué idiomas habla el equipo?' ), $pending ),
	array( 'season', 'general', 'draft', $L( 'Do you dive all year round?', 'Mergulham todo o ano?', 'Tauchen Sie das ganze Jahr?', '¿Bucean todo el año?' ), $pending ),
	array( 'junior-age', 'courses', 'draft', $L( 'What are the ages for Junior courses?', 'Quais são as idades para os cursos Junior?', 'Welches Alter gilt für Junior-Kurse?', '¿Qué edades hay para los cursos Junior?' ), $pending ),
	array( 'medical', 'courses', 'draft', $L( 'Do I need a medical statement?', 'Preciso de declaração médica?', 'Brauche ich ein ärztliches Attest?', '¿Necesito una declaración médica?' ), $pending ),
	array( 'cancellation', 'request', 'draft', $L( 'What is the cancellation policy?', 'Qual é a política de cancelamento?', 'Wie sind die Stornobedingungen?', '¿Cuál es la política de cancelación?' ), $pending ),
	array( 'extra-fees', 'certified', 'draft', $L( 'Are there any extra fees, for example for regulator rental?', 'Há taxas extra, por exemplo pelo aluguer do regulador?', 'Gibt es Zusatzkosten, zum Beispiel für den Atemreglerverleih?', '¿Hay tasas extra, por ejemplo por el alquiler del regulador?' ), $pending ),
	array( 'whats-included', 'courses', 'draft', $L( 'What is included in course prices (equipment, materials, certification)?', 'O que inclui o preço dos cursos (equipamento, materiais, certificação)?', 'Was ist im Kurspreis enthalten (Ausrüstung, Material, Zertifizierung)?', '¿Qué incluye el precio de los cursos (equipo, materiales, certificación)?' ), $pending ),
);
