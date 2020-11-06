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
     * 排除的目录，绝对路径
     *
     * @var array
     */
    public static $excludeDir = [];
    
    /**
     * @param string $rootPath
     */
    public static function setRootPath(string $rootPath)
    {
        static::$rootPath = app()->getRootPath() . $rootPath;
    }

    /**
     * @param string $rootPath
     * @param array $excludeDir 排除的目录，绝对路径
     */
    public static function setModulesRootPath(string $rootPath = 'app/modules/**/', $excludeDir = [])
    {
        static::setRootPath($rootPath);
        static::$excludeDir = array_map(function ($value){
            return rtrim($value, '/\\');
        }, $excludeDir);
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return static::$rootPath;
    }

    /**
     * 递归glob
     *
     * @param $dir
     * @param null $globFlags
     * @param array $arr
     * @return array
     */
    public static function globRecursion($dir, $globFlags = null, &$arr = [])
    {
        // 目前只支持一次/**/
        if (strpos($dir, '**') === false){ // 非递归
            return glob($dir, $globFlags);
        }

        $excludeDir = array_flip(static::$excludeDir);

        // 递归
        // /data/php/app/modules/**/database/migrations/*.php
        $dirArr = preg_split('/[\/\\\\][^\/\\\\]*\*\*[^\/\\\\]*[\/\\\\]/', $dir, 2);
        $dirPrefix = !empty($dirArr[0])?$dirArr[0]:'';
        $dirSuffix = '/' . ltrim(!empty($dirArr[1])?$dirArr[1]:'', '/\\');

        foreach (glob($dirPrefix . '/*', $globFlags) as $file) {
            $arr += glob($file . $dirSuffix, $globFlags);
            if (!isset($excludeDir[$file]) && is_dir($file)) { // 目录
                static::globRecursion($file . '/**' . $dirSuffix, $globFlags, $arr);
            }
        }

        return $arr;
    }

}
