<?php

function icone($nome, $tamanho = 20)
{
    $desenhos = [
        'inicio'      => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
        'desafios'    => '<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>',
        'grupos'      => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'ranking'     => '<path d="M3 21h18"/><path d="M6 21v-6"/><path d="M12 21V9"/><path d="M18 21V4"/>',
        'condicionamento' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'sair'        => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
        'calendario'  => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>',
        'mais'        => '<path d="M5 12h14"/><path d="M12 5v14"/>',
        'raio'        => '<path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>',
        'subindo'     => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
        'check'       => '<path d="M20 6 9 17l-5-5"/>',
        'seta'        => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
        'alvo'        => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'fechar'      => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'medalha'     => '<circle cx="12" cy="8" r="6"/><path d="M15.48 12.89 17 22l-5-3-5 3 1.52-9.11"/>',
    ];

    $desenho = $desenhos[$nome] ?? '';

    return '<svg class="icone" width="' . $tamanho . '" height="' . $tamanho . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $desenho . '</svg>';
}

function iniciais($nome)
{
    $partes = preg_split('/\s+/', trim($nome));
    $primeira = mb_substr($partes[0], 0, 1);
    $ultima = count($partes) > 1 ? mb_substr(end($partes), 0, 1) : '';
    return mb_strtoupper($primeira . $ultima);
}
