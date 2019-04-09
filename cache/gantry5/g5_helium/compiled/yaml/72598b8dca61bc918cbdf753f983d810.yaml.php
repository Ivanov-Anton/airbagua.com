<?php
return [
    '@class' => 'Gantry\\Component\\File\\CompiledYamlFile',
    'filename' => '/home/dobrod/airbagua.com/www/templates/g5_helium/blueprints/styles/link.yaml',
    'modified' => 1552425395,
    'data' => [
        'name' => 'Link Colors',
        'description' => 'Link colors for the Helium theme',
        'type' => 'core',
        'form' => [
            'fields' => [
                'regular' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Link Color',
                    'default' => '#4db2b3'
                ],
                'hover' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Hover Color',
                    'default' => '#424753'
                ]
            ]
        ]
    ]
];
