<?php

$dall_e = [
    '1024 x 1024',
    '1024 x 1792',
    '1792 x 1024',
];
$baidu_ai = [
    '512 x 512',
    '640 x 360',
    '360 x 640',
    '1024 x 1024',
    '1280 x 720',
    '720 x 1280',
    '2048 x 2048',
    '2560 x 1440',
    '1440 x 2560',
];

return [

    'size' => [
        'DALL-E' => $dall_e,
        'Baidu AI' => $baidu_ai,
        'Stable Diffusion' => array_unique(array_merge($baidu_ai, $dall_e), SORT_REGULAR),
    ],

    // 默认负面提示词
    'default_negative_prompt' => 'lowres,bad hands,worst quality,missing fingers,fewer digits,extra digit,unclear eyes,bad face,(sexy:1.5),(more than c cup:2.0),',
];
