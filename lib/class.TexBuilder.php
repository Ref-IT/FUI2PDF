<?php

/**
 * @author michael
 *
 */
class TexBuilder{
    // member variables ----------------
    /**
     * vailidator map
     *
     * @var array
     */
    private static $valiMap = [
        'belegpdf' => [
            'projekt' => [
                'arraymap',
                'required' => true,
                'map' => [
                    'created' => [
                        'date',
                        'format' => 'Y-m-d H:i:s',
                        'parse' => 'Y-m-d H:i:s',
                        'error' => 'Ungültiges Projekt Datum.'
                    ],
                    'name' => [
                        'regex',
                        'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                        'maxlength' => '255',
                        'empty',
                        'error' => 'Ungültiger Projekt Name.'
                    ],
                    'org' => [
                        'regex',
                        'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                        'maxlength' => '255',
                        'empty',
                        'error' => 'Ungültiger Organisations Name.'
                    ],
                    'id' => [
                        'integer',
                        'min' => '1',
                        'error' => 'Ungültige Projekt ID.'
                    ],
                ],
            ],
            'auslage' => [
                'arraymap',
                'required' => true,
                'map' => [
                    'created' => [
                        'date',
                        'format' => 'Y-m-d H:i:s',
                        'parse' => 'Y-m-d H:i:s',
                        'error' => 'Ungültiges Auslagen Datum.'
                    ],
                    'created_by' => [
                        'name',
                        'maxlength' => '255',
                        'error' => 'Ungültiger Auslagen Name.'
                    ],
                    'name' => [
                        'regex',
                        'empty',
                        'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                        'maxlength' => '255',
                        'error' => 'Ungültiger Auslagen Name.'
                    ],
                    'id' => [
                        'integer',
                        'min' => '1',
                        'error' => 'Ungültige Auslagen ID.'
                    ],
                    'zahlung' => [
                        'arraymap',
                        'required' => true,
                        'map' => [
                            'name' => [
                                'regex',
                                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                                'maxlength' => '127',
                                'empty',
                                'error' => 'Ungültiger Zahlungs Name.'
                            ],
                        ],
                    ],
                    'address' => [
                        'regex',
                        'pattern' => '/^[a-zA-Z0-9\-_,:;\/\\\\()& \n\r\.\[\]%\'"#\*\+äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                        'empty',
                        'maxlength' => '1023',
                        'error' => 'Adressangabe fehlerhaft.',
                    ],
                ],
            ],
            'belege' => [
                'array',
                'optional',
                'minlength' => 1,
                'key' => [
                    'regex',
                    'pattern' => '/^(\d+)$/'
                ],
                'validator' => [
                    'arraymap',
                    'required' => true,
                    'map' => [
                        'id' => [
                            'integer',
                            'min' => '1',
                            'error' => 'Ungültige Beleg ID.'
                        ],
                        'short' => [
                            'integer',
                            'min' => '1',
                            'error' => 'Ungültige Beleg NR.'
                        ],
                        'date' => [
                            'date',
                            'format' => 'Y-m-d H:i:s',
                            'parse' => 'Y-m-d',
                            'error' => 'Ungültiges Beleg Datum.'
                        ],
                        'desc' => [
                            'text',
                            'strip',
                            'trim',
                        ],
                        'file_id' => [
                            'integer',
                            'min' => '1',
                            'error' => 'Ungültige Beleg File ID.'
                        ],
                        'file' => [
                            'text',
                            'strip',
                            'trim',
                        ],
                    ]
                ]
            ],
        ],
        'gremienbescheinigung' => [
            'vorname' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'empty',
                'error' => 'Ungültiger Vorname.'
            ],
            'name' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'empty',
                'error' => 'Ungültiger Name.'
            ],
            'adresse' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'empty',
                'error' => 'Ungültige Adresse.'
            ],
            'ort' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'empty',
                'error' => 'Ungültiger Ort.'
            ],
            'male' => [
                'boolean',
                'error' => 'Wert für male.'
            ],
            'geburtsdatum' => [
                'date',
                'format' => 'd.m.Y',
                'error' => 'Ungültiges Geburtsdatum.'
            ],
            'date' => [
                'date',
                'format' => 'd.m.Y',
                'error' => 'Ungültiges Datum.'
            ],
            'sum' => [
                'integer',
                'min' => 0,
                'error' => 'Ungültige Summe'
            ],
            'smallest' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'error' => "Ungültiges Datum 'smallest'."
            ],
            'biggest' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'error' => "Ungültiges Datum 'biggest'."
            ],
            'konsul' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'error' => "Ungültiger Konsul name."
            ],
            'skills' => [
                'array',
                'validator' => [
                    'regex',
                    'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                    'maxlength' => '500',
                    'error' => "Ungültiger Skill."
                ],
            ],
            'arbeit' => [
                'array',
                'empty',
                'validator' => [
                    'arraymap',
                    'required' => true,
                    'map' => [
                        'checked' => [
                            'integer',
                            'min' => '0',
                            'error' => 'Some id checking error.'
                        ],
                        'von' => [
                            'date',
                            'format' => 'Y-m-d',
                            'error' => 'Ungültiges "von" Datum.'
                        ],
                        'bis' => [
                            'date',
                            'empty',
                            'format' => 'Y-m-d',
                            'error' => 'Ungültiges "bis" Datum.'
                        ],
                        'position' => [
                            'text',
                            'strip',
                            'trim',
                        ],
                        'gremium' => [
                            'text',
                            'strip',
                            'trim',
                        ],
                        'h' => [
                            'integer',
                            'min' => '0',
                            'error' => 'Ungültige Stunden Zahl.'
                        ],
                        'type' => [
                            'regex',
                            'pattern' => '#^h(/W|/S)?$#',
                            'maxlength' => '4',
                            'error' => "Ungültiger type name."
                        ],
                    ]
                ]
            ],
            'additional-text' => [
                'text',
                'empty',
                'strip',
                'trim',
            ],
        ],
        'zahlungsanweisung' => [
             'short-type-projekt' => [
                 'regex',
                 'pattern' => '/[A-Z]{2}/',
                 'maxlength' => '2',
                 'error' => 'Kein short-type-projekt',
             ],
            'projekt-id' => ['id'],
            'projekt-name' => ['text'],
            'projekt-org' => ['text'],
            'projekt-recht' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'error' => 'Ungültiges Recht.'
            ],
            'projekt-create' => [
                'date',
                'format' => 'Y-m-d H:i:s',
                'parse' => 'y',
                'error' => 'Kein Erstellungsdatum des Projektes',
            ],
            'short-type-auslage' => [
                'regex',
                'pattern' => '/[A-Z]/',
                'maxlength' => '1',
                'error' => 'Kein short-type-auslage',
            ],
            'auslage-id' => ['id'],
            'auslage-name' => ['text'],
            'zahlung-name' => [
                'regex',
                'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
                'maxlength' => '255',
                'error' => 'Ungültiger Empfänger Name.'
            ],
            'zahlung-iban' => [
                'iban',
				'empty',
            ],
            'zahlung-value' => [
                'float',
                'format' => '2',
                'decimal' => '.',
                'parse' => [
                        'append' => ' EUR'
                ],
                'error' => 'Ungültiger Value'
            ],
            'zahlung-adresse' => ['text'],
            'angewiesen-date' => [
                'date',
                'optional',
                'format' => 'Y-m-d H:i:s',
                'parse' => 'd.m.Y',
                'error' => 'angewiesen-date ist falsch.',
            ],
            'details' => [
                'array',
                'optional',
                'empty',
                //'minlength' => 1,
                'key' => [
                    'regex',
                    'pattern' => '/^(\d+)$/'
                ],
                'validator' => [
                    'arraymap',
                    'required' => true,
                    'map' => [
                        'beleg-id' => [
                            'text'
                        ],
                        'titel' => [
                            'text', 
                            'trim',
                            'regex',
                            'pattern' => '/^[0-9 ]*$/',
                            'maxlength' => '255',
                            'error' => 'Ungültiger Titel'
                        ],
                        'einnahmen' => [
                            'float',
                            'min' => '0',
                            'format' => '2',
                            'decimal' => '.',
                            'parse' => [
                                    'append' => ' EUR'
                            ],
                            'error' => 'Ungültige Einnahme'
                        ],
                        'ausgaben' => [
                            'float',
                            'min' => '0',
                            'format' => '2',
                            'decimal' => '.',
                            'parse' => [
                                    'append' => ' EUR'
                            ],
                            'error' => 'Ungültige Ausgabe'
                        ],
                    ]
                ]
            ]
        ],
        'protocolmemberlist' => [
            'date' => [
                'date',
                'format' => 'Y-m-d',
                'parse' => 'd.m.Y',
                'error' => 'Kein Datum angegeben',
            ],
			'leitung' => [
                'regex',
				'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
				'maxlength' => 255,
				'minlength' => 2,
				'empty',
				'error' => 'Ungültige Sitzungsleitung.'
			],
			'protocol' => [
                'regex',
				'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
				'maxlength' => 255,
				'minlength' => 2,
				'empty',
				'error' => 'Ungültige Protokollleitung.'
			],
			'nth' => [
                'integer',
				'min' => 1,
				'empty',
				'error' => 'Ungültiger NTH Eintrag.'
			],
			'legislatur' => [
                'integer',
				'min' => 1,
				'error' => 'Ungültige Legislatur.'
			],
            'member_elected' => [
                'array',
				'optional',
                'empty',
                'error' => 'Missing key member_elected',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'name' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'error' => 'Ungültiger Name.'
						],
						'job' => [
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
                            'empty',
							'error' => 'Ungültiger Job.'
						],
						'text' => [
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => '255',
							'empty',
							'error' => 'Ungültiger text.'
						],
                    ]
                ],
            ],
            'member_active' => [
                'array',
				'optional',
				'empty',
				'error' => 'Missing key member_active',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'name' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'error' => 'Ungültiger Name.'
						],
						'job' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'empty',
							'error' => 'Ungültiger Job.'
						],
						'text' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => '255',
							'empty',
							'error' => 'Ungültiger text.'
						],
					]
				],
            ],
            'member_ref' => [ 
                'array',
				'optional',
				'empty',
				'error' => 'Missing key member_ref',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'name' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'error' => 'Ungültiger Name.'
						],
						'job' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'empty',
							'error' => 'Ungültiger Job.'
						],
						'text' => [ 
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => '255',
							'empty',
							'error' => 'Ungültiger text.'
						],
					]
				],
            ],
            'member_stuff' => [ 
                'array',
				'optional',
				'empty',
				'error' => 'Missing key member_stuff',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'name' => [
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'error' => 'Ungültiger Name.'
						],
						'job' => [
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 2,
							'empty',
							'error' => 'Ungültiger Job.'
						],
						'text' => [
                            'regex',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => '255',
							'empty',
							'error' => 'Ungültiger text.'
						],
					]
				],
            ],
        ],
		'inventorylist' => [
			'date' => [
                'date',
				'format' => 'Y-m-d',
				'parse' => 'd.m.Y',
				'error' => 'Kein Datum angegeben',
			],
			'header' => [ 
                'array',
				'empty',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'title' => [
                            'regex',
							'empty',
							'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
							'maxlength' => 255,
							'minlength' => 0,
							'error' => 'Ungültiger Titel.'
						],
						'date' => [
                            'regex', 
                            'optional',
							'pattern' => '/^([0-9a-zA-Z_ :,\.\-\/])+$/',
							'maxlength' => 255,
							'minlength' => 1,
							'empty',
							'error' => 'Ungültiges Datumformat.'
						],
						'table_format' => [
                            'regex', 
                            'optional',
							'pattern' => '/^([a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()\{\}])*$/',
							'maxlength' => '255',
							'empty',
							'error' => 'Ungültiges Tabellen format.'
						],
						'center' => [ 
                            'integer', 
                            'optional',
							'min' => 1,
							'max' => 1,
							'error' => 'Ungültiges flag "center"',
						],
					],
				],
				'key' => [
                    'regex',
					'pattern' => '/^[a-zA-Z0-9\-_ :,;%$§\&\+\*\.!\?\/\\\[\]\'"#~()äöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß]*$/',
					'maxlength' => 255,
					'minlength' => 1,
					'error' => 'Ungültiger headerkey Eintrag.'
				]
			],
			'items' => [ 
                'array',
				'pre_json_decode',
				'empty',
				'false',
				'error' => 'Ungültiger items Eintrag.'
			],
			'modifier' => [
                'array', 
                'optional',
				'empty',
			],
		],
		'inventorydetail' => [
			'date' => [
                'date',
				'format' => 'Y-m-d',
				'parse' => 'd.m.Y',
				'error' => 'Kein Datum angegeben',
			],
			'filedata' => [ 
                'array', 
                'optional',
				'empty',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'data' => [
                            'text',
							'strip',
							'trim',
						],
					],
				],
			],
			'data' => [ 
                'array',
				'empty',
				'required' => true,
			],
			'log' => [ 
                'array', 
                'optional',
				'empty',
				'validator' => [ 
                    'arraymap',
					'required' => true,
					'map' => [
						'id' 			=> [ 
                            'integer',
							'min' => '1',
							'error' => 'id' 
                        ],
						'date' => [ 
                            'date',
							'format' => 'Y-m-d H:i:s',
							'error' => 'date'
						],
						'wer' 	=> [
                            'regex',
							'pattern' => '/^((^| )([.a-zA-ZäöüÄÖÜéèêóòôáàâíìîúùûÉÈÊÓÒÔÁÀÂÍÌÎÚÙÛß0-9#!?\-\.,:;_+\*\(\)\[\]\|\/\\\\]+))+$/',
							'empty',
							'maxlength' => 255,
							'error' => 'Invalid name' 
                        ],
						'was' => [
                            'regex',
							'pattern' => '/( |\n|.)*/',
							'error' => 'Invalid note - Invalid letters?'
						],
					],
				],
			],
		],
    ];

    /**
     *
     * @var Validator
     */
    private $validator;

    /**
     * is error and error message
     *
     * @var bool|string
     */
    private $error = false;

    /**
     * last valid key
     *
     * @var bool|string
     */
    private $last_key = false;

    /**
     * @var binary pdf file data
     */
    private $binary_build;

    // constructor --------------------

    /**
     * class constructor
     */
    function __construct(){
        $this->validator = new Validator();
        $this->error = false;
    }

    // getter / setter --------------------

    /**
     * escape latex invalid letters
     *
     * @param string in
     *
     * @return string
     */
    public static function texEscape($in){
        return str_replace(
            ["\\", '~', '_', '%', '$', '&', '^', '"', '{', '}', '#', '€', 'ẞ'],
            ['\textbackslash', '\textasciitilde', '\_', '\%', '\$', '\&', '^', "''", '\{', '\}', '\#', '\EUR', 'ß'],
            $in
        );
    }

    // helper -----------------------------

    /**
     * @return the $error
     */
    public function getError(){
        return $this->error;
    }

    /**
     * validate Post or $data variables by builderKey
     * alias of validate
     *
     * @param string $key
     * @param array  $data
     *
     * @return bool
     */
    public function setData($key, $data = null){
        return $this->validate($key, $data);
    }

    /**
     * validate Post or $data variables by builderKey
     *
     * @param string $key
     * @param array  $data
     *
     * @return bool
     */
    public function validate($key, $data = null){
        if (!$this->builderExist($key)){
            $this->error = 'Unknown builder.';
            $this->last_key = false;
            return false;
        }else{
            $this->validator->validateMap(($data) ? $data : $_POST, self::$valiMap[$key]);
            if (!$this->validator->getIsError()){
                $this->error = false;
                $this->last_key = $key;
                return true;
            }else{
                if ($this->validator->getLastErrorMsg() == 'Access Denied'){
                    error_log("Error: Access Denied; Target: $key; Description: " . $this->validator->getLastErrorDescription());
                    $this->error = $this->validator->getLastErrorMsg() . ' - ' . $this->validator->getLastErrorDescription();
                }else{
                    $this->error = $this->validator->getLastErrorMsg();
                }
                $this->last_key = false;
                return false;
            }
        }
    }

    /**
     * check if pdf builder exists
     *
     * @return bool
     */
    public function builderExist($key){
        return (isset(self::$valiMap[$key]));
    }

    /**
     * return validated and filtered request data
     *
     * @return mixed
     */
    public function getValidated(){
        return $this->validator->getFiltered();
    }

    // build pdf ------------------------------------

    /**
     * build pdf
     */
    public function build(){
        if (!$this->error && $this->last_key){
            //create images files
            $files = [];
            if (isset($this->validator->getFiltered()['belege'][0]['file'])){
                foreach ($this->validator->getFiltered()['belege'] as $b){
                    if (($f = tempnam(sys_get_temp_dir(), 'tex-tmp-')) === false){
                        $this->error = "Failed to create temporary file";
                        foreach ($files as $v){
                            if (!DEBUG_DO_NOT_DELETE__TEX_PDF && file_exists($v))
                                unlink($v);
                        }
                        return false;
                    }
                    //remove empty file again
                    if (file_exists($f)) unlink($f);
                    //set file with actual content
                    file_put_contents($f . '.pdf', base64_decode($b['file']));
                    $files[str_pad($b['id'], 3, "0", STR_PAD_LEFT) . '-B' . $b['short']] = $f . '.pdf';
                }
            }
			$validated = $this->validator->getFiltered();
			if (isset($validated['filedata'])){
				foreach ($validated['filedata'] as $k => $fs){
					if (($f = tempnam(sys_get_temp_dir(), 'tex-tmp-')) === false){
						$this->error = "Failed to create temporary file";
						foreach ($files as $v){
							if (!DEBUG_DO_NOT_DELETE__TEX_PDF && file_exists($v))
								unlink($v);
						}
						return false;
					}
					//remove empty file again
					if (file_exists($f)) unlink($f);
					//set file with actual content
					$f.=(isset($fs['type']) && $fs['type'])? '.'.$fs['type'] : '.png';
					file_put_contents($f , base64_decode($fs['data']));
					$files[$k] = $f;
					$validated['filedata'][$k]['file'] = $f;
					unset($validated['filedata'][$k]['data']);
				}
			}

            $validated['files'] = $files;
            //get tex code
            $tex = self::_renderTex($this->last_key, $validated);
            //render twice
            $this->_createPDF($tex);
            //remove inline images
            if (!DEBUG_DO_NOT_DELETE__TEX_PDF){
                foreach ($files as $v){
                    if (file_exists($v)) unlink($v);
                }
            }
            return true;
        }
    }

    /**
     * render tex code
     *
     * @param string $key
     * @param array  $param
     *
	 * @return string
     */
    private static function _renderTex($key, $param){
        $file = SYSBASE . '/template/tex/' . $key . '.phpTex';
        $tex = '';

        if (file_exists($file)){
            ob_start();
            include $file;
            $tex = ob_get_clean();
        }
        return $tex;
    }

    /**
     * create pdf file set binary code
     */
    private function _createPDF($tex_code){
        //create temp file
        if (($f = tempnam(sys_get_temp_dir(), 'tex-')) === false){
            $this->error = "Failed to create temporary file";
            return;
        }

        $tex_f = $f . ".tex";
        $aux_f = $f . ".aux";
        $log_f = $f . ".log";
        $pdf_f = $f . ".pdf";

        //write file to tmp file
        file_put_contents($tex_f, $tex_code);
        //switch to directory
        chdir(sys_get_temp_dir());

        //run command
        $shellcmd = SHELL_LATEX_COMMAND." \"\\input{" . $tex_f . '}" 2>&1';

        $status = -100;
        exec($shellcmd, $out, $status);
        //render twice -> for page numbers, etc.
        exec($shellcmd, $out, $status);

        //unlink files
        if (!DEBUG_DO_NOT_DELETE__TEX_PDF && file_exists($tex_f)) unlink($tex_f);
        if (!DEBUG_DO_NOT_DELETE__TEX_PDF && file_exists($aux_f)) unlink($aux_f);
        if (!DEBUG_DO_NOT_DELETE__TEX_PDF && file_exists($log_f)) unlink($log_f);

        // Test here
        if (!file_exists($pdf_f)){
            //unlink files
            if (!DEBUG_DO_NOT_DELETE__TEX_PDF)
                unlink($f);
            $this->error = [
                'msg' => "Output was not generated and latex returned: $status.",
                'code' => $status,
                'log' => $out,
                'tex' => $tex_code,
            ];
            return;
        }

        //load file to memory
        $this->binary_build = file_get_contents($pdf_f);

        //unlink files
        if (!DEBUG_DO_NOT_DELETE__TEX_PDF){
            unlink($pdf_f);
            unlink($f);
        }
    }

    // private ----------------------------------------

    /**
     * get pdf as base64 string
     *
     * @param bool $echo
     *
     * @return string
     */
    public function getBase64($echo = false){
        if ($this->binary_build){
            $t = base64_encode($this->getBinary(false));
            if ($echo) echo $t;
            return $t;
        }
        return null;
    }

    /**
     * get binary pdf data
     *
     * @param bool $echo
     *
     * @return binary|NULL
     */
    public function getBinary($echo = false){
        if ($this->binary_build){
            if ($echo) echo $this->binary_build;
            return $this->binary_build;
        }
        return null;
    }
}

?>
