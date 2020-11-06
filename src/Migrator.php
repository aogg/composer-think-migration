<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend;

/**
 * @method change() // 自动识别，只支持部分字段
 * @method \Phinx\Db\Adapter\AdapterInterface|\Phinx\Db\Adapter\TablePrefixAdapter getAdapter()
 */
class Migrator extends \think\migration\Migrator
{
    public function table($tableName, $options = [])
    {
        return new db\Table($tableName, $options, $this->getAdapter());
    }

    /**
     * 给表名添加前缀
     *
     * @param $tableName
     * @return string
     */
    public function getFullTableName($tableName)
    {
        return $this->getAdapter()->getAdapterTableName($this->table($tableName)->getName());
    }

    /**
     * 表前缀
     *
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->getAdapter()->getPrefix();
    }

}