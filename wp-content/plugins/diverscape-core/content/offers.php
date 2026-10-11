<?php
/**
 * Textos das páginas de curso/experiência nos quatro idiomas.
 * Formato por idioma: [ título, slug, resumo, para quem é, [parágrafos], [[pergunta, resposta], …] ]
 *
 * Reescritos a partir das descrições do site atual (EN, PT, DE, ES). Não acrescentam durações,
 * idades, condições, equipamento incluído ou promessas sem suporte; preços e durações vêm do catálogo.
 * Descrições técnicas a rever por um instrutor antes do lançamento (ver docs/dados-a-validar.md).
 */

defined( 'ABSPATH' ) || exit;

return array(

	'bubblemaker' => array(
		'en' => array( 'PADI Bubblemaker', 'bubblemaker', 'A first taste of breathing underwater for children aged 8 and over, in calm, confined water.', 'Children from 8 years old trying scuba for the first time.', array(
			'Bubblemaker is a half-day introduction to scuba diving designed for children aged 8 and over. It takes place in confined water, where young divers can get used to the equipment and to breathing underwater with an instructor by their side.',
			'Send us a request with your preferred date and our team will confirm the details and what to bring.',
		) ),
		'pt' => array( 'PADI Bubblemaker', 'bubblemaker', 'Uma primeira experiência de respirar debaixo de água para crianças a partir dos 8 anos, em águas confinadas e calmas.', 'Crianças a partir dos 8 anos que experimentam o mergulho pela primeira vez.', array(
			'O Bubblemaker é uma introdução ao mergulho com a duração de meio dia, pensada para crianças a partir dos 8 anos. Decorre em águas confinadas, onde os mais novos se habituam ao equipamento e a respirar debaixo de água com um instrutor ao lado.',
			'Envie-nos um pedido com a data preferida e a equipa confirma os pormenores e o que deve trazer.',
		) ),
		'de' => array( 'PADI Bubblemaker', 'bubblemaker', 'Das erste Mal unter Wasser atmen — für Kinder ab 8 Jahren, in ruhigem, begrenztem Wasser.', 'Kinder ab 8 Jahren, die zum ersten Mal tauchen möchten.', array(
			'Bubblemaker ist eine halbtägige Einführung ins Tauchen für Kinder ab 8 Jahren. Sie findet in begrenztem Wasser statt, wo sich die jungen Taucher mit einem Instruktor an ihrer Seite an die Ausrüstung und das Atmen unter Wasser gewöhnen.',
			'Senden Sie uns eine Anfrage mit Ihrem Wunschtermin. Unser Team bestätigt die Einzelheiten und was mitzubringen ist.',
		) ),
		'es' => array( 'PADI Bubblemaker', 'bubblemaker', 'La primera vez respirando bajo el agua para niños a partir de 8 años, en aguas confinadas y tranquilas.', 'Niños a partir de 8 años que prueban el buceo por primera vez.', array(
			'Bubblemaker es una introducción al buceo de medio día pensada para niños a partir de 8 años. Se realiza en aguas confinadas, donde los más pequeños se acostumbran al equipo y a respirar bajo el agua con un instructor a su lado.',
			'Envíenos una solicitud con la fecha preferida y el equipo le confirmará los detalles y qué debe traer.',
		) ),
	),

	'discover-scuba-diving' => array(
		'en' => array( 'PADI Discover Scuba Diving', 'discover-scuba-diving', 'Learn the basic skills and enjoy your first dive in the sea — all in half a day.', 'Anyone who has never dived and wants to try it.', array(
			'Discover Scuba Diving is the easiest way to find out whether diving is for you. In half a day you learn the basic scuba skills with an instructor, then put them into practice on a dive in the sea.',
			'Choose to start from the shore or from the boat. It is an experience, not a certification — and a natural first step if you later want to become an Open Water Diver.',
		), array(
			array( 'Do I need any diving experience?', 'No. Discover Scuba Diving is designed for people who have never dived: you learn the basic skills before your dive in the sea.' ),
			array( 'Will I get a certification?', 'No. It is an experience. If you enjoy it, ask us about the Open Water Diver course.' ),
		) ),
		'pt' => array( 'PADI Discover Scuba Diving (batismo de mergulho)', 'batismo-de-mergulho', 'Aprenda as técnicas básicas e faça o seu primeiro mergulho no mar — tudo em meio dia.', 'Quem nunca mergulhou e quer experimentar.', array(
			'O Discover Scuba Diving é a forma mais simples de descobrir se o mergulho é para si. Em meio dia aprende as técnicas básicas com um instrutor e põe-nas em prática num mergulho no mar.',
			'Pode escolher a saída da costa ou de barco. É uma experiência, não uma certificação — e um primeiro passo natural se mais tarde quiser tornar-se Open Water Diver.',
		), array(
			array( 'Preciso de ter experiência de mergulho?', 'Não. O Discover Scuba Diving foi pensado para quem nunca mergulhou: aprende as técnicas básicas antes do mergulho no mar.' ),
			array( 'Fico com uma certificação?', 'Não. É uma experiência. Se gostar, pergunte-nos pelo curso Open Water Diver.' ),
		) ),
		'de' => array( 'PADI Discover Scuba Diving (Schnuppertauchen)', 'schnuppertauchen', 'Lernen Sie die Grundlagen und erleben Sie Ihren ersten Tauchgang im Meer — an einem halben Tag.', 'Alle, die noch nie getaucht sind und es ausprobieren möchten.', array(
			'Discover Scuba Diving ist der einfachste Weg herauszufinden, ob Tauchen etwas für Sie ist. An einem halben Tag lernen Sie mit einem Instruktor die Grundfertigkeiten und setzen sie bei einem Tauchgang im Meer um.',
			'Sie starten wahlweise vom Ufer oder vom Boot. Es ist ein Erlebnis, keine Zertifizierung — und ein natürlicher erster Schritt, wenn Sie später Open Water Diver werden möchten.',
		), array(
			array( 'Brauche ich Taucherfahrung?', 'Nein. Discover Scuba Diving ist für Menschen gedacht, die noch nie getaucht sind: Sie lernen die Grundfertigkeiten vor dem Tauchgang im Meer.' ),
			array( 'Bekomme ich eine Zertifizierung?', 'Nein. Es ist ein Erlebnis. Wenn es Ihnen gefällt, fragen Sie uns nach dem Open-Water-Diver-Kurs.' ),
		) ),
		'es' => array( 'PADI Discover Scuba Diving (bautismo de buceo)', 'bautismo-de-buceo', 'Aprenda las técnicas básicas y disfrute de su primera inmersión en el mar — todo en medio día.', 'Quien nunca ha buceado y quiere probarlo.', array(
			'Discover Scuba Diving es la forma más sencilla de descubrir si el buceo es para usted. En medio día aprende las técnicas básicas con un instructor y las pone en práctica en una inmersión en el mar.',
			'Puede elegir la salida desde la costa o en barco. Es una experiencia, no una certificación — y un primer paso natural si más adelante quiere convertirse en Open Water Diver.',
		), array(
			array( '¿Necesito experiencia de buceo?', 'No. Discover Scuba Diving está pensado para quien nunca ha buceado: aprende las técnicas básicas antes de la inmersión en el mar.' ),
			array( '¿Obtengo una certificación?', 'No. Es una experiencia. Si le gusta, pregúntenos por el curso Open Water Diver.' ),
		) ),
	),

	'scuba-diver' => array(
		'en' => array( 'PADI Scuba Diver', 'scuba-diver', 'Learn the fundamentals of scuba diving and earn your first certification, for diving under professional supervision.', 'Beginners who want a first certification. Also available as Junior.', array(
			'Over two days, the Scuba Diver course teaches you the fundamentals of scuba diving and earns you your first certification, which lets you dive under the supervision of a dive professional.',
			'Want to become a fully independent diver later on? Ask us how to continue to Open Water Diver.',
		) ),
		'pt' => array( 'PADI Scuba Diver', 'scuba-diver', 'Aprenda os fundamentos do mergulho autónomo e obtenha a sua primeira certificação, para mergulhar sob supervisão.', 'Principiantes que querem uma primeira certificação. Também disponível na versão Junior.', array(
			'Em dois dias, o curso Scuba Diver ensina-lhe os fundamentos do mergulho autónomo e dá-lhe a primeira certificação, que permite mergulhar sob a supervisão de um profissional de mergulho.',
			'Quer mais tarde tornar-se um mergulhador independente? Pergunte-nos como continuar até ao Open Water Diver.',
		) ),
		'de' => array( 'PADI Scuba Diver', 'scuba-diver', 'Lernen Sie die Grundlagen des Gerätetauchens und erhalten Sie Ihre erste Zertifizierung — für Tauchgänge unter professioneller Aufsicht.', 'Einsteiger, die eine erste Zertifizierung möchten. Auch als Junior-Version.', array(
			'In zwei Tagen vermittelt der Scuba-Diver-Kurs die Grundlagen des Gerätetauchens. Sie erhalten Ihre erste Zertifizierung, mit der Sie unter Aufsicht eines Tauchprofis tauchen können.',
			'Sie möchten später selbstständig tauchen? Fragen Sie uns, wie Sie zum Open Water Diver weitermachen.',
		) ),
		'es' => array( 'PADI Scuba Diver', 'scuba-diver', 'Aprenda los fundamentos del buceo autónomo y obtenga su primera certificación, para bucear bajo supervisión.', 'Principiantes que quieren una primera certificación. También en versión Junior.', array(
			'En dos días, el curso Scuba Diver le enseña los fundamentos del buceo autónomo y le da su primera certificación, que le permite bucear bajo la supervisión de un profesional.',
			'¿Quiere convertirse más adelante en un buceador independiente? Pregúntenos cómo continuar hasta Open Water Diver.',
		) ),
	),

	'open-water-diver' => array(
		'en' => array( 'PADI Open Water Diver', 'open-water-diver', 'Earn a lifetime certification and dive with a buddy to 18 metres, anywhere in the world.', 'Anyone who wants to become an independent certified diver. Also available as Junior.', array(
			'Open Water Diver is the step from trying diving to becoming a certified diver. Over four days you learn the theory, practise the skills and complete your training dives with our instructors.',
			'The certification lasts for life and lets you plan and make dives to 18 metres with a buddy, wherever you travel.',
		) ),
		'pt' => array( 'PADI Open Water Diver', 'open-water-diver', 'Obtenha uma certificação vitalícia e mergulhe com um parceiro até aos 18 metros, em qualquer parte do mundo.', 'Quem quer tornar-se um mergulhador certificado e independente. Também disponível na versão Junior.', array(
			'O Open Water Diver é o passo entre experimentar o mergulho e tornar-se mergulhador certificado. Em quatro dias aprende a teoria, pratica as técnicas e faz os mergulhos de formação com os nossos instrutores.',
			'A certificação é vitalícia e permite planear e realizar mergulhos até aos 18 metros com um parceiro, onde quer que viaje.',
		) ),
		'de' => array( 'PADI Open Water Diver', 'open-water-diver', 'Eine Zertifizierung fürs Leben: Tauchen Sie mit einem Buddy bis 18 Meter, überall auf der Welt.', 'Alle, die selbstständige, zertifizierte Taucher werden möchten. Auch als Junior-Version.', array(
			'Open Water Diver ist der Schritt vom Ausprobieren zum zertifizierten Taucher. In vier Tagen lernen Sie die Theorie, üben die Fertigkeiten und absolvieren Ihre Ausbildungstauchgänge mit unseren Instruktoren.',
			'Die Zertifizierung gilt ein Leben lang und erlaubt Ihnen, mit einem Buddy Tauchgänge bis 18 Meter zu planen und durchzuführen — wohin Sie auch reisen.',
		) ),
		'es' => array( 'PADI Open Water Diver', 'open-water-diver', 'Una certificación para toda la vida: bucee con un compañero hasta 18 metros, en cualquier parte del mundo.', 'Quien quiere convertirse en buceador certificado e independiente. También en versión Junior.', array(
			'Open Water Diver es el paso entre probar el buceo y convertirse en buceador certificado. En cuatro días aprende la teoría, practica las técnicas y realiza las inmersiones de formación con nuestros instructores.',
			'La certificación es para toda la vida y le permite planificar y realizar inmersiones hasta 18 metros con un compañero, allá donde viaje.',
		) ),
	),

	'adventure-diver' => array(
		'en' => array( 'PADI Adventure Diver', 'adventure-diver', 'Try adventure dives and discover where diving can take you — in one day.', 'Divers who want to try new kinds of diving. Also available as Junior.', array(
			'Adventure Diver lets you sample different kinds of diving through adventure dives, and see where diving can take you next — all in one day.',
			'Ask us which adventure dives are possible on your dates: the options depend on sea conditions and on your experience.',
		) ),
		'pt' => array( 'PADI Adventure Diver', 'adventure-diver', 'Experimente mergulhos de aventura e descubra até onde o mergulho o pode levar — num só dia.', 'Mergulhadores que querem experimentar novos tipos de mergulho. Também na versão Junior.', array(
			'O Adventure Diver permite experimentar diferentes tipos de mergulho através de mergulhos de aventura e perceber até onde o mergulho o pode levar — tudo num dia.',
			'Pergunte-nos que mergulhos de aventura são possíveis nas suas datas: as opções dependem das condições do mar e da sua experiência.',
		) ),
		'de' => array( 'PADI Adventure Diver', 'adventure-diver', 'Probieren Sie Abenteuertauchgänge aus und entdecken Sie, wohin Tauchen Sie führen kann — an einem Tag.', 'Taucher, die neue Arten des Tauchens ausprobieren möchten. Auch als Junior-Version.', array(
			'Beim Adventure Diver lernen Sie in Abenteuertauchgängen verschiedene Arten des Tauchens kennen und sehen, wohin Sie das Tauchen als Nächstes führen kann — alles an einem Tag.',
			'Fragen Sie uns, welche Abenteuertauchgänge an Ihren Terminen möglich sind: Die Auswahl hängt von den Bedingungen und Ihrer Erfahrung ab.',
		) ),
		'es' => array( 'PADI Adventure Diver', 'adventure-diver', 'Pruebe inmersiones de aventura y descubra hasta dónde le puede llevar el buceo — en un día.', 'Buceadores que quieren probar nuevos tipos de buceo. También en versión Junior.', array(
			'Adventure Diver le permite probar distintos tipos de buceo mediante inmersiones de aventura y ver hasta dónde le puede llevar el buceo — todo en un día.',
			'Pregúntenos qué inmersiones de aventura son posibles en sus fechas: las opciones dependen de las condiciones del mar y de su experiencia.',
		) ),
	),

	'advanced-open-water' => array(
		'en' => array( 'PADI Advanced Open Water Diver', 'advanced-open-water-diver', 'Complete five adventure dives and qualify for the next level of certification.', 'Certified divers ready for the next level. Also available as Junior.', array(
			'Advanced Open Water Diver builds on your first certification through five adventure dives and qualifies you for the next level.',
			'Already an Adventure Diver? You can complete your Advanced with us in one day — choose the completion option below.',
		) ),
		'pt' => array( 'PADI Advanced Open Water Diver', 'advanced-open-water-diver', 'Faça cinco mergulhos de aventura e qualifique-se para o nível seguinte de certificação.', 'Mergulhadores certificados prontos para o nível seguinte. Também na versão Junior.', array(
			'O Advanced Open Water Diver parte da sua primeira certificação com cinco mergulhos de aventura e qualifica-o para o nível seguinte.',
			'Já é Adventure Diver? Pode concluir o Advanced connosco num só dia — escolha a opção de conclusão abaixo.',
		) ),
		'de' => array( 'PADI Advanced Open Water Diver', 'advanced-open-water-diver', 'Absolvieren Sie fünf Abenteuertauchgänge und qualifizieren Sie sich für die nächste Stufe.', 'Zertifizierte Taucher, bereit für die nächste Stufe. Auch als Junior-Version.', array(
			'Der Advanced Open Water Diver baut mit fünf Abenteuertauchgängen auf Ihrer ersten Zertifizierung auf und qualifiziert Sie für die nächste Stufe.',
			'Sie sind bereits Adventure Diver? Dann schließen Sie Ihren Advanced bei uns an einem Tag ab — wählen Sie unten die Abschlussoption.',
		) ),
		'es' => array( 'PADI Advanced Open Water Diver', 'advanced-open-water-diver', 'Realice cinco inmersiones de aventura y califíquese para el siguiente nivel de certificación.', 'Buceadores certificados listos para el siguiente nivel. También en versión Junior.', array(
			'Advanced Open Water Diver parte de su primera certificación con cinco inmersiones de aventura y le califica para el siguiente nivel.',
			'¿Ya es Adventure Diver? Puede completar el Advanced con nosotros en un solo día — elija abajo la opción de finalización.',
		) ),
	),

	'rescue-diver' => array(
		'en' => array( 'PADI Rescue Diver', 'rescue-diver', 'Learn how to respond in an emergency — a serious course that is also great fun.', 'Certified divers who want to look after themselves and their buddies.', array(
			'Over three days, Rescue Diver teaches you how to act when something goes wrong in or near the water. It is a serious course, but also an extremely enjoyable one — and it makes you a more confident buddy.',
		) ),
		'pt' => array( 'PADI Rescue Diver', 'rescue-diver', 'Aprenda a agir numa situação de emergência — um curso sério, mas extremamente divertido.', 'Mergulhadores certificados que querem cuidar de si e dos seus parceiros.', array(
			'Em três dias, o Rescue Diver ensina-o a agir quando algo corre mal dentro ou perto da água. É um curso sério, mas também extremamente divertido — e faz de si um parceiro mais confiante.',
		) ),
		'de' => array( 'PADI Rescue Diver', 'rescue-diver', 'Lernen Sie, im Notfall richtig zu handeln — ein ernsthafter Kurs, der trotzdem viel Spaß macht.', 'Zertifizierte Taucher, die auf sich und ihre Buddys achten möchten.', array(
			'In drei Tagen lernen Sie beim Rescue Diver, wie Sie handeln, wenn im oder am Wasser etwas schiefgeht. Ein ernsthafter Kurs, der trotzdem großen Spaß macht — und Sie zu einem souveräneren Buddy macht.',
		) ),
		'es' => array( 'PADI Rescue Diver', 'rescue-diver', 'Aprenda a actuar en una emergencia — un curso serio, pero muy divertido.', 'Buceadores certificados que quieren cuidar de sí mismos y de sus compañeros.', array(
			'En tres días, Rescue Diver le enseña a actuar cuando algo sale mal en el agua o cerca de ella. Es un curso serio, pero también muy divertido — y le convierte en un compañero más seguro de sí mismo.',
		) ),
	),

	'efr-primary-secondary' => array(
		'en' => array( 'Emergency First Response — Primary and Secondary Care', 'efr-primary-secondary-care', 'Learn what to do in an emergency: primary care, secondary care, both in one day, or primary care with an AED.', 'Anyone who wants to be ready to help in an emergency.', array(
			'Primary care covers the care that rescue teams give in an emergency. Secondary care builds on that knowledge for situations where help is not immediately available.',
			'Choose the full day with primary and secondary care, either part on its own, or primary care together with the use of an AED (automated external defibrillator).',
		) ),
		'pt' => array( 'Emergency First Response — Cuidados Primários e Secundários', 'primeiros-socorros-efr', 'Aprenda a agir numa emergência: cuidados primários, secundários, ambos num dia, ou primários com DAE.', 'Quem quer estar preparado para ajudar numa emergência.', array(
			'Os cuidados primários abrangem o que as equipas de socorro fazem numa situação de emergência. Os cuidados secundários consolidam esses conhecimentos para situações em que o socorro não está disponível de imediato.',
			'Escolha o dia completo com cuidados primários e secundários, cada parte em separado, ou cuidados primários com utilização do DAE (desfibrilhador automático externo).',
		) ),
		'de' => array( 'Emergency First Response — Primary and Secondary Care', 'erste-hilfe-efr', 'Richtig handeln im Notfall: Primary Care, Secondary Care, beides an einem Tag oder Primary Care mit AED.', 'Alle, die im Notfall helfen können möchten.', array(
			'Primary Care umfasst die Maßnahmen, die Rettungskräfte im Notfall ergreifen. Secondary Care vertieft dieses Wissen für Situationen, in denen nicht sofort Hilfe verfügbar ist.',
			'Wählen Sie den ganzen Tag mit Primary und Secondary Care, einen Teil einzeln oder Primary Care mit dem Einsatz eines AED (automatisierter externer Defibrillator).',
		) ),
		'es' => array( 'Emergency First Response — Cuidados primarios y secundarios', 'primeros-auxilios-efr', 'Aprenda a actuar en una emergencia: cuidados primarios, secundarios, ambos en un día, o primarios con DEA.', 'Quien quiere estar preparado para ayudar en una emergencia.', array(
			'Los cuidados primarios abarcan lo que hacen los equipos de rescate en una situación de emergencia. Los cuidados secundarios consolidan esos conocimientos para situaciones en las que la ayuda no está disponible de inmediato.',
			'Elija el día completo con cuidados primarios y secundarios, cada parte por separado, o cuidados primarios con el uso del DEA (desfibrilador externo automático).',
		) ),
	),

	'efr-care-for-children' => array(
		'en' => array( 'Emergency First Response — Care for Children', 'efr-care-for-children', 'A day dedicated to emergency care for the little ones.', 'Parents, carers and anyone who looks after children.', array(
			'A one-day course to learn how to care for children in an emergency, covering primary and secondary care adapted to the youngest.',
		) ),
		'pt' => array( 'Emergency First Response — Cuidados para Crianças', 'primeiros-socorros-criancas', 'Um dia dedicado aos cuidados de emergência para os mais pequenos.', 'Pais, cuidadores e quem trabalha com crianças.', array(
			'Um curso de um dia para aprender a cuidar dos mais pequenos numa emergência, com cuidados primários e secundários adaptados às crianças.',
		) ),
		'de' => array( 'Emergency First Response — Care for Children', 'erste-hilfe-kinder', 'Ein Tag rund um die Notfallversorgung der Kleinsten.', 'Eltern, Betreuungspersonen und alle, die mit Kindern arbeiten.', array(
			'Ein eintägiger Kurs, in dem Sie lernen, Kinder im Notfall zu versorgen — mit Primary und Secondary Care, angepasst an die Kleinsten.',
		) ),
		'es' => array( 'Emergency First Response — Cuidados para niños', 'primeros-auxilios-ninos', 'Un día dedicado a los cuidados de emergencia para los más pequeños.', 'Padres, cuidadores y quien trabaja con niños.', array(
			'Un curso de un día para aprender a cuidar de los más pequeños en una emergencia, con cuidados primarios y secundarios adaptados a los niños.',
		) ),
	),

	'efr-instructor' => array(
		'en' => array( 'Emergency First Response Instructor', 'efr-instructor', 'Become able to teach others how to respond in an emergency.', 'Those who want to teach Emergency First Response courses.', array(
			'The EFR Instructor course prepares you to teach others how to act in emergency situations. Ask us about the prerequisites and dates before you send your request.',
		) ),
		'pt' => array( 'Emergency First Response — Instrutor', 'instrutor-efr', 'Fique habilitado a ensinar outros a agir em situações de emergência.', 'Quem quer ensinar cursos Emergency First Response.', array(
			'O curso de Instrutor EFR prepara-o para ensinar outras pessoas a agir em situações de emergência. Pergunte-nos pelos pré-requisitos e datas antes de enviar o pedido.',
		) ),
		'de' => array( 'Emergency First Response Instructor', 'efr-instructor', 'Lernen Sie, anderen das richtige Handeln im Notfall beizubringen.', 'Alle, die Emergency-First-Response-Kurse unterrichten möchten.', array(
			'Der EFR-Instructor-Kurs bereitet Sie darauf vor, anderen das Handeln in Notfallsituationen beizubringen. Fragen Sie uns vor Ihrer Anfrage nach Voraussetzungen und Terminen.',
		) ),
		'es' => array( 'Emergency First Response — Instructor', 'instructor-efr', 'Capacítese para enseñar a otros a actuar en situaciones de emergencia.', 'Quien quiere impartir cursos Emergency First Response.', array(
			'El curso de Instructor EFR le prepara para enseñar a otras personas a actuar en situaciones de emergencia. Pregúntenos por los requisitos previos y las fechas antes de enviar su solicitud.',
		) ),
	),

	'deep-diver' => array(
		'en' => array( 'PADI Deep Diver', 'deep-diver', 'Go further: there is still so much to explore down to 40 metres.', 'Certified divers who want to explore deeper.', array(
			'The Deep Diver specialty prepares you to explore beyond the depths of your entry-level training, down to 40 metres, over two days with our instructors.',
		) ),
		'pt' => array( 'Mergulho Profundo (PADI Deep Diver)', 'mergulho-profundo', 'Vá mais além: até aos 40 metros ainda há muito para explorar.', 'Mergulhadores certificados que querem explorar mais fundo.', array(
			'A especialidade de Mergulho Profundo prepara-o para explorar para lá das profundidades da formação inicial, até aos 40 metros, em dois dias com os nossos instrutores.',
		) ),
		'de' => array( 'PADI Deep Diver', 'deep-diver', 'Weiter hinab: Bis 40 Meter gibt es noch so viel zu entdecken.', 'Zertifizierte Taucher, die tiefer gehen möchten.', array(
			'Der Spezialkurs Deep Diver bereitet Sie in zwei Tagen mit unseren Instruktoren darauf vor, über die Tiefen Ihrer Grundausbildung hinaus bis 40 Meter zu tauchen.',
		) ),
		'es' => array( 'Buceo profundo (PADI Deep Diver)', 'buceo-profundo', 'Vaya más allá: hasta los 40 metros aún queda mucho por explorar.', 'Buceadores certificados que quieren explorar más profundo.', array(
			'La especialidad de Buceo profundo le prepara para explorar más allá de las profundidades de su formación inicial, hasta los 40 metros, en dos días con nuestros instructores.',
		) ),
	),

	'enriched-air-nitrox' => array(
		'en' => array( 'PADI Enriched Air (Nitrox) Diver', 'enriched-air-nitrox', 'More time underwater: learn to dive with enriched air nitrox.', 'Certified divers who want longer dives.', array(
			'Enriched air nitrox can give you more time underwater. This course qualifies you to dive with nitrox blends of up to 40% oxygen.',
			'Take it as a theory course without dives, or with two dives to put it into practice.',
		) ),
		'pt' => array( 'Ar Enriquecido Nitrox (PADI Enriched Air Diver)', 'nitrox', 'Mais tempo debaixo de água: aprenda a mergulhar com ar enriquecido nitrox.', 'Mergulhadores certificados que querem mergulhos mais longos.', array(
			'O ar enriquecido nitrox pode dar-lhe mais tempo debaixo de água. Este curso habilita-o a mergulhar com misturas nitrox até 40% de oxigénio.',
			'Pode fazê-lo como curso teórico, sem mergulhos, ou com dois mergulhos para pôr em prática.',
		) ),
		'de' => array( 'PADI Enriched Air (Nitrox) Diver', 'nitrox', 'Länger unter Wasser: Lernen Sie das Tauchen mit Nitrox.', 'Zertifizierte Taucher, die länger tauchen möchten.', array(
			'Nitrox kann Ihnen mehr Zeit unter Wasser verschaffen. Dieser Kurs qualifiziert Sie für das Tauchen mit Nitrox-Gemischen bis 40 % Sauerstoff.',
			'Wählen Sie den Theoriekurs ohne Tauchgänge oder die Variante mit zwei Tauchgängen zur praktischen Anwendung.',
		) ),
		'es' => array( 'Aire enriquecido Nitrox (PADI Enriched Air Diver)', 'nitrox', 'Más tiempo bajo el agua: aprenda a bucear con aire enriquecido nitrox.', 'Buceadores certificados que quieren inmersiones más largas.', array(
			'El aire enriquecido nitrox puede darle más tiempo bajo el agua. Este curso le capacita para bucear con mezclas nitrox de hasta un 40 % de oxígeno.',
			'Puede hacerlo como curso teórico sin inmersiones o con dos inmersiones para ponerlo en práctica.',
		) ),
	),

	'peak-performance-buoyancy' => array(
		'en' => array( 'PADI Peak Performance Buoyancy', 'peak-performance-buoyancy', 'Become a master of buoyancy control.', 'Certified divers who want to dive with more control.', array(
			'Good buoyancy makes every dive more relaxed and helps protect the environment around you. In one day, this specialty helps you fine-tune your control in the water.',
		) ),
		'pt' => array( 'Flutuabilidade Perfeita (PADI Peak Performance Buoyancy)', 'flutuabilidade-perfeita', 'Torne-se um mestre no controlo da flutuabilidade.', 'Mergulhadores certificados que querem mergulhar com mais controlo.', array(
			'Uma boa flutuabilidade torna cada mergulho mais tranquilo e ajuda a proteger o ambiente à sua volta. Num dia, esta especialidade ajuda-o a afinar o seu controlo na água.',
		) ),
		'de' => array( 'PADI Peak Performance Buoyancy', 'tarierung', 'Werden Sie ein Meister der Tarierung.', 'Zertifizierte Taucher, die kontrollierter tauchen möchten.', array(
			'Gute Tarierung macht jeden Tauchgang entspannter und schont die Umgebung. An einem Tag hilft Ihnen dieser Spezialkurs, Ihre Kontrolle im Wasser zu verfeinern.',
		) ),
		'es' => array( 'Flotabilidad perfecta (PADI Peak Performance Buoyancy)', 'flotabilidad-perfecta', 'Conviértase en un maestro del control de la flotabilidad.', 'Buceadores certificados que quieren bucear con más control.', array(
			'Una buena flotabilidad hace cada inmersión más relajada y ayuda a proteger el entorno. En un día, esta especialidad le ayuda a afinar su control en el agua.',
		) ),
	),

	'equipment-specialist' => array(
		'en' => array( 'PADI Equipment Specialist', 'equipment-specialist', 'Get to know your equipment like nobody else.', 'Certified divers who want to understand their gear.', array(
			'Learn how your scuba equipment works and how to look after it, in a one-day specialty course.',
		) ),
		'pt' => array( 'Especialista em Equipamento (PADI Equipment Specialist)', 'especialista-em-equipamento', 'Conheça o seu equipamento como ninguém.', 'Mergulhadores certificados que querem conhecer o seu equipamento.', array(
			'Aprenda como funciona o seu equipamento de mergulho e como cuidar dele, num curso de especialidade de um dia.',
		) ),
		'de' => array( 'PADI Equipment Specialist', 'equipment-specialist', 'Lernen Sie Ihre Ausrüstung kennen wie kein anderer.', 'Zertifizierte Taucher, die ihre Ausrüstung verstehen möchten.', array(
			'Erfahren Sie in einem eintägigen Spezialkurs, wie Ihre Tauchausrüstung funktioniert und wie Sie sie pflegen.',
		) ),
		'es' => array( 'Especialista en equipo (PADI Equipment Specialist)', 'especialista-en-equipo', 'Conozca su equipo como nadie.', 'Buceadores certificados que quieren conocer su equipo.', array(
			'Aprenda cómo funciona su equipo de buceo y cómo cuidarlo, en un curso de especialidad de un día.',
		) ),
	),

	'night-diver' => array(
		'en' => array( 'PADI Night Diver', 'night-diver', 'The same site, in the dark: a dive you will never forget.', 'Certified divers curious about the underwater world at night.', array(
			'Night, darkness, a place you know by day — your first time in the underwater world at night stays with you. Over two days you learn how to navigate in the dark and explore another world.',
		) ),
		'pt' => array( 'Mergulho Noturno (PADI Night Diver)', 'mergulho-noturno', 'O mesmo local, no escuro: um mergulho que nunca vai esquecer.', 'Mergulhadores certificados curiosos pelo mundo subaquático à noite.', array(
			'A noite, o escuro, um local que conhece de dia — a primeira vez no mundo subaquático à noite fica para sempre. Em dois dias aprende a orientar-se no escuro e a explorar um outro mundo.',
		) ),
		'de' => array( 'PADI Night Diver', 'nachttauchen', 'Derselbe Platz, im Dunkeln: ein Tauchgang, den Sie nie vergessen.', 'Zertifizierte Taucher, neugierig auf die Unterwasserwelt bei Nacht.', array(
			'Nacht, Dunkelheit, ein Ort, den Sie bei Tag kennen — Ihr erstes Mal in der Unterwasserwelt bei Nacht bleibt in Erinnerung. In zwei Tagen lernen Sie, sich im Dunkeln zu orientieren und eine andere Welt zu entdecken.',
		) ),
		'es' => array( 'Buceo nocturno (PADI Night Diver)', 'buceo-nocturno', 'El mismo lugar, a oscuras: una inmersión que nunca olvidará.', 'Buceadores certificados con curiosidad por el mundo submarino de noche.', array(
			'La noche, la oscuridad, un lugar que conoce de día — su primera vez en el mundo submarino de noche se queda para siempre. En dos días aprende a orientarse en la oscuridad y a explorar otro mundo.',
		) ),
	),

	'sidemount-diver' => array(
		'en' => array( 'PADI Sidemount Diver', 'sidemount-diver', 'Take the weight off your back: dive with cylinders mounted at your sides.', 'Certified divers who want to try a different configuration.', array(
			'Sidemount means carrying your cylinders at your sides instead of on your back. In two days you learn to set up and dive this configuration and to feel comfortable underwater in a new way.',
		) ),
		'pt' => array( 'PADI Sidemount Diver', 'sidemount', 'Tire o peso das costas: mergulhe com as garrafas colocadas lateralmente.', 'Mergulhadores certificados que querem experimentar outra configuração.', array(
			'Em sidemount, as garrafas vão de lado e não nas costas. Em dois dias aprende a montar e a mergulhar com esta configuração e a sentir-se confortável debaixo de água de uma forma nova.',
		) ),
		'de' => array( 'PADI Sidemount Diver', 'sidemount', 'Kein Gewicht mehr auf dem Rücken: Tauchen mit seitlich getragenen Flaschen.', 'Zertifizierte Taucher, die eine andere Konfiguration ausprobieren möchten.', array(
			'Beim Sidemount tragen Sie die Flaschen seitlich statt auf dem Rücken. In zwei Tagen lernen Sie, diese Konfiguration aufzubauen, damit zu tauchen und sich unter Wasser auf neue Weise wohlzufühlen.',
		) ),
		'es' => array( 'PADI Sidemount Diver', 'sidemount', 'Quítese el peso de la espalda: bucee con las botellas a los lados.', 'Buceadores certificados que quieren probar otra configuración.', array(
			'En sidemount, las botellas se llevan a los lados y no en la espalda. En dos días aprende a montar y bucear con esta configuración y a sentirse cómodo bajo el agua de una forma nueva.',
		) ),
	),

	'search-and-recovery' => array(
		'en' => array( 'PADI Search and Recovery Diver', 'search-and-recovery', 'Lost an anchor, a weight belt or your keys? Learn how to find and recover objects.', 'Certified divers who like a practical challenge.', array(
			'You never know when something will be lost underwater, so it pays to be prepared. Over two days you learn how to search for and recover lost objects — in a course that is good fun.',
		) ),
		'pt' => array( 'Busca e Recuperação (PADI Search and Recovery)', 'busca-e-recuperacao', 'Perdeu uma âncora, um cinto ou as chaves? Aprenda a encontrar e recuperar objetos.', 'Mergulhadores certificados que gostam de um desafio prático.', array(
			'Nunca se sabe quando algo se vai perder debaixo de água, por isso o melhor é estar preparado. Em dois dias aprende a procurar e recuperar objetos perdidos — num curso divertido.',
		) ),
		'de' => array( 'PADI Search and Recovery Diver', 'suchen-und-bergen', 'Anker, Bleigurt oder Schlüssel verloren? Lernen Sie, Gegenstände zu finden und zu bergen.', 'Zertifizierte Taucher, die praktische Herausforderungen mögen.', array(
			'Man weiß nie, wann unter Wasser etwas verloren geht — da lohnt es sich, vorbereitet zu sein. In zwei Tagen lernen Sie, verlorene Gegenstände zu suchen und zu bergen, in einem Kurs, der Spaß macht.',
		) ),
		'es' => array( 'Búsqueda y recuperación (PADI Search and Recovery)', 'busqueda-y-recuperacion', '¿Ha perdido un ancla, un cinturón o las llaves? Aprenda a encontrar y recuperar objetos.', 'Buceadores certificados a quienes les gusta un reto práctico.', array(
			'Nunca se sabe cuándo se va a perder algo bajo el agua, así que lo mejor es estar preparado. En dos días aprende a buscar y recuperar objetos perdidos, en un curso divertido.',
		) ),
	),

	'drift-diver' => array(
		'en' => array( 'PADI Drift Diver', 'drift-diver', 'A perfect day, but a strong current? No problem — drift diving is an exciting way to dive.', 'Certified divers who want to enjoy diving with the current.', array(
			'Some days the sea is perfect but the current is strong. Drift diving turns that into an exciting way to dive. This one-day specialty shows you how to enjoy diving with the current.',
		) ),
		'pt' => array( 'Mergulho em Corrente (PADI Drift Diver)', 'mergulho-em-corrente', 'Um dia perfeito, mas uma corrente forte? Não há problema — o mergulho em corrente é uma forma emocionante de mergulhar.', 'Mergulhadores certificados que querem aproveitar o mergulho com corrente.', array(
			'Há dias em que o mar está perfeito, mas a corrente é forte. O mergulho em corrente transforma isso numa forma emocionante de mergulhar. Esta especialidade de um dia mostra-lhe como desfrutar do mergulho com a corrente.',
		) ),
		'de' => array( 'PADI Drift Diver', 'stroemungstauchen', 'Perfekter Tag, aber starke Strömung? Kein Problem — Strömungstauchen ist eine aufregende Art zu tauchen.', 'Zertifizierte Taucher, die das Tauchen mit der Strömung genießen möchten.', array(
			'An manchen Tagen ist das Meer perfekt, aber die Strömung stark. Strömungstauchen macht daraus eine aufregende Art zu tauchen. Dieser eintägige Spezialkurs zeigt Ihnen, wie Sie das Tauchen mit der Strömung genießen.',
		) ),
		'es' => array( 'Buceo en corriente (PADI Drift Diver)', 'buceo-en-corriente', '¿Un día perfecto, pero con mucha corriente? No hay problema — el buceo en corriente es una forma emocionante de bucear.', 'Buceadores certificados que quieren disfrutar del buceo con corriente.', array(
			'Hay días en los que el mar está perfecto, pero la corriente es fuerte. El buceo en corriente lo convierte en una forma emocionante de bucear. Esta especialidad de un día le enseña a disfrutar del buceo con la corriente.',
		) ),
	),

	'digital-underwater-photographer' => array(
		'en' => array( 'PADI Digital Underwater Photographer', 'underwater-photography', 'Love photography, but your underwater shots don’t look right? Learn what you need in one day.', 'Certified divers who want better underwater photos.', array(
			'You have a camera and you love photography, but underwater the results are disappointing. In one day you learn what you need to take much better pictures beneath the surface.',
		) ),
		'pt' => array( 'Fotografia Digital Subaquática (PADI Digital Underwater Photographer)', 'fotografia-subaquatica', 'Adora fotografia, mas as fotos debaixo de água não ficam bem? Aprenda o que precisa num dia.', 'Mergulhadores certificados que querem melhores fotografias subaquáticas.', array(
			'Tem máquina e adora fotografia, mas debaixo de água os resultados desiludem. Num dia aprende o que precisa para tirar fotografias muito melhores abaixo da superfície.',
		) ),
		'de' => array( 'PADI Digital Underwater Photographer', 'unterwasserfotografie', 'Sie fotografieren gern, aber unter Wasser wird es nichts? Lernen Sie das Nötige an einem Tag.', 'Zertifizierte Taucher, die bessere Unterwasserfotos möchten.', array(
			'Sie haben eine Kamera und lieben Fotografie, doch unter Wasser enttäuschen die Ergebnisse. An einem Tag lernen Sie, was Sie für deutlich bessere Bilder unter der Oberfläche brauchen.',
		) ),
		'es' => array( 'Fotografía digital submarina (PADI Digital Underwater Photographer)', 'fotografia-submarina', '¿Le encanta la fotografía, pero bajo el agua no le salen bien? Aprenda lo necesario en un día.', 'Buceadores certificados que quieren mejores fotos submarinas.', array(
			'Tiene cámara y le encanta la fotografía, pero bajo el agua los resultados decepcionan. En un día aprende lo que necesita para hacer fotos mucho mejores bajo la superficie.',
		) ),
	),

	'emergency-oxygen-provider' => array(
		'en' => array( 'PADI Emergency Oxygen Provider', 'emergency-oxygen-provider', 'Accidents happen: learn how to use emergency oxygen and make a difference.', 'Divers and non-divers who want to be prepared.', array(
			'Hope for the best, prepare for the worst. This course teaches you how to use oxygen in an emergency situation, so you can make a difference for someone who needs it.',
		) ),
		'pt' => array( 'Administração de Oxigénio de Emergência (PADI Emergency Oxygen Provider)', 'oxigenio-de-emergencia', 'Os acidentes acontecem: aprenda a utilizar oxigénio de emergência e a fazer a diferença.', 'Mergulhadores e não mergulhadores que querem estar preparados.', array(
			'Esperar o melhor e preparar-se para o pior. Este curso ensina a utilizar oxigénio numa situação de emergência, para poder fazer a diferença para quem precisar.',
		) ),
		'de' => array( 'PADI Emergency Oxygen Provider', 'notfall-sauerstoff', 'Unfälle passieren: Lernen Sie, Notfallsauerstoff einzusetzen, und helfen Sie wirksam.', 'Taucher und Nichttaucher, die vorbereitet sein möchten.', array(
			'Auf das Beste hoffen, auf das Schlimmste vorbereitet sein. In diesem Kurs lernen Sie, Sauerstoff in einer Notfallsituation einzusetzen — damit Sie helfen können, wenn es darauf ankommt.',
		) ),
		'es' => array( 'Administración de oxígeno de emergencia (PADI Emergency Oxygen Provider)', 'oxigeno-de-emergencia', 'Los accidentes ocurren: aprenda a usar oxígeno de emergencia y a marcar la diferencia.', 'Buceadores y no buceadores que quieren estar preparados.', array(
			'Esperar lo mejor y prepararse para lo peor. Este curso le enseña a usar oxígeno en una situación de emergencia, para poder ayudar a quien lo necesite.',
		) ),
	),

	'wreck-diver' => array(
		'en' => array( 'PADI Wreck Diver', 'wreck-diver', 'Explore the places where history lies beneath the surface.', 'Certified divers fascinated by wrecks.', array(
			'Wrecks are some of the most fascinating places to dive. Over two days, this specialty shows you how to explore them with care and enjoyment.',
		) ),
		'pt' => array( 'Mergulho em Naufrágios (PADI Wreck Diver)', 'mergulho-em-naufragios', 'Explore os locais onde a história repousa abaixo da superfície.', 'Mergulhadores certificados fascinados por naufrágios.', array(
			'Os naufrágios estão entre os locais mais fascinantes para mergulhar. Em dois dias, esta especialidade mostra-lhe como explorá-los com cuidado e prazer.',
		) ),
		'de' => array( 'PADI Wreck Diver', 'wracktauchen', 'Entdecken Sie Orte, an denen Geschichte unter der Oberfläche liegt.', 'Zertifizierte Taucher, die Wracks faszinieren.', array(
			'Wracks gehören zu den faszinierendsten Tauchplätzen. In zwei Tagen zeigt Ihnen dieser Spezialkurs, wie Sie sie umsichtig und mit Freude erkunden.',
		) ),
		'es' => array( 'Buceo en pecios (PADI Wreck Diver)', 'buceo-en-pecios', 'Explore los lugares donde la historia descansa bajo la superficie.', 'Buceadores certificados fascinados por los pecios.', array(
			'Los pecios están entre los lugares más fascinantes para bucear. En dos días, esta especialidad le enseña a explorarlos con cuidado y disfrute.',
		) ),
	),

	'boat-diver' => array(
		'en' => array( 'PADI Boat Diver', 'boat-diver', 'Everything you need to know to dive from a boat, in one day.', 'Certified divers who want to feel at home on a dive boat.', array(
			'This one-day specialty covers what you need to know to dive comfortably from a boat.',
		) ),
		'pt' => array( 'Mergulho Embarcado (PADI Boat Diver)', 'mergulho-embarcado', 'Tudo o que precisa de saber para mergulhar a partir de um barco, num dia.', 'Mergulhadores certificados que querem sentir-se à vontade num barco de mergulho.', array(
			'Esta especialidade de um dia aborda tudo o que precisa de saber para mergulhar a partir de um barco com à-vontade.',
		) ),
		'de' => array( 'PADI Boat Diver', 'bootstauchen', 'Alles, was Sie für das Tauchen vom Boot wissen müssen — an einem Tag.', 'Zertifizierte Taucher, die sich auf einem Tauchboot wohlfühlen möchten.', array(
			'Dieser eintägige Spezialkurs vermittelt alles, was Sie für entspanntes Tauchen vom Boot aus wissen müssen.',
		) ),
		'es' => array( 'Buceo desde barco (PADI Boat Diver)', 'buceo-desde-barco', 'Todo lo que necesita saber para bucear desde un barco, en un día.', 'Buceadores certificados que quieren sentirse a gusto en un barco de buceo.', array(
			'Esta especialidad de un día cubre todo lo que necesita saber para bucear desde un barco con comodidad.',
		) ),
	),

	'discover-tec' => array(
		'en' => array( 'PADI Discover Tec', 'discover-tec', 'Curious about technical diving but not sure yet? Find out what it involves in half a day.', 'Certified divers curious about tec diving. Requirements confirmed by our team.', array(
			'Want to know what technical diving is about before you commit? Discover Tec is a half-day introduction to the equipment and approach of tec diving, with a TecRec instructor.',
		) ),
		'pt' => array( 'PADI Discover Tec', 'discover-tec', 'Curioso sobre o mergulho técnico, mas ainda sem certezas? Descubra o que implica em meio dia.', 'Mergulhadores certificados curiosos pelo mergulho técnico. Requisitos confirmados pela equipa.', array(
			'Quer saber mais sobre o mergulho técnico antes de embarcar nessa aventura? O Discover Tec é uma introdução de meio dia ao equipamento e à abordagem do mergulho técnico, com um instrutor TecRec.',
		) ),
		'de' => array( 'PADI Discover Tec', 'discover-tec', 'Neugierig auf technisches Tauchen, aber noch unsicher? Finden Sie es an einem halben Tag heraus.', 'Zertifizierte Taucher mit Interesse am Tec-Tauchen. Voraussetzungen klärt unser Team.', array(
			'Sie möchten wissen, worum es beim technischen Tauchen geht, bevor Sie sich festlegen? Discover Tec ist eine halbtägige Einführung in Ausrüstung und Denkweise des Tec-Tauchens, mit einem TecRec-Instruktor.',
		) ),
		'es' => array( 'PADI Discover Tec', 'discover-tec', '¿Curiosidad por el buceo técnico, pero aún sin decidirse? Descubra lo que implica en medio día.', 'Buceadores certificados con curiosidad por el buceo técnico. El equipo confirma los requisitos.', array(
			'¿Quiere saber de qué trata el buceo técnico antes de embarcarse en esa aventura? Discover Tec es una introducción de medio día al equipo y al enfoque del buceo técnico, con un instructor TecRec.',
		) ),
	),

	'tec-40' => array(
		'en' => array( 'PADI Tec 40', 'tec-40', 'The first step into technical diving: dives to 40 metres with limited decompression.', 'Experienced divers starting technical training. Requirements confirmed by our team.', array(
			'Tec 40 is where technical diving begins. Over three days you learn to plan and make dives to 40 metres with limited decompression.',
		) ),
		'pt' => array( 'PADI Tec 40', 'tec-40', 'O primeiro passo no mergulho técnico: mergulhos até 40 metros com descompressão limitada.', 'Mergulhadores experientes que iniciam a formação técnica. Requisitos confirmados pela equipa.', array(
			'O Tec 40 é o início do mergulho técnico. Em três dias aprende a planear e executar mergulhos até 40 metros com descompressão limitada.',
		) ),
		'de' => array( 'PADI Tec 40', 'tec-40', 'Der erste Schritt ins technische Tauchen: Tauchgänge bis 40 Meter mit begrenzter Dekompression.', 'Erfahrene Taucher am Beginn der Tec-Ausbildung. Voraussetzungen klärt unser Team.', array(
			'Mit Tec 40 beginnt das technische Tauchen. In drei Tagen lernen Sie, Tauchgänge bis 40 Meter mit begrenzter Dekompression zu planen und durchzuführen.',
		) ),
		'es' => array( 'PADI Tec 40', 'tec-40', 'El primer paso en el buceo técnico: inmersiones hasta 40 metros con descompresión limitada.', 'Buceadores experimentados que inician la formación técnica. El equipo confirma los requisitos.', array(
			'Tec 40 es el inicio del buceo técnico. En tres días aprende a planificar y realizar inmersiones hasta 40 metros con descompresión limitada.',
		) ),
	),

	'tec-45' => array(
		'en' => array( 'PADI Tec 45 and Tec 45 Trimix', 'tec-45', 'Decompression dives to 45 metres, with an optional trimix course.', 'Technical divers. Requirements confirmed by our team.', array(
			'Tec 45 takes you a step further and prepares you to plan and make decompression dives to 45 metres.',
			'The one-day Tec 45 Trimix course adds the use of trimix, a gas mixture containing helium. Requirements and content are confirmed individually with our TecRec instructor.',
		) ),
		'pt' => array( 'PADI Tec 45 e Tec 45 Trimix', 'tec-45', 'Mergulhos com descompressão até 45 metros, com opção de curso de trimix.', 'Mergulhadores técnicos. Requisitos confirmados pela equipa.', array(
			'O Tec 45 leva-o mais longe e prepara-o para planear e realizar mergulhos com descompressão até 45 metros.',
			'O curso Tec 45 Trimix, de um dia, acrescenta a utilização de trimix, uma mistura gasosa com hélio. Requisitos e conteúdos são confirmados individualmente com o nosso instrutor TecRec.',
		) ),
		'de' => array( 'PADI Tec 45 und Tec 45 Trimix', 'tec-45', 'Dekompressionstauchgänge bis 45 Meter, optional mit Trimix-Kurs.', 'Tec-Taucher. Voraussetzungen klärt unser Team.', array(
			'Tec 45 geht einen Schritt weiter und bereitet Sie darauf vor, Dekompressionstauchgänge bis 45 Meter zu planen und durchzuführen.',
			'Der eintägige Kurs Tec 45 Trimix ergänzt den Einsatz von Trimix, einem heliumhaltigen Gasgemisch. Voraussetzungen und Inhalte werden individuell mit unserem TecRec-Instruktor abgestimmt.',
		) ),
		'es' => array( 'PADI Tec 45 y Tec 45 Trimix', 'tec-45', 'Inmersiones con descompresión hasta 45 metros, con opción de curso de trimix.', 'Buceadores técnicos. El equipo confirma los requisitos.', array(
			'Tec 45 le lleva un paso más allá y le prepara para planificar y realizar inmersiones con descompresión hasta 45 metros.',
			'El curso Tec 45 Trimix, de un día, añade el uso de trimix, una mezcla de gases con helio. Los requisitos y contenidos se confirman individualmente con nuestro instructor TecRec.',
		) ),
	),

	'tec-50' => array(
		'en' => array( 'PADI Tec 50 and Tec 50 Trimix', 'tec-50', 'That wreck at 47 metres? Decompression dives to 50 metres, with an optional trimix course.', 'Technical divers. Requirements confirmed by our team.', array(
			'Tec 50 prepares you to plan and make decompression dives to 50 metres.',
			'The one-day Tec 50 Trimix course introduces the use of trimix at these depths. Requirements and content are confirmed individually with our TecRec instructor.',
		) ),
		'pt' => array( 'PADI Tec 50 e Tec 50 Trimix', 'tec-50', 'Aquele naufrágio aos 47 metros? Mergulhos com descompressão até 50 metros, com opção de curso de trimix.', 'Mergulhadores técnicos. Requisitos confirmados pela equipa.', array(
			'O Tec 50 prepara-o para planear e realizar mergulhos com descompressão até 50 metros.',
			'O curso Tec 50 Trimix, de um dia, introduz a utilização de trimix a estas profundidades. Requisitos e conteúdos são confirmados individualmente com o nosso instrutor TecRec.',
		) ),
		'de' => array( 'PADI Tec 50 und Tec 50 Trimix', 'tec-50', 'Das Wrack auf 47 Metern? Dekompressionstauchgänge bis 50 Meter, optional mit Trimix-Kurs.', 'Tec-Taucher. Voraussetzungen klärt unser Team.', array(
			'Tec 50 bereitet Sie darauf vor, Dekompressionstauchgänge bis 50 Meter zu planen und durchzuführen.',
			'Der eintägige Kurs Tec 50 Trimix führt in den Einsatz von Trimix in diesen Tiefen ein. Voraussetzungen und Inhalte werden individuell mit unserem TecRec-Instruktor abgestimmt.',
		) ),
		'es' => array( 'PADI Tec 50 y Tec 50 Trimix', 'tec-50', '¿Aquel pecio a 47 metros? Inmersiones con descompresión hasta 50 metros, con opción de curso de trimix.', 'Buceadores técnicos. El equipo confirma los requisitos.', array(
			'Tec 50 le prepara para planificar y realizar inmersiones con descompresión hasta 50 metros.',
			'El curso Tec 50 Trimix, de un día, introduce el uso de trimix a estas profundidades. Los requisitos y contenidos se confirman individualmente con nuestro instructor TecRec.',
		) ),
	),

	'tec-trimix-65' => array(
		'en' => array( 'PADI Tec Trimix 65', 'tec-trimix-65', 'Normoxic trimix diving to a maximum depth of 65 metres.', 'Experienced tec divers. Requirements confirmed by our team.', array(
			'Tec Trimix 65 teaches you to use normoxic trimix for dives to a maximum depth of 65 metres.',
		) ),
		'pt' => array( 'PADI Tec Trimix 65', 'tec-trimix-65', 'Mergulho com trimix normóxico até uma profundidade máxima de 65 metros.', 'Mergulhadores técnicos experientes. Requisitos confirmados pela equipa.', array(
			'O Tec Trimix 65 ensina a utilizar trimix normóxico em mergulhos até uma profundidade máxima de 65 metros.',
		) ),
		'de' => array( 'PADI Tec Trimix 65', 'tec-trimix-65', 'Tauchen mit normoxischem Trimix bis maximal 65 Meter.', 'Erfahrene Tec-Taucher. Voraussetzungen klärt unser Team.', array(
			'Tec Trimix 65 lehrt den Einsatz von normoxischem Trimix für Tauchgänge bis zu einer maximalen Tiefe von 65 Metern.',
		) ),
		'es' => array( 'PADI Tec Trimix 65', 'tec-trimix-65', 'Buceo con trimix normóxico hasta una profundidad máxima de 65 metros.', 'Buceadores técnicos experimentados. El equipo confirma los requisitos.', array(
			'Tec Trimix 65 le enseña a usar trimix normóxico en inmersiones hasta una profundidad máxima de 65 metros.',
		) ),
	),

	'tec-trimix' => array(
		'en' => array( 'PADI Tec Trimix', 'tec-trimix', 'The highest level of PADI TecRec training, for dives deeper than 65 metres.', 'Experienced technical divers. Requirements confirmed by our team.', array(
			'Tec Trimix is the final level of technical diving training in the PADI TecRec programme, preparing experienced tec divers for dives deeper than 65 metres using hypoxic trimix.',
			'Depth limits, requirements and course content are discussed individually with our instructor.',
		) ),
		'pt' => array( 'PADI Tec Trimix', 'tec-trimix', 'O último nível da formação PADI TecRec, para mergulhos abaixo dos 65 metros.', 'Mergulhadores técnicos experientes. Requisitos confirmados pela equipa.', array(
			'O Tec Trimix é o último nível da formação técnica do programa PADI TecRec e prepara mergulhadores técnicos experientes para mergulhos abaixo dos 65 metros com trimix hipóxico.',
			'Limites de profundidade, requisitos e conteúdos são discutidos individualmente com o nosso instrutor.',
		) ),
		'de' => array( 'PADI Tec Trimix', 'tec-trimix', 'Die höchste Stufe der PADI-TecRec-Ausbildung, für Tauchgänge tiefer als 65 Meter.', 'Erfahrene Tec-Taucher. Voraussetzungen klärt unser Team.', array(
			'Tec Trimix ist die letzte Stufe der technischen Ausbildung im PADI-TecRec-Programm und bereitet erfahrene Tec-Taucher mit hypoxischem Trimix auf Tauchgänge tiefer als 65 Meter vor.',
			'Tiefengrenzen, Voraussetzungen und Kursinhalte werden individuell mit unserem Instruktor besprochen.',
		) ),
		'es' => array( 'PADI Tec Trimix', 'tec-trimix', 'El nivel más alto de la formación PADI TecRec, para inmersiones a más de 65 metros.', 'Buceadores técnicos experimentados. El equipo confirma los requisitos.', array(
			'Tec Trimix es el último nivel de la formación técnica del programa PADI TecRec y prepara a buceadores técnicos experimentados para inmersiones a más de 65 metros con trimix hipóxico.',
			'Los límites de profundidad, requisitos y contenidos se tratan individualmente con nuestro instructor.',
		) ),
	),

	'tec-sidemount' => array(
		'en' => array( 'PADI Tec Sidemount', 'tec-sidemount', 'Sidemount techniques applied to technical diving.', 'Tec divers who want to use sidemount. Requirements confirmed by our team.', array(
			'Tec Sidemount is for divers who want to use side-mounted cylinder techniques in technical diving.',
		) ),
		'pt' => array( 'PADI Tec Sidemount', 'tec-sidemount', 'Técnicas de montagem lateral de garrafas aplicadas ao mergulho técnico.', 'Mergulhadores técnicos que querem usar sidemount. Requisitos confirmados pela equipa.', array(
			'O Tec Sidemount destina-se a quem quer utilizar as técnicas de montagem lateral de garrafas no mergulho técnico.',
		) ),
		'de' => array( 'PADI Tec Sidemount', 'tec-sidemount', 'Sidemount-Techniken für das technische Tauchen.', 'Tec-Taucher, die Sidemount nutzen möchten. Voraussetzungen klärt unser Team.', array(
			'Tec Sidemount richtet sich an Taucher, die seitlich getragene Flaschen im technischen Tauchen einsetzen möchten.',
		) ),
		'es' => array( 'PADI Tec Sidemount', 'tec-sidemount', 'Técnicas de montaje lateral de botellas aplicadas al buceo técnico.', 'Buceadores técnicos que quieren usar sidemount. El equipo confirma los requisitos.', array(
			'Tec Sidemount está pensado para quien quiere utilizar las técnicas de montaje lateral de botellas en el buceo técnico.',
		) ),
	),

	'gas-blender' => array(
		'en' => array( 'PADI Gas Blender — Nitrox and Trimix', 'gas-blender', 'Learn the techniques and procedures for blending nitrox and trimix fills.', 'Divers who want to learn gas blending. Requirements confirmed by our team.', array(
			'Gas Blender Nitrox covers the techniques and procedures for preparing nitrox fills. Gas Blender Trimix extends them to trimix.',
		) ),
		'pt' => array( 'PADI Gas Blender — Nitrox e Trimix', 'gas-blender', 'Aprenda as técnicas e procedimentos para enchimentos de nitrox e trimix.', 'Quem quer aprender a preparar misturas. Requisitos confirmados pela equipa.', array(
			'O Gas Blender Nitrox aborda as técnicas e procedimentos para realizar enchimentos de nitrox. O Gas Blender Trimix alarga-os ao trimix.',
		) ),
		'de' => array( 'PADI Gas Blender — Nitrox und Trimix', 'gas-blender', 'Lernen Sie Techniken und Verfahren für Nitrox- und Trimix-Füllungen.', 'Alle, die das Mischen von Atemgasen lernen möchten. Voraussetzungen klärt unser Team.', array(
			'Gas Blender Nitrox vermittelt Techniken und Verfahren für Nitrox-Füllungen. Gas Blender Trimix erweitert diese auf Trimix.',
		) ),
		'es' => array( 'PADI Gas Blender — Nitrox y Trimix', 'gas-blender', 'Aprenda las técnicas y procedimientos para rellenos de nitrox y trimix.', 'Quien quiere aprender a preparar mezclas. El equipo confirma los requisitos.', array(
			'Gas Blender Nitrox aborda las técnicas y procedimientos para realizar rellenos de nitrox. Gas Blender Trimix los amplía al trimix.',
		) ),
	),

	'boat-dives' => array(
		'en' => array( 'Boat dives for certified divers', 'boat-dives', '1, 2, 6 or 10 boat dives, with your own equipment or with rental equipment included.', 'Certified divers.', array(
			'Already certified? Join our boat dives around Sagres. Choose how many dives you want and whether you bring your own equipment or rent ours.',
			'Our dives are accompanied by a guide or an instructor.',
		), array(
			array( 'Can I rent equipment?', 'Yes. Choose a package with equipment included, or rent diving equipment by the day.' ),
		) ),
		'pt' => array( 'Saídas de barco para mergulhadores certificados', 'saidas-de-barco', '1, 2, 6 ou 10 mergulhos de barco, com equipamento próprio ou com equipamento incluído.', 'Mergulhadores certificados.', array(
			'Já tem certificação? Junte-se às nossas saídas de barco em Sagres. Escolha quantos mergulhos quer fazer e se traz o seu equipamento ou aluga o nosso.',
			'Os nossos mergulhos são acompanhados por um guia ou instrutor.',
		), array(
			array( 'Posso alugar equipamento?', 'Sim. Escolha um pacote com equipamento incluído, ou alugue equipamento de mergulho por dia.' ),
		) ),
		'de' => array( 'Bootstauchgänge für brevetierte Taucher', 'bootstauchgaenge', '1, 2, 6 oder 10 Bootstauchgänge, mit eigener Ausrüstung oder inklusive Leihausrüstung.', 'Brevetierte Taucher.', array(
			'Sie sind bereits zertifiziert? Kommen Sie mit auf unsere Bootstauchgänge rund um Sagres. Wählen Sie die Anzahl der Tauchgänge und ob Sie eigene Ausrüstung mitbringen oder unsere leihen.',
			'Unsere Tauchgänge werden von einem Guide oder Instruktor begleitet.',
		), array(
			array( 'Kann ich Ausrüstung leihen?', 'Ja. Wählen Sie ein Paket mit Ausrüstung oder leihen Sie Tauchausrüstung tageweise.' ),
		) ),
		'es' => array( 'Salidas en barco para buceadores certificados', 'salidas-en-barco', '1, 2, 6 o 10 inmersiones en barco, con equipo propio o con equipo de alquiler incluido.', 'Buceadores certificados.', array(
			'¿Ya tiene certificación? Únase a nuestras salidas en barco en Sagres. Elija cuántas inmersiones quiere hacer y si trae su equipo o alquila el nuestro.',
			'Nuestras inmersiones van acompañadas por un guía o instructor.',
		), array(
			array( '¿Puedo alquilar equipo?', 'Sí. Elija un paquete con equipo incluido o alquile equipo de buceo por días.' ),
		) ),
	),

	'equipment-rental' => array(
		'en' => array( 'Equipment rental', 'equipment-rental', 'Diving or snorkelling equipment, rented by the day.', 'Certified divers and snorkellers who need equipment.', array(
			'Need equipment? We rent diving equipment and snorkelling equipment by the day.',
			'Snorkelling equipment is available to rent; it does not include a guided trip.',
		) ),
		'pt' => array( 'Aluguer de equipamento', 'aluguer-de-equipamento', 'Equipamento de mergulho ou de snorkel, alugado por dia.', 'Mergulhadores certificados e praticantes de snorkel que precisam de equipamento.', array(
			'Precisa de equipamento? Alugamos equipamento de mergulho e equipamento de snorkel por dia.',
			'O equipamento de snorkel está disponível para aluguer; não inclui uma saída guiada.',
		) ),
		'de' => array( 'Ausrüstungsverleih', 'ausruestungsverleih', 'Tauch- oder Schnorchelausrüstung, tageweise zu leihen.', 'Brevetierte Taucher und Schnorchler, die Ausrüstung brauchen.', array(
			'Sie brauchen Ausrüstung? Wir verleihen Tauch- und Schnorchelausrüstung tageweise.',
			'Schnorchelausrüstung kann geliehen werden; eine geführte Tour ist nicht enthalten.',
		) ),
		'es' => array( 'Alquiler de equipo', 'alquiler-de-equipo', 'Equipo de buceo o de snorkel, alquilado por días.', 'Buceadores certificados y practicantes de snorkel que necesitan equipo.', array(
			'¿Necesita equipo? Alquilamos equipo de buceo y equipo de snorkel por días.',
			'El equipo de snorkel está disponible en alquiler; no incluye una salida guiada.',
		) ),
	),

	'scuba-review' => array(
		'en' => array( 'Scuba Review (skills refresher)', 'scuba-review', 'Half a day to go over the basics and remember what you’ve forgotten.', 'Certified divers who haven’t dived for a while.', array(
			'Take a little time to go over the basics again before your next dives. A half-day refresher with our team.',
		) ),
		'pt' => array( 'Refrescamento de habilidades (Scuba Review)', 'refrescamento', 'Meio dia para rever o essencial e recordar o que já esqueceu.', 'Mergulhadores certificados que não mergulham há algum tempo.', array(
			'Dedique um pouco de tempo a rever o essencial antes dos próximos mergulhos. Meio dia de revisão com a nossa equipa.',
		) ),
		'de' => array( 'Scuba Review (Auffrischung)', 'scuba-review', 'Ein halber Tag, um die Grundlagen aufzufrischen.', 'Brevetierte Taucher, die länger nicht getaucht sind.', array(
			'Nehmen Sie sich vor Ihren nächsten Tauchgängen etwas Zeit, um die Grundlagen zu wiederholen. Eine halbtägige Auffrischung mit unserem Team.',
		) ),
		'es' => array( 'Repaso de habilidades (Scuba Review)', 'repaso-de-habilidades', 'Medio día para repasar lo básico y recordar lo que ha olvidado.', 'Buceadores certificados que llevan un tiempo sin bucear.', array(
			'Dedique un poco de tiempo a repasar lo básico antes de sus próximas inmersiones. Medio día de repaso con nuestro equipo.',
		) ),
	),

	'reactivate' => array(
		'en' => array( 'PADI ReActivate', 'reactivate', 'Out of the water for a long time? Refresh your skills and knowledge in one day.', 'Certified divers who haven’t dived for a long time.', array(
			'ReActivate is the ideal programme for divers who have been out of the water for a long time: in one day you refresh both your skills and your knowledge.',
		) ),
		'pt' => array( 'PADI ReActivate', 'reactivate', 'Há muito tempo sem mergulhar? Atualize as suas técnicas e conhecimentos num dia.', 'Mergulhadores certificados que já não mergulham há muito tempo.', array(
			'O ReActivate é o programa ideal para quem já não mergulha há muito tempo: num dia refresca as suas técnicas e os seus conhecimentos.',
		) ),
		'de' => array( 'PADI ReActivate', 'reactivate', 'Lange nicht getaucht? Frischen Sie Fertigkeiten und Wissen an einem Tag auf.', 'Brevetierte Taucher, die lange nicht getaucht sind.', array(
			'ReActivate ist das ideale Programm für Taucher, die lange nicht im Wasser waren: An einem Tag frischen Sie Fertigkeiten und Wissen auf.',
		) ),
		'es' => array( 'PADI ReActivate', 'reactivate', '¿Mucho tiempo sin bucear? Refresque sus habilidades y conocimientos en un día.', 'Buceadores certificados que llevan mucho tiempo sin bucear.', array(
			'ReActivate es el programa ideal para quien lleva mucho tiempo sin bucear: en un día refresca tanto sus habilidades como sus conocimientos.',
		) ),
	),
);
