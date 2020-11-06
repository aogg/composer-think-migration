<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend\traits;


trait MigrateTrait
{
    use CommonTrait;

    /**
     * @see \think\migration\command\Migrate::getPath
     * @return string
     */
    protected function getPath()
    {
        return $this->getRootPath() . 'database' . DIRECTORY_SEPARATOR . 'migrations';
    }

    /**
     * 
     * @see \think\migration\command\Migrate::getMigrations
     * @return \think\migration\Migrator[]|\aogg\think\migration\extend\Migrator[]
     */
    protected function getMigrations()
    {
        if (null === $this->migrations) {
            // 只改了这行
            $phpFiles = \aogg\think\migration\extend\RootPath::globRecursion(
                $this->getPath() . DIRECTORY_SEPARATOR . '*.php', defined('GLOB_BRACE') ? GLOB_BRACE : 0
            );

            // filter the files to only get the ones that match our naming scheme
            $fileNames = [];
            /** @var \think\migration\Migrator[] $versions */
            $versions = [];

            foreach ($phpFiles as $filePath) {
                if (\Phinx\Util\Util::isValidMigrationFileName(basename($filePath))) {
                    $version = \Phinx\Util\Util::getVersionFromFileName(basename($filePath));

                    if (isset($versions[$version])) {
                        throw new \InvalidArgumentException(sprintf('Duplicate migration - "%s" has the same version as "%s"', $filePath, $versions[$version]->getVersion()));
                    }

                    // convert the filename to a class name
                    $class = \Phinx\Util\Util::mapFileNameToClassName(basename($filePath));

                    if (isset($fileNames[$class])) {
                        throw new \InvalidArgumentException(sprintf('Migration "%s" has the same name as "%s"', basename($filePath), $fileNames[$class]));
                    }

                    $fileNames[$class] = basename($filePath);

                    // load the migration file
                    /** @noinspection PhpIncludeInspection */
                    require_once $filePath;
                    if (!class_exists($class)) {
                        throw new \InvalidArgumentException(sprintf('Could not find class "%s" in file "%s"', $class, $filePath));
                    }

                    // instantiate it
                    $migration = new $class($version, $this->input, $this->output);

                    if (!($migration instanceof \Phinx\Migration\AbstractMigration)) {
                        throw new \InvalidArgumentException(sprintf('The class "%s" in file "%s" must extend \Phinx\Migration\AbstractMigration', $class, $filePath));
                    }

                    $versions[$version] = $migration;
                }
            }

            ksort($versions);
            $this->migrations = $versions;
        }

        return $this->migrations;
    }

}