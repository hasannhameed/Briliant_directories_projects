<?php

$subBtn_sql    =    'SELECT * FROM `list_services` where profession_id = 4 AND master_id != 0';
$subcat_sql2   =    'SELECT * FROM `list_services` where profession_id = 4 AND master_id != 0 LIMIT 9';
$brands_sql3   =    'SELECT * FROM `users_data` where profession_id = 3 LIMIT 20'; //for brands
$data          =    mysql_query($subBtn_sql);
$data2         =    mysql_query($subcat_sql2);
$brands_data   =    mysql_query($brands_sql3);

$categories = [
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://img.freepik.com/free-vector/people-silhouette-logo_361591-2448.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Laine Minérale Haute Performance',
                'title' => 'Laine de Roche R=7 pour Combles',
                'profile_url' => '#',
                'reviews' => 205,
                'rating' => 4,
            ],
            [
                'is_sponsored' => true,
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/011/883/296/non_2x/modern-graphic-leaf-abstrack-with-water-drop-colorful-logo-good-for-technology-logo-fruits-logo-fresh-logo-nature-logo-company-logo-dummy-logo-bussiness-logo-vector.jpg',
                'alt_text' => 'Ouate de Cellulose',
                'title' => 'Ouate de Cellulose Écologique Soufflée',
                'profile_url' => '#',
                'reviews' => 180,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/free-vector/flat-letter-i-logo-template-set_23-2149376109.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Panneaux Polyuréthane Haute Densité',
                'title' => 'Panneaux Isolants Polyuréthane pour Sols',
                'profile_url' => '#',
                'reviews' => 100,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/free-vector/logo-tie-design-template_474888-1906.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Isolation Thermique par l`Extérieur',
                'title' => 'Système ITE Complet pour Façades',
                'profile_url' => '#',
                'reviews' => 50,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://static.vecteezy.com/system/resources/previews/011/883/287/non_2x/modern-letter-c-colorful-logo-with-watter-drop-good-for-technology-logo-company-logo-dummy-logo-bussiness-logo-free-vector.jpg',
                'alt_text' => 'Pompe à Chaleur Air/Eau',
                'title' => 'Pompe à Chaleur Basse Consommation A+++',
                'profile_url' => '#',
                'reviews' => 310,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/image-vector/science-cloud-computing-concept-based-600nw-2390397167.jpg',
                'alt_text' => 'Chaudière à granulés de bois',
                'title' => 'Chaudière Biomasse Granulés Haute Perf.',
                'profile_url' => '#',
                'reviews' => 145,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/free-vector/flat-design-culture-logo-template_23-2149845368.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Chauffe-eau thermodynamique',
                'title' => 'Chauffe-Eau Thermodynamique Connecté',
                'profile_url' => '#',
                'reviews' => 88,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://static.vecteezy.com/system/resources/thumbnails/011/883/284/small/colorful-star-logo-good-for-technology-logo-vintech-logo-company-logo-browser-logo-dummy-logo-bussiness-logo-free-vector.jpg',
                'alt_text' => 'Radiateur à inertie sèche',
                'title' => 'Radiateurs Électriques à Inertie Sèche',
                'profile_url' => '#',
                'reviews' => 210,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://img.freepik.com/premium-vector/people-logo-design-simbol-icon_332533-2427.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'VMC double flux haut rendement',
                'title' => 'VMC Double Flux pour Maisons Passives',
                'profile_url' => '#',
                'reviews' => 95,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/image-vector/abstract-technology-logo-template-corporate-600nw-1786736897.jpg',
                'alt_text' => 'Climatiseur réversible mural',
                'title' => 'Climatisation Réversible Inverter Silence',
                'profile_url' => '#',
                'reviews' => 160,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://logopond.com/logos/dc5cc8314658dcbce4536affc4772f78.png',
                'alt_text' => 'Purificateur d`air HEPA',
                'title' => 'Purificateur d`Air Professionnel (Hôpitaux)',
                'profile_url' => '#',
                'reviews' => 40,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/free-vector/business-logo_23-2147503133.jpg',
                'alt_text' => 'Déshumidificateur industriel',
                'title' => 'Déshumidificateur Grande Capacité',
                'profile_url' => '#',
                'reviews' => 25,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://png.pngtree.com/png-clipart/20200720/original/pngtree-nipple-baby-dummy-pacifier-kids-purple-business-logo-templat-png-image_4354080.jpg',
                'alt_text' => 'Récupérateur d`eau de pluie enterré',
                'title' => 'Kit Récupération d`Eau de Pluie 5000L',
                'profile_url' => '#',
                'reviews' => 112,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://uploads.turbologo.com/uploads/design/preview_image/68460538/preview_image20241130-1-1mybbz0.png',
                'alt_text' => 'Adoucisseur d`eau à sel',
                'title' => 'Adoucisseur d`Eau Compact à Résine',
                'profile_url' => '#',
                'reviews' => 68,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://dynamic.brandcrowd.com/asset/logo/efaaf340-4ff2-448a-b68c-be74dce87aec/logo-search-grid-2x?logoTemplateVersion=1&v=638016821190330000',
                'alt_text' => 'Système de filtration d`eau domestique',
                'title' => 'Système de Filtration d`Eau sur Puits',
                'profile_url' => '#',
                'reviews' => 35,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/image-vector/modern-vector-graphic-triangle-play-260nw-1960828870.jpg',
                'alt_text' => 'Pompe à eau submersible',
                'title' => 'Pompe Submersible pour Eaux Chargées',
                'profile_url' => '#',
                'reviews' => 49,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://png.pngtree.com/template/20190611/ourmid/pngtree-babydummynewbienipplenoob-logo-design--blue-and-orange-b-image_211990.jpg',
                'alt_text' => 'Panneaux solaires résidentiels',
                'title' => 'Installation Solaire Autoconsommation 6kWc',
                'profile_url' => '#',
                'reviews' => 250,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTbjWpo8vRBnnqbEAujlubS6rdcrLCoWOThZA&s',
                'alt_text' => 'Système de stockage sur batterie domestique',
                'title' => 'Batterie de Stockage Domestique Lithium-ion',
                'profile_url' => '#',
                'reviews' => 130,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpirPCq-KQAnJCQyJMmruwdPgR3-w1_a0neA&s',
                'alt_text' => 'Petite éolienne domestique',
                'title' => 'Petite Éolienne Verticale Urbaine',
                'profile_url' => '#',
                'reviews' => 70,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRu4Fg9FtHQn4IMD8VnLWRn47Gp3O20cfB9g&s',
                'alt_text' => 'Onduleur photovoltaïque central',
                'title' => 'Onduleurs Haute Efficacité pour Grande Centrale',
                'profile_url' => '#',
                'reviews' => 45,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://img.freepik.com/premium-vector/logo-design-featuring-stylized-human-figure_1314744-187.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Formation aux métiers du BTP',
                'title' => 'Centre de Formation RGE Qualibat et FEEBAT',
                'profile_url' => '#',
                'reviews' => 150,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://dynamic.design.com/asset/logo/9ce6e848-42b7-4a6c-8b08-f824010dcf1b/logo-search-grid-2x?logoTemplateVersion=2&v=638863466961430000',
                'alt_text' => 'Cours d`étude thermique',
                'title' => 'Stage "Étude Thermique et RE 2020"',
                'profile_url' => '#',
                'reviews' => 85,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/i/95965191-fd9b-43bd-b32a-1a00e55b0e02/d2pgs6a-8006bf26-2f11-4158-b9a1-d024688b8433.jpg',
                'alt_text' => 'Formation Sécurité sur chantier (CACES)',
                'title' => 'Habilitation Travail en Hauteur et CACES',
                'profile_url' => '#',
                'reviews' => 60,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/image-vector/silver-colored-consulting-theme-togetherness-600nw-1731278200.jpg',
                'alt_text' => 'Formation logicielle BIM',
                'title' => 'Tutoriels et Formations Logiciels de Dessin (BIM)',
                'profile_url' => '#',
                'reviews' => 75,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://static.vecteezy.com/system/resources/thumbnails/019/073/761/small/matrix-logo-unit-matrix-dummy-company-name-logo-free-vector.jpg',
                'alt_text' => 'Logo organisme de certification',
                'title' => 'Organisme de Qualification RGE National',
                'profile_url' => '#',
                'reviews' => 90,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/image-vector/abstract-technology-logo-template-corporate-600nw-1786736897.jpg',
                'alt_text' => 'Audit énergétique d`un bâtiment',
                'title' => 'Bureau d`Études et Audit Énergétique',
                'profile_url' => '#',
                'reviews' => 110,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/free-vector/fashion-shop-gradient-logo-design_474888-4580.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Inspection de chantier',
                'title' => 'Contrôle Technique des Constructions',
                'profile_url' => '#',
                'reviews' => 55,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/premium-vector/business-logo-design-concept_761413-7694.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Certification de produit (norme NF)',
                'title' => 'Certification de Produits Bâtiment (NF, CSTB)',
                'profile_url' => '#',
                'reviews' => 30,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://img.freepik.com/premium-vector/beautiful-unique-logo-design-ecommerce-retail-company_1287271-14617.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Logiciel CRM BTP',
                'title' => 'Logiciel CRM Spécialisé Bâtiment',
                'profile_url' => '#',
                'reviews' => 80,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcREfhXsck1Z96NDFvDRXkxq9wKKHueqd_gIIosiMJOYZ_dImQq64JKzCWsWsEhXE9juD4g&usqp=CAU',
                'alt_text' => 'Scanner laser 3D de bâtiment',
                'title' => 'Scanner Laser 3D et Modélisation Numérique',
                'profile_url' => '#',
                'reviews' => 45,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://dynamic.brandcrowd.com/asset/logo/3e503013-fae1-4ec0-8c0b-3f682e1b95c4/logo-search-grid-2x?logoTemplateVersion=3&v=638902153977570000',
                'alt_text' => 'Système d`objets connectés (IoT) pour maison',
                'title' => 'Solution IoT pour Gestion d`Énergie Domotique',
                'profile_url' => '#',
                'reviews' => 65,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://dynamic.brandcrowd.com/asset/logo/2ba5beeb-c552-4593-8d98-d42ff1c179ce/logo-search-grid-2x?logoTemplateVersion=1&v=638946218171700000',
                'alt_text' => 'Logiciel de devis et facturation BTP',
                'title' => 'Logiciel Devis/Facturation pour Artisans',
                'profile_url' => '#',
                'reviews' => 105,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://img.freepik.com/free-vector/2025-most-trusted-brand-best-award-badge-design_1017-59464.jpg?semt=ais_incoming&w=740&q=80',
                'alt_text' => 'Agence de matériaux de construction professionnelle',
                'title' => 'Agence de Distribution Pro de Matériaux Bio-sourcés',
                'profile_url' => '#',
                'reviews' => 190,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://img.freepik.com/free-vector/best-brand-golden-luxury-label_1017-8434.jpg?semt=ais_hybrid&w=740&q=80',
                'alt_text' => 'Location de mini-pelle',
                'title' => 'Location de Mini-Pelles et Engins de Chantier',
                'profile_url' => '#',
                'reviews' => 75,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/shutterstock/photos/1959869302/display_1500/stock-vector-gold-and-silver-circle-star-logo-template-1959869302.jpg',
                'alt_text' => 'Réseau de chauffagistes partenaires',
                'title' => 'Réseau d`Installateurs Partenaires Chauffage/PAC',
                'profile_url' => '#',
                'reviews' => 115,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://www.shutterstock.com/image-vector/gold-star-logo-vector-illustration-600w-2473466059.jpg',
                'alt_text' => 'Dépôt de matériel professionnel',
                'title' => 'Dépôt d`Outillage Électroportatif Pro',
                'profile_url' => '#',
                'reviews' => 88,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://www.shutterstock.com/image-vector/golden-star-shape-template-light-600w-2316201647.jpg',
                'alt_text' => 'Isolant en lin et coton recyclé',
                'title' => 'Isolant Textile Recyclé Lin/Coton',
                'profile_url' => '#',
                'reviews' => 95,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/golden-star-badge%2Cgolden-star-logo-design-template-75af54e5e5c9d030db2d222c1a814a5c_screen.jpg?ts=1694183496',
                'alt_text' => 'Bois de construction local certifié',
                'title' => 'Bois de Construction Issu de Filière Locale',
                'profile_url' => '#',
                'reviews' => 70,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTK2QRn-DOC58iLm5HYIDXy8y_xZK8pk_cyoxGyv__Jc7FhOfoR-n0KROkqIYv4IEnI2y8&usqp=CAU',
                'alt_text' => 'Blocs de terre crue',
                'title' => 'Matériaux en Terre Crue (Briques et Enduits)',
                'profile_url' => '#',
                'reviews' => 40,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://png.pngtree.com/element_pic/00/16/09/2057e0eecf792fb.jpg',
                'alt_text' => 'Plateforme de recyclage des gravats',
                'title' => 'Plateforme de Valorisation des Déchets BTP',
                'profile_url' => '#',
                'reviews' => 25,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://img.freepik.com/premium-vector/colorful-lion-logo-template_9569-151.jpg',
                'alt_text' => 'Fenêtre aluminium triple vitrage',
                'title' => 'Fenêtres Aluminium RT 2020 Triple Vitrage',
                'profile_url' => '#',
                'reviews' => 180,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/lion-logo-painting-design-template-8f8de9b32371b689a95e60433bfe5580_screen.jpg?ts=1671730455',
                'alt_text' => 'Portes intérieures en bois massif',
                'title' => 'Gamme de Portes Intérieures Isophoniques',
                'profile_url' => '#',
                'reviews' => 95,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkbpoX91cyxfG8vGW9CO1hGoHoprIRdyvmt1Buhftf-gwx5mt8g1L1BHJlSNtkK0rEtfU&usqp=CAU',
                'alt_text' => 'Volet battant en composite',
                'title' => 'Volets Battants en Composite sans entretien',
                'profile_url' => '#',
                'reviews' => 60,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRoo4QdxTtuyD0FzmbZpqiTgfzb9Ei8qTUH6tkRfiQhFvL4Kz92j0i29hCTtzbhRhQrk4A&usqp=CAU',
                'alt_text' => 'Porte de garage sectionnelle isolante',
                'title' => 'Porte de Garage Sectionnelle Isolée',
                'profile_url' => '#',
                'reviews' => 70,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3FyxNzax4VcjNzsBEh99mul05nIi9oOlCLt1l1ZBWhehXmFwgaeJ0ONBWnDRY75Ruf-o&usqp=CAU',
                'alt_text' => 'Béton luminescent',
                'title' => 'Béton et Revêtements de Sol Luminescents',
                'profile_url' => '#',
                'reviews' => 110,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/salinan-best-logo-design-for-business%2C-brand%2C-template-0ef474784819ceed03efd565ef3d3f87_screen.jpg?ts=1636837588',
                'alt_text' => 'Imprimante 3D de construction',
                'title' => 'Service d`Impression 3D pour Maquette/Élément',
                'profile_url' => '#',
                'reviews' => 45,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSRxmdNJW2KkoMYchVGwXKRYVsIKVijDwXuDoGAoFixCwxp7PyZTIvjWYEUfykxpR4mrjU&usqp=CAU',
                'alt_text' => 'Vitrage à opacification contrôlée',
                'title' => 'Vitrage Intelligent à Opacité Variable',
                'profile_url' => '#',
                'reviews' => 65,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwcz3vWCR0zvwo_KzI6fKWHsvSNnbJh6e2yyAEo1ewZHIwpxmncSLt291w6SUrz7YV9_A&usqp=CAU',
                'alt_text' => 'Revêtement extérieur anti-pollution',
                'title' => 'Peinture/Enduit Dépolluant et Anti-Graffiti',
                'profile_url' => '#',
                'reviews' => 35,
                'rating' => 4,
            ]
        ]
    ],
    [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR0SqRT-krz7S45PTf2eIPWLQo0xVdFaHLxo0_6zufVt1Uoggrn03Lb4Y51BI3Jlm_feNI&usqp=CAU',
                'alt_text' => 'Caméra thermique',
                'title' => 'Caméra Thermique pour Diagnostic Bâtiment',
                'profile_url' => '#',
                'reviews' => 130,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDLjivK6gimceZv-55mE-bZtjBfzztdXPB1gp3yJL-daaSj91fREx5rIbT0O6RQJ6nGCs&usqp=CAU',
                'alt_text' => 'Scie circulaire sans fil professionnelle',
                'title' => 'Scies et Tronçonneuses Professionnelles Sans Fil',
                'profile_url' => '#',
                'reviews' => 90,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/brand-logo-design-template-07e76c1085e67d2db0da4bf3de292f1a_screen.jpg?ts=1714703446',
                'alt_text' => 'Niveau laser rotatif',
                'title' => 'Niveau Laser Rotatif Autocalibré',
                'profile_url' => '#',
                'reviews' => 55,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/brand-logo-design-template-b1fdf5e0be94bc4845a0e680f6e57a25_screen.jpg?ts=1711686656',
                'alt_text' => 'Équipements de protection individuelle (EPI)',
                'title' => 'Équipements de Protection Individuelle (EPI) Pro',
                'profile_url' => '#',
                'reviews' => 70,
                'rating' => 4,
            ]
        ]
            ],
     [
        'listings' => [
            [
                'is_sponsored' => true,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/brand-logo-design-template-b1fdf5e0be94bc4845a0e680f6e57a25_screen.jpg?ts=1711686656',
                'alt_text' => 'Caméra thermique',
                'title' => 'Caméra Thermique pour Diagnostic Bâtiment',
                'profile_url' => '#',
                'reviews' => 130,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/brand-logo-design-template-171b8c3ed4c64042926d7e2b3369dfd6_screen.jpg?ts=1715320851',
                'alt_text' => 'Scie circulaire sans fil professionnelle',
                'title' => 'Scies et Tronçonneuses Professionnelles Sans Fil',
                'profile_url' => '#',
                'reviews' => 90,
                'rating' => 5,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT02dT326b6YyMAYx1wbYeTlN1Fi0vunTGoBgKZXEvBx91vSNW9tNfOA_5cgA0cDOB1pI4&usqp=CAU',
                'alt_text' => 'Niveau laser rotatif',
                'title' => 'Niveau Laser Rotatif Autocalibré',
                'profile_url' => '#',
                'reviews' => 55,
                'rating' => 4,
            ],
            [
                'is_sponsored' => false,
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT02dT326b6YyMAYx1wbYeTlN1Fi0vunTGoBgKZXEvBx91vSNW9tNfOA_5cgA0cDOB1pI4&usqp=CAU',
                'alt_text' => 'Équipements de protection individuelle (EPI)',
                'title' => 'Équipements de Protection Individuelle (EPI) Pro',
                'profile_url' => '#',
                'reviews' => 70,
                'rating' => 4,
            ]
        ]
    ]
];

