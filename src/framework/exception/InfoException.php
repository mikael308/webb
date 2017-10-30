<?php

namespace Web\Framework\Exception;
/**
 * Class InfoException
 * @package Web\Framework\Exception
 */
class InfoException extends \Exception
{

    public function __construct(
        $message,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->message = $message;
        $this->code = $code;
        $this->previous = $previous;
    }

    public function toHtml()
    {
        return "<div class='info exception-message'>".$this->message."</div>";
    }

}
