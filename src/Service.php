<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

namespace aogg\think\migration\extend;

//use think\migration\command\factory\Create as FactoryCreate;
use \aogg\think\migration\extend\command\migrate\Breakpoint as MigrateBreakpoint;
use \aogg\think\migration\extend\command\migrate\Create as MigrateCreate;
use \aogg\think\migration\extend\command\migrate\Rollback as MigrateRollback;
use \aogg\think\migration\extend\command\migrate\Run as MigrateRun;
use \aogg\think\migration\extend\command\migrate\Status as MigrateStatus;
use \aogg\think\migration\extend\command\seed\Create as SeedCreate;
use \aogg\think\migration\extend\command\seed\Run as SeedRun;

class Service extends \think\Service
{

    public function boot()
    {

        $this->app->bind(RootPath::class, function () {
            return new RootPath();
        });


        // 暂无create文件
//        $this->app->bind('migration.creator', Creator::class);

        $this->commands([
            MigrateCreate::class,
            \aogg\think\migration\extend\command\migrate\TestOne::class,
            \aogg\think\migration\extend\command\migrate\RunOne::class,
            \aogg\think\migration\extend\command\migrate\RollbackOne::class,
            MigrateRun::class,
            MigrateRollback::class,
            MigrateBreakpoint::class,
            MigrateStatus::class,
            SeedCreate::class,
            SeedRun::class,
//            FactoryCreate::class,
        ]);
    }
}