?>


<div class="container-fluid ta-section-container">
    <div class="container">

        <section class="ta-section">
            <div class="ta-section-header row">
                <h2>Expériences Populaires </h2>
                <p>Découvrez ce que d'autres naviguent comme vous acheter lors du tri des notes des équipements et leur nombre d'abonnements</p>
                <!-- <button class="btn ta-btn-green pull-right">Tout afficher</button> -->
            </div>
            <div class="ta-filter-chips row">
            <?php while($result = mysql_fetch_assoc($data2)) {?>
                <button class="btnn ta-chip-btn <?php echo $result['service_id']; ?>"> <?php echo $result['name']; ?></button>
            <?php } ?>
             <button class="btnn ta-chip-btn view_all"> View All</button>
            </div>
        </section>

       <section class='scroll-section'>
            <p class='text-center header-brand'>MARQUES EN VEDETTE</p>

            <div class="marquee">
                <div class="marquee-content" id="brandsContainer">
                    <?php 
                    while ($brands_fetch = mysql_fetch_assoc($brands_data)) {?>
                    <span class='brand-item'>
                        <div class="brand-item-inner"> 
                            <div class='brand-icon' bis_skin_checked='1'>
                                <span class='text-xl'>📢</span>
                            </div> 
                            <div>
                                <?php echo $brands_fetch['company']; ?>
                            </div>
                        </div>
                    </span>
                   <?php } ?>
                </div>
            </div>

        </section>


    
    <?php 
    $count = 0; 
        while ($category = mysql_fetch_assoc($data)) { 
            
        ?>
        
            <section class="ta-section" id="<?php echo htmlspecialchars($category['service_id']); ?>">
                
                <div class="ta-section-header row">     
                    <div class="col-sm-6 col-xs-6 header-div"><h2><?php echo htmlspecialchars($category['name']); ?></h2> &nbsp;<a href="<?php echo htmlspecialchars($category['filename']); ?>"><i class="fa fa-external-link"></i></a></div>
                    <div class="col-sm-6 col-xs-6">
                        <!-- <a class="btn ta-btn-green pull-right" href="<?php echo htmlspecialchars($category['filename']); ?>" target="_blank">Tout afficher</a> -->
                        <div class='scroll-btn'>
                            <p class='left-btn'><i class="fa fa-angle-left" style="font-size:24px"></i></p>
                            <p class='right-btn'><i class="fa fa-angle-right" style="font-size:24px"></i></p>
                        </div>
                    </div>             
                </div>

                <div class="marquee-wrapper">
                    <div class="marquee-track">
                        <div class="row ta-card-grid">
                            <?php foreach($categories[$count]['listings'] as $listingData) { ?>
                                <div class="col-xs-12 col-sm-3 col-md-3 card">
                                    <div class="ta-card ta-listing-card">
                                        <img src="<?php echo $listingData['image_url']; ?>" alt="<?php echo $listingData['alt_text'];?>">
                                        <div class="ta-card-content">
                                            <span class="ta-sponsored-tag"><?php echo $listingData['is_sponsored']?'':''; ?></span>
                                            <h4><a href="#"><?php echo $listingData['title']; ?></a></h4>
                                            <div class="rating-bubbles">
                                                <div class='text-center rating-bubbles-inner'>
                                                    <span class='star'> ★★★★★ </span>
                                                    <span class="review-count">205 avis</span>
                                                </div>
                                            </div>
                                            
                                            <button class="btn ta-btn-green">Voir le profil</button>
                                        </div>
                                    </div>
                                </div>
                            <?php   } ?>
                        </div>
                        <div class="row ta-card-grid">
                            <?php foreach($categories[$count]['listings'] as $listingData) { ?>
                                <div class="col-xs-12 col-sm-3 col-md-3 card">
                                    <div class="ta-card ta-listing-card">
                                        <img src="<?php echo $listingData['image_url']; ?>" alt="<?php echo $listingData['alt_text'];?>">
                                        <div class="ta-card-content">
                                            <span class="ta-sponsored-tag"><?php echo $listingData['is_sponsored']?'':''; ?></span>
                                            <h4><a href="#"><?php echo $listingData['title']; ?></a></h4>
                                            <div class="rating-bubbles">
                                                <div class='text-center rating-bubbles-inner'>
                                                    <span class='star'> ★★★★★ </span>
                                                    <span class="review-count">205 avis</span>
                                                </div>
                                            </div>
                                            
                                            <button class="btn ta-btn-green">Voir le profil</button>
                                        </div>
                                    </div>
                                </div>
                            <?php   } ?>
                        </div>
                    </div>
                </div>

            </section>
        <?php $count=$count+1; }  ?>

    </div>
