<?php

namespace Asmitta\FormFlowBundle\Tests\EventListener;

use Asmitta\FormFlowBundle\EventListener\PreviousStepInvalidEventListener;
use Asmitta\FormFlowBundle\Tests\UnitTestCase;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PreviousStepInvalidEventListenerTest extends UnitTestCase
{

	use EventListenerWithTranslatorTestTrait;

	protected function getListener()
	{
		return new PreviousStepInvalidEventListener();
	}
}
