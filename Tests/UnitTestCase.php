<?php

namespace Asmitta\FormFlowBundle\Tests;

use Asmitta\FormFlowBundle\Form\FormFlow;
use Asmitta\FormFlowBundle\Form\FormFlowInterface;
use Asmitta\FormFlowBundle\Form\StepInterface;
use Asmitta\FormFlowBundle\Storage\DataManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
abstract class UnitTestCase extends TestCase
{

	/**
	 * @return MockObject|FormFlow
	 */
	protected function getMockedFlow()
	{
		return $this->getMockForAbstractClass(FormFlow::class);
	}

	/**
	 * @return MockObject|FormFlowInterface
	 */
	protected function getMockedFlowInterface()
	{
		return $this->createMock(FormFlowInterface::class);
	}

	/**
	 * @param string[] $methodNames Names of methods to be mocked.
	 * @return MockObject|FormFlow
	 */
	protected function getFlowWithMockedMethods(array $methodNames)
	{
		return $this->getMockBuilder(FormFlow::class)->onlyMethods($methodNames)->getMock();
	}

	/**
	 * @return MockObject|StepInterface
	 */
	protected function getMockedStepInterface()
	{
		return $this->createMock(StepInterface::class);
	}

	/**
	 * @return MockObject|DataManagerInterface
	 */
	protected function getMockedDataManagerInterface()
	{
		return $this->createMock(DataManagerInterface::class);
	}
}