</div> 
<script>
    const marquee = document.querySelector('#brandsContainer');
    marquee.innerHTML += marquee.innerHTML; 

</script>
<style>
.scroll-section{
    background-color: #152d390d;
    border-radius: 10px;
    padding: 20px;
    
}
.brand-icon {
    height: 40px;
    width: 40px;
    background-color: #eeeff0;
    border-radius: 100%;
    font-size: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
   
}
	..active2:hover{
		color:white !important;
	}
.marquee {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
}

.header-brand{
    text-align: center;
    font-size: 12px;
    font-weight: bold;
    color: #0000009e;
}
.brand-item {
    font-size: 10px;
    font-weight: 100;
    padding: 0 5px;
    overflow: hidden;
    width: 150px;
    height: 100px;
    
}

.brand-item-inner{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    background-color: #fff;
    height: 100%;
    width: 100%;
    border: 1px dashed black;
    text-align: center;
    border: 2px dashed #00000029;
    border-radius: 5px;
}


@keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

.marquee-content {
    display: inline-flex;
    animation: scroll-left 200s linear infinite;
}


.marquee-content {
    display: inline-flex;
    animation: scroll-left 60s linear infinite; 
}


.ta-section-container {
    padding: 30px 0; 
    
     
    width: 90%;
    margin: 0 auto;
}
.ta-section {
    margin: 80px 0px; 
}

