<?php

namespace Web\Framework\Exception;
/**
 * Class InvalidPageException
 * @package Web\Framework\Exception
 */
class InvalidPageException extends \Exception
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
		return "<div class='invalidpage exception-message'>".$this->message."</div>";
	}

}
