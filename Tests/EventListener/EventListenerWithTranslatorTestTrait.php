<?php

namespace Asmitta\FormFlowBundle\Tests\EventListener;

use Asmitta\FormFlowBundle\Exception\InvalidTypeException;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait EventListenerWithTranslatorTestTrait
{

	protected abstract function getListener();

	/**
	 * @dataProvider dataSetTranslator_invalidArguments
	 */
	public function testSetTranslator_invalidArguments($translator)
	{
		$listener = $this->getListener();

		$this->expectException(InvalidTypeException::class);
		$listener->setTranslator($translator);
	}

	public function dataSetTranslator_invalidArguments()
	{
		return [
			[null],
			[new \stdClass()],
		];
	}
}