.ta-section-header {
    margin-bottom: 20px;
    
}
.header-div{
    flex-direction: row;
    display: flex;
    justify-content: flex-start;
    align-items: center;
}
.ta-section-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #000;
    margin-top: 0;
    margin-bottom: 5px;
}
.ta-section-header p {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}
.ta-btn-green {
    background-color: #0d1b3e;
    color: #fff;
    border: none;
    padding: 8px 15px;
    font-weight: 600;
    transition: background-color 0.2s ease;
}
.ta-btn-green:hover {
    background-color: #0d1b3e !important;
    color: #fff !important;
}
.scroll-btn{
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 16px;
    float: right;
}
.scroll-btn p {
    background-color: #e5e7eb;
    border-radius: 100%;
    padding: 7.5px 15px;
    margin: 0px 5px;
}
.scroll-btn p:hover {
    background-color: #d1d5db;
    transform :color 0.8s ease;
}
.ta-card:hover {
    border: 3px solid #e9321a;
    box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.25);
    transition: box-shadow 0.3s ease, border 0.3s ease;
}

.scroll-btn p {
    transition: background-color 0.2s ease;
}

.scroll-btn p:hover {
    background-color: #d1d5db;
}

.ta-section-header .pull-right {
    
    top: 0;
    right: 0;
}

