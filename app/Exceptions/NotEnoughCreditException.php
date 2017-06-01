<?php
namespace App\Exceptions;

use RuntimeException;

class NotEnoughCreditException extends ServiceValidationException
{

	public function __construct()
	{
		parent::__construct('You do not have enough credit.');
	}

}
