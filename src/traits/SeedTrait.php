<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend\traits;


trait SeedTrait
{
    use CommonTrait;

    /**
     * @see \think\migration\command\Seed::getPath
     * @return string
     */
    protected function getPath()
    {
        return $this->getRootPath() . 'database' . DIRECTORY_SEPARATOR . 'seeds';
    }

    /**
     * @see \think\migration\command\Seed::getSeeds
     * @return \think\migration\Seeder[]
     */
    public function getSeeds()
    {
        if (null === $this->seeds) {
            // 只改了这行
            $phpFiles = \aogg\think\migration\extend\RootPath::globRecursion(
                $this->getPath() . DIRECTORY_SEPARATOR . '*.php', defined('GLOB_BRACE') ? GLOB_BRACE : 0
            );

            // filter the files to only get the ones that match our naming scheme
            $fileNames = [];
            /** @var \think\migration\Seeder[] $seeds */
            $seeds = [];

            foreach ($phpFiles as $filePath) {
                if (\Phinx\Util\Util::isValidSeedFileName(basename($filePath))) {
                    // convert the filename to a class name
                    $class             = pathinfo($filePath, PATHINFO_FILENAME);
                    $fileNames[$class] = basename($filePath);

                    // load the seed file
                    /** @noinspection PhpIncludeInspection */
                    require_once $filePath;
                    if (!class_exists($class)) {
                        throw new \InvalidArgumentException(sprintf('Could not find class "%s" in file "%s"', $class, $filePath));
                    }

                    // instantiate it
                    $seed = new $class($this->input, $this->output);

                    if (!($seed instanceof \Phinx\Seed\AbstractSeed)) {
                        throw new \InvalidArgumentException(sprintf('The class "%s" in file "%s" must extend \Phinx\Seed\AbstractSeed', $class, $filePath));
                    }

                    $seeds[$class] = $seed;
                }
            }

            ksort($seeds);
            $this->seeds = $seeds;
        }

        return $this->seeds;
    }

}