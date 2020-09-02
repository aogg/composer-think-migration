# composer-think-migration
扩展TP的数据库迁移工具


# 使用

## modules支持
> 1、实现模块化数据迁移，每个文件夹下是一个模块，每个模块里面有database文件夹用于原始TP的数据迁移  
> 2、及将原始TP的数据迁移，支持多个地方的文件夹  
> 3、修改app/AppService.php文件  

```php
<?php

namespace app;

use think\Service;

class AppService extends Service
{


    public function boot()
    {

        \aogg\think\migration\extend\RootPath::setModulesRootPath();

    }
}

```