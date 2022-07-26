<?php

return [
    'guards' => [
        'admin' => [
            'model' => \App\Models\AdminUser::class,
            'login_fields' => [
                'username',
            ],
            'conditions' => [
                ['status', '=', 1]
            ]
        ]
    ],

    'route_prefix' => "api",
    'middleware' => [
        'basic' => 'api', //基础中间件

        'auth' => ['auth:sanctum'], //鉴权中间件

        'permission' => ['auth:sanctum', 'snack.permission'] //包含权限检测的中间件
    ],

    'captcha_cache_ttl' => 2,
];