.ta-section-header p + .pull-right {
    top: 30px; 
}
.ta-filter-chips {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    align-items: baseline;
    justify-content: flex-start;
    padding: 30px 0px;
    border-bottom: 1px solid #00000042;
}
.ta-chip-btn {
    background-color: #fff;
    border: 1px solid #28282826;
    margin-right: 10px;
    margin-bottom: 10px;
    color:  #000000ff;
    font-weight: 500;
}
.ta-chip-btn i {
    margin-right: 5px;
    color: #666; 
}

.ta-card-grid {
    margin-left: -10px; 
    margin-right: -10px;
    display: flex; 
    flex-wrap: wrap; 
}
.ta-card-grid > [class*="col-"] {
    padding-left: 10px; 
    padding-right: 10px;
    margin-bottom: 20px; 
}


.ta-card {
    background-color: #fff;
    border-radius: 15px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 3px solid #0000001a;
    transition: border 0.8s ease; 
    width: 270px;
}


.ta-card:hover {
    border: 3px solid #e9321a;
}

.ta-card:hover a{
    color: #e9321a;
}

.ta-card img {
    width: 100%;
    height: 180px; 
    object-fit: cover;
    display: block;
    padding: 15px;
    padding-bottom: 0px;
    border-radius: 20px;
}

