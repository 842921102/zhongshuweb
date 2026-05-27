<?php

/**
 * 后台版本标识（部署到正式环境时请同步更新 .env 中的 APP_RELEASE_*）
 */
return [

    'version' => env('APP_RELEASE_VERSION', '2026.05.26.2'),

    'label' => env('APP_RELEASE_LABEL', '首页模块排序、全站页脚统一、合作伙伴横排'),

    'published_at' => env('APP_RELEASE_AT'),

];
