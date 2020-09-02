<?php
/**
 * User: aozhuochao
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend\db;

use Phinx\Db\Table\Column;

class Table extends \think\migration\db\Table
{
    /**
     * 获取单个字段
     *
     * @param $field
     * @return Column
     */
    public function getColumn($field)
    {
        $arr = $this->getColumns();

        foreach ($arr as $item) {
            if ($item->getName() === $field) {
                return $item;
            }
        }

        $column = new Column();
        $column->setName($field);

        return $column;
    }

    /**
     * 修改字段的备注
     *
     * @param $columnName
     * @param $comment
     * @return \Phinx\Db\Table|\think\migration\db\Table|$this
     */
    public function changeColumnComment($columnName, $comment)
    {
        return $this->changeColumn($this->getColumn($columnName)->setComment($comment));
    }

    /**
     * 修改字段
     *
     * @example $options参数
     * @see \Phinx\Db\Table\Column::getValidOptions
     * @see \Phinx\Db\Table\Column::getAliasedOptions
     *
     * @param string $columnName
     * @param null $newColumnType
     * @param array $options
     * @return \Phinx\Db\Table|\think\migration\db\Table
     */
    public function changeColumn($columnName, $newColumnType = null, $options = [])
    {
        return parent::changeColumn($columnName, $newColumnType, $options);
    }


}