.ta-card-content {
    padding: 15px;
    flex-grow: 1; 
    display: flex;
    flex-direction: column;
	justify-content: end;
    text-align: center;
}

.heart-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: rgba(0,0,0,0.4);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    z-index: 10;
}
.heart-icon:hover {
    background-color: rgba(0,0,0,0.6);
}



.ta-city-card {
    position: relative; 
}
.ta-city-card h4 {
    font-size: 18px;
    font-weight: 600;
    margin-top: 5px;
    margin-bottom: 2px;
    color: #000;
}
.ta-city-card p {
    font-size: 13px;
    color: #666;
    margin-bottom: 0;
}

.ta-listing-card img {
    height: 150px; 
}

.ta-listing-card h4 {
    font-size: 16px;
    font-weight: 600;
    margin-top: 5px;
    margin-bottom: 5px;
    color: #000;
    line-height: 1.3;
}
.ta-listing-card h4 a {
    color: inherit;
    text-decoration: none;
    transition: color 0.5s ease;
}


.rating-bubbles {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap; 
}
.rating-bubbles-inner{
    display: flex;
    width: 100%;
    justify-content: center;
    align-items: center;
    padding-top: 10px;
}
.star{
    color: #22c55e;
    font-size: 24px;
}
.rating-bubble {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background-color:#22c55e; 
    margin-right: 3px;
    flex-shrink: 0; 
}
.rating-bubble.empty {
    background-color: #e0e0e0;
}
.review-count {
    margin-left: 8px;
    font-size: 13px;
    color: #555;
    flex-shrink: 0;
}

