<?php

use App\Services\GuanghengProductImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('import:guangheng-products {--keep-existing : 保留现有产品/分类的启用状态，仅追加或更新导入数据}', function () {
    $this->info('开始从 guangheng-zs.fkwcust.com 导入产品与分类…');

    $result = (new GuanghengProductImporter())->import(
        deactivateExisting: ! $this->option('keep-existing'),
    );

    $this->table(
        ['项目', '数量'],
        [
            ['分类', (string) $result['categories']],
            ['产品', (string) $result['products']],
            ['资源文件', (string) $result['assets']],
        ],
    );

    $this->info('导入完成。请刷新前台 /products 查看。');
})->purpose('从广恒演示站导入产品与分类');
