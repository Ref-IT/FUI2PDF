<?php
$routing = [
    'path' => '',
    'type' => 'path',
    'controller' => 'error',
    'action' => '404',
    'not_found' => '404',
    'method' => 'POST',
    'children' => [
        [
            'path' => 'pdfbuilder',
        	'controller' => 'pdfbuilder',
        	'action' => '',
        	'auth' => 'Basic',
        	'groups' => 'pdfbuilder',
            'type' => 'path',
        ],
    	[
    		'allowall' => true,
	    	'path' => 'old',
	    	'controller' => 'old',
	    	'action' => '',
	    	'type' => 'path',
    	],
    ]
];

