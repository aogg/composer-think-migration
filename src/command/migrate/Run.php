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
The <info>aogg:migrate:run</info> command runs all available migrations, optionally up to a specific version

<info>php think aogg:migrate:run</info>
<info>php think aogg:migrate:run -t 20110103081132</info>
<info>php think aogg:migrate:run -d 20110103</info>
<info>php think aogg:migrate:run -v</info>

EOT
        );
    }

}