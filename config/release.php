<?php

/**
 * 后台版本标识（部署到正式环境时请同步更新 .env 中的 APP_RELEASE_*）
 */
return [

    'version' => env('APP_RELEASE_VERSION', '2026.05.22.1'),

    'label' => env('APP_RELEASE_LABEL', '案例页Banner、全站菜单与页脚'),

    'published_at' => env('APP_RELEASE_AT'),

];
