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

    /**
     * 获取指定表的唯一索引
     *
     * @param $tableName
     * @return array
     */
    public function getTableUniqueIndexes($tableName)
    {
        $fullTableName = $this->getFullTableName($tableName);

        $indexes = array();
        $rows = $this->fetchAll(sprintf('SHOW INDEXES FROM `%s`', $fullTableName));
        foreach ($rows as $row) {
            if (!isset($row['Non_unique']) || !empty($row['Non_unique'])){
                continue;
            }

            // array('type', 'unique', 'name', 'limit')
            if (!isset($indexes[$row['Key_name']])) {
                $indexes[$row['Key_name']] = array(
                    'columns' => array(),
                    'options' => [],
                );
            }
            $indexes[$row['Key_name']]['columns'][] = strtolower($row['Column_name']);

            // options
            $indexes[$row['Key_name']]['options']['unique'] = true;
            if (!empty($row['Sub_part'])) {
                $indexes[$row['Key_name']]['options']['limit'][strtolower($row['Column_name'])] = $row['Sub_part'];
            }
        }
        return $indexes;

    }

}