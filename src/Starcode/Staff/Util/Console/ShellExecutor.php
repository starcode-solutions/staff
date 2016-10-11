<?php

namespace Starcode\Staff\Util\Console;

class ShellExecutor
{
    /**
     * @param $command
     * @return string
     */
    public function execute($command)
    {
        return shell_exec($command);
    }

    /**
     * Check command exist
     *
     * @param $command
     * @return bool
     */
    public function exist($command)
    {
        $which = shell_exec("which {$command}");
        return !empty($which);
    }
}