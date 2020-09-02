<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend\traits;

/**
 * @mixin \think\console\Command
 */
trait CommonTrait
{
    protected $rootPath = '';

    /**
     * @param string $rootPath
     * @return $this
     */
    public function setRootPath(string $rootPath = '')
    {
        $rootPath = $this->getApp()->getRootPath() . $rootPath;
        $this->rootPath = $rootPath;

        return $this;
    }

    public function setModulesRootPath(string $rootPath = '')
    {
        $rootPath = $this->getApp()->getRootPath() . ($rootPath ?: app(\aogg\think\migration\extend\RootPath::class)->getRootPath());
        $this->rootPath = $rootPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    public function setName(string $name)
    {
        return parent::setName('aogg:' . $name);
    }

}