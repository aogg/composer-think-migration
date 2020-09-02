<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend;


class RootPath
{
    public static $rootPath = '';
    
    /**
     * @param string $rootPath
     */
    public static function setRootPath(string $rootPath)
    {
        static::$rootPath = $rootPath;
    }

    public static function setModulesRootPath(string $rootPath = 'app/modules/**/')
    {
        static::setRootPath($rootPath);
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return static::$rootPath;
    }


}
