<?php

namespace Asmitta\FormFlowBundle\Exception;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class InvalidTypeException extends \InvalidArgumentException
{

	/**
	 * @param mixed $value
	 * @param string|array<string> $expectedType
	 */
	public function __construct($value, $expectedType)
	{
		$givenType = is_object($value) ? get_class($value) : gettype($value);

		if (is_array($expectedType)) {
			$message = sprintf('Expected argument of either type %s, but "%s" given.', $this->conjunctTypes($expectedType), $givenType);
		} else {
			$message = sprintf('Expected argument of type "%s", but "%s" given.', $expectedType, $givenType);
		}

		parent::__construct($message);
	}

	/**
	 * @param string[] $expectedTypes
	 * @return string
	 */
	protected function conjunctTypes(array $expectedTypes): string
	{
		$expectedTypes = array_values($expectedTypes);

		$len = count($expectedTypes);

		if ($len === 2) {
			return sprintf('"%s" or "%s"', $expectedTypes[0], $expectedTypes[1]);
		}

		$text = '';

		for ($i = 0; $i < $len; ++$i) {
			if ($i !== 0) {
				$text .= ', ';
			}

			if ($i === $len - 1) {
				$text .= 'or ';
			}

			$text .= sprintf('"%s"', $expectedTypes[$i]);
		}

		return $text;
	}
}
