<?php

namespace Starcode\Staff\Exception;

use Exception;

class InvalidAccessTokenTTLException extends BaseException
{
    const MESSAGE_PATTERN = 'Invalid access token TTL format %s';

    private $format;

    public function __construct($format, $code = 0, Exception $previous = null)
    {
        $this->format = $format;
        $message = sprintf(self::MESSAGE_PATTERN, $this->format);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }
}