.ta-sponsored-tag {
    font-size: 12px;
    color: #888;
    margin-bottom: 5px;
    display: block;
}

.ta-price {
    font-size: 15px;
    color: #555;
    margin-top: auto; 
    margin-bottom: 15px;
}
.ta-price span {
    font-weight: bold;
    color: #000;
    font-size: 1.2em; 
}
.active2{
    background-color: black;
    color: white;
}
.ta-listing-card .btn {
    width: 100%; 
    margin-top: 10px; 
}


.ta-card-grid-scroll {
    overflow-x: auto; 
    white-space: nowrap; 
    -webkit-overflow-scrolling: touch; 
    padding-bottom: 15px; 
    position: relative; 
    margin-left: 0; 
    margin-right: 0;
}
.ta-card-grid-scroll .col-xs-12 { 
    flex: 0 0 100%;
    max-width: 100%;
}
.ta-card-grid-scroll .col-sm-3 { 
    flex: 0 0 50%;
    max-width: 50%;
}
.ta-card-grid-scroll .col-md-3 { 
    flex: 0 0 25%;
    max-width: 25%;
}
.ta-card-grid-scroll .ta-scroll-item {
    display: inline-block; 
    float: none; 
    vertical-align: top; 
    white-space: normal; 
}


.ta-card-grid-scroll > [class*="col-"] {
    padding-left: 10px;
    padding-right: 10px;
}
.btnn {
    margin-right: 10px;
    margin-bottom: 10px;
    border: 1px solid black;
    display: inline-block;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid #28282826;
    border-radius: 5px;
}

