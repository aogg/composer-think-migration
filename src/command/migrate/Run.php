<?php
/**
 * User: aogg
 * Date: 2020/9/2
 */

namespace aogg\think\migration\extend\command\migrate;

class Run extends \think\migration\command\migrate\Run
{
    use \aogg\think\migration\extend\traits\MigrateTrait;

    public function setHelp(string $help)
    {
        return parent::setHelp(<<<EOT
The <info>migrate:run:aogg</info> command runs all available migrations, optionally up to a specific version

<info>php think migrate:run:aogg</info>
<info>php think migrate:run:aogg -t 20110103081132</info>
<info>php think migrate:run:aogg -d 20110103</info>
<info>php think migrate:run:aogg -v</info>

EOT
        );
    }

}