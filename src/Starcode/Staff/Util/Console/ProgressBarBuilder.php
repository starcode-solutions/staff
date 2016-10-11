<?php

namespace Starcode\Staff\Util\Console;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProgressBarBuilder
 * @package Starcode\Staff\Util\Console
 */
class ProgressBarBuilder
{
    /**
     * @param OutputInterface $output
     * @param int $max
     * @return ProgressBar
     */
    public function build(OutputInterface $output, $max = 0)
    {
        return new ProgressBar($output, $max);
    }
}