<?php

namespace Asmitta\FormFlowBundle\Tests\Event;

use Asmitta\FormFlowBundle\Event\PostBindRequestEvent;
use Asmitta\FormFlowBundle\Tests\UnitTestCase;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PostBindRequestEventTest extends UnitTestCase
{

	public function testEvent()
	{
		$formData = ['blah' => '123'];
		$stepNumber = 2;

		$event = new PostBindRequestEvent($this->getMockedFlowInterface(), $formData, $stepNumber);

		$this->assertEquals($formData, $event->getFormData());
		$this->assertEquals($stepNumber, $event->getStepNumber());
	}
}
