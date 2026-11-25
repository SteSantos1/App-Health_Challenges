<?php

// 'unidades' lista as unidades aceitas por tipo, com os pontos do indice por unidade
function tipos_atividade(): array
{
    return [
        'Caminhada'  => ['plural' => 'Caminhadas',  'emoji' => '🚶', 'unidades' => ['km' => 5,  'min' => 0.5]],
        'Corrida'    => ['plural' => 'Corridas',    'emoji' => '🏃', 'unidades' => ['km' => 10, 'min' => 1]],
        'Ciclismo'   => ['plural' => 'Ciclismos',   'emoji' => '🚴', 'unidades' => ['km' => 4,  'min' => 0.4]],
        'Natação'    => ['plural' => 'Natações',    'emoji' => '🏊', 'unidades' => ['km' => 20, 'min' => 1]],
        'Musculação' => ['plural' => 'Musculações', 'emoji' => '🏋️', 'unidades' => ['min' => 0.8]],
        'Yoga'       => ['plural' => 'Yogas',       'emoji' => '🧘', 'unidades' => ['min' => 0.4]],
        'Pilates'    => ['plural' => 'Pilates',     'emoji' => '🤸', 'unidades' => ['min' => 0.5]],
        'Dança'      => ['plural' => 'Danças',      'emoji' => '💃', 'unidades' => ['min' => 0.7]],
    ];
}
