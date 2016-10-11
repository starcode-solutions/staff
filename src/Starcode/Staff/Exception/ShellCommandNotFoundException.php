<?php

namespace Starcode\Staff\Exception;

class ShellCommandNotFoundException extends BaseException
{
    const MESSAGE_PATTERN = 'Shell command %s not found';

    private $command;

    public function __construct($command, $code = 0, \Exception $previous = null)
    {
        $this->command = $command;
        parent::__construct(sprintf(self::MESSAGE_PATTERN, $command), $code, $previous);
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }
}