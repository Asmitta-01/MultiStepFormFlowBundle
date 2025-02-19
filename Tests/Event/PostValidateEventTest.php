<?php

namespace Asmitta\FormFlowBundle\Tests\Event;

use Asmitta\FormFlowBundle\Event\PostValidateEvent;
use Asmitta\FormFlowBundle\Tests\UnitTestCase;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PostValidateEventTest extends UnitTestCase
{

	public function testEvent()
	{
		$formData = ['blah' => '123'];

		$event = new PostValidateEvent($this->getMockedFlowInterface(), $formData);

		$this->assertEquals($formData, $event->getFormData());
	}
}
