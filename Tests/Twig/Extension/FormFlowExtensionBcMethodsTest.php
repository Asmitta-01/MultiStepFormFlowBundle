<?php

namespace Asmitta\FormFlowBundle\Tests\Twig\Extension;

use Asmitta\FormFlowBundle\Tests\UnitTestCase;
use Asmitta\FormFlowBundle\Twig\Extension\FormFlowExtension;

/**
 * Tests for BC.
 *
 * @group legacy
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowExtensionBcMethodsTest extends UnitTestCase
{

	/**
	 * @expectedDeprecation Twig filter craue_addDynamicStepNavigationParameter is deprecated since AsmittaFormFlowBundle 3.0. Use filter craue_addDynamicStepNavigationParameters instead.
	 */
	public function testBcMethodDelegation_addDynamicStepNavigationParameter()
	{
		$extension = $this->getMockBuilder(FormFlowExtension::class)->onlyMethods(['addDynamicStepNavigationParameters'])->getMock();

		$parameters = ['foo' => 'bar'];
		$flow = $this->getMockedFlow();
		$stepNumber = 1;

		$extension
			->expects($this->once())
			->method('addDynamicStepNavigationParameters')
			->with($this->equalTo($parameters), $this->equalTo($flow), $this->equalTo($stepNumber))
		;

		$extension->addDynamicStepNavigationParameter($parameters, $flow, $stepNumber);
	}

	/**
	 * @expectedDeprecation Twig filter craue_removeDynamicStepNavigationParameter is deprecated since AsmittaFormFlowBundle 3.0. Use filter craue_removeDynamicStepNavigationParameters instead.
	 */
	public function testBcMethodDelegation_removeDynamicStepNavigationParameter()
	{
		$extension = $this->getMockBuilder(FormFlowExtension::class)->onlyMethods(['removeDynamicStepNavigationParameters'])->getMock();

		$parameters = ['foo' => 'bar'];
		$flow = $this->getMockedFlow();

		$extension
			->expects($this->once())
			->method('removeDynamicStepNavigationParameters')
			->with($this->equalTo($parameters), $this->equalTo($flow))
		;

		$extension->removeDynamicStepNavigationParameter($parameters, $flow);
	}
}
