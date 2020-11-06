<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend\command\migrate;

class RollbackOne extends \think\migration\command\migrate\Rollback
{
    use \aogg\think\migration\extend\traits\MigrateTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('migrate:rollback:one')
            ->setDescription('Rollback one migration')
            ->addOption('--target', '-t', \think\console\input\Option::VALUE_REQUIRED, 'The version number to rollback to')
            ->addOption('--date', '-d', \think\console\input\Option::VALUE_REQUIRED, 'The date to rollback to')
            ->addOption('--force', '-f', \think\console\input\Option::VALUE_NONE, 'Force rollback to ignore breakpoints')
            ->setHelp(<<<EOT
The <info>migrate:rollback:one:aogg</info> command reverts the last migration, or optionally up to a specific version

<info>php think migrate:rollback:one:aogg -t 20111018185412</info>
<info>php think migrate:rollback:one:aogg -d 20111018</info>
<info>php think migrate:rollback:one:aogg -v</info>

EOT
            );
    }


    /**
     * Rollback the migration.
     *
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     * @return void
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


    protected function rollback($version = null, $force = false)
    {
        $migrations = $this->getMigrations();
        $versionLog = $this->getVersionLog();
        $versions   = array_keys($versionLog);

        ksort($migrations);
        sort($versions);

        // Check we have at least 1 migration to revert
        if (empty($versions)) {
            $this->output->writeln('<error>No migrations to rollback</error>');
            return;
        }

        // If no target version was supplied, revert the last migration
        if (null === $version) {
            // Get the migration before the last run migration
            $prev    = count($versions) - 2;
            $version = $prev < 0 ? 0 : $versions[$prev];
        } else {
            // Get the first migration number
            $first = $versions[0];

            // If the target version is before the first migration, revert all migrations
            if ($version < $first) {
                $version = 0;
            }
        }

        // Check the target version exists
        if (0 !== $version && !isset($migrations[$version])) {
            $this->output->writeln("<error>Target version ($version) not found</error>");
            return;
        }

        // Revert the migration(s)
        krsort($migrations);
        foreach ($migrations as $migration) {
            if ($migration->getVersion() !== $version) {
                continue;
            }

            $this->executeMigration($migration, \Phinx\Migration\MigrationInterface::DOWN);
            break;
        }
    }

}