.ta-scroll-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #333;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 20;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}



.ta-scroll-left {
    left: -20px; 
}
.ta-scroll-right {
    right: -20px; 
}


.ta-card-grid-scroll::-webkit-scrollbar {
    display: none;
}
.ta-card-grid-scroll {
    -ms-overflow-style: none;  
    scrollbar-width: none;  
}


@media (max-width: 767px) { 
    .ta-section-header .pull-right {
       
        display: block;
        margin-top: 15px; */
       
    }
    .ta-chip-btn {
        
        
    }
    .active2{
        background-color: black;
        color: white;
        outline: 0;
      
    }
    .ta-card-grid-scroll {
        overflow-x: scroll; 
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        padding-bottom: 15px;
    }
    .ta-card-grid-scroll > [class*="col-"] {
        display: inline-block;
        float: none;
        vertical-align: top;
        white-space: normal;
        width: 80%; 
        max-width: 80%;
    }
    .ta-scroll-btn {
        display: none; 
    }
}

@media (min-width: 768px) and (max-width: 991px) { 
    .ta-card-grid-scroll > [class*="col-"] {
        width: 100%;
        max-width: 50%;
    }
}
@media (min-width: 992px) { 
    .ta-card-grid-scroll > [class*="col-"] {
        width: 25%;
        max-width: 25%;
    }
}



/* Slower / Faster speed? adjust 45s above */
@keyframes scrollCards {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}

.marquee-wrapper {
    overflow-x: auto;
    white-space: nowrap;
    scroll-behavior: smooth;
}
.ta-card-grid{
    margin-right: 10px;
}
.marquee-wrapper::-webkit-scrollbar {
    display: none;
}

.marquee-track {
    display: flex;
    width: max-content;
    animation: scrollCards 45s linear infinite;
    animation-play-state: paused; /* stop by default */
}

.marquee-wrapper:hover .marquee-track {
    animation-play-state: running; /* start on hover */
}

@keyframes scrollCards {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(-50%);
    }
}
</style>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const marquees = document.querySelectorAll('.marquee-wrapper');

    marquees.forEach(wrapper => {
        const track = wrapper.querySelector('.marquee-track');
        const leftBtn = wrapper.parentElement.querySelector('.left-btn');
        const rightBtn = wrapper.parentElement.querySelector('.right-btn');

        const step = 300;

        if (rightBtn) {
            rightBtn.addEventListener("click", () => {
                wrapper.scrollBy({ left: step, behavior: "smooth" });
            });
        }

        if (leftBtn) {
            leftBtn.addEventListener("click", () => {
                wrapper.scrollBy({ left: -step, behavior: "smooth" });
            });
        }
    });
});
</script>
