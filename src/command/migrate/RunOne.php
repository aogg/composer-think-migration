<?php
/**
 * User: aozhuochao
 * Date: 2020/11/6
 */

namespace aogg\think\migration\extend\command\migrate;

use \Phinx\Migration\MigrationInterface;

class RunOne extends \think\migration\command\migrate\Run
{
    use \aogg\think\migration\extend\traits\MigrateTrait;


    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('migrate:run:one')
            ->setDescription('Migrate one the database')
            ->addOption('--target', '-t', \think\console\input\Option::VALUE_REQUIRED, 'The version number to migrate to')
            ->addOption('--date', '-d', \think\console\input\Option::VALUE_REQUIRED, 'The date to migrate to')
            ->setHelp(<<<EOT
The <info>migrate:run:test:aogg</info> command runs all available migrations, optionally up to a specific version

<info>php think migrate:run:one:aogg -t 20110103081132</info>
<info>php think migrate:run:one:aogg -d 20110103</info>
<info>php think migrate:run:one:aogg -v</info>

EOT
            );
    }

    /**
     * Migrate the database.
     *
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     * @throws \Exception
     */
    protected function execute(\think\console\Input $input, \think\console\Output $output)
    {
        $version = $input->getOption('target');
        $date    = $input->getOption('date');

        if (empty($version) && empty($date)) {
            $output->writeln('<error>必须指定一个参数target或date</error>');
        }

        parent::execute($input, $output);
    }

    protected function migrate($version = null)
    {
        $migrations = $this->getMigrations();
        $versions   = $this->getVersions();

        if (empty($versions) && empty($migrations)) {
            return;
        }

        if (null === $version) {
            $version = max(array_merge($versions, array_keys($migrations)));
        } else {
            if (0 != $version && !isset($migrations[$version])) {
                $this->output->writeln(sprintf('<comment>warning</comment> %s is not a valid version', $version));
                return;
            }
        }


        ksort($migrations);
        foreach ($migrations as $migration) {
            if ($migration->getVersion() !== $version) {
                continue;
            }

            $this->executeMigration($migration, MigrationInterface::UP);
            break;
        }
    }

}