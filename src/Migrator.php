<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend;

/**
 * @method change() // 自动识别，只支持部分字段
 */
class Migrator extends \think\migration\Migrator
{
    public function table($tableName, $options = [])
    {
        return new db\Table($tableName, $options, $this->getAdapter());
    }


}