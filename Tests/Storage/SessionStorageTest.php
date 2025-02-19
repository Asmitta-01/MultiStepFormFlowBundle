<?php

namespace Asmitta\FormFlowBundle\Tests\Storage;

use Asmitta\FormFlowBundle\Storage\SessionStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class SessionStorageTest extends AbstractStorageTest
{

	/**
	 * {@inheritDoc}
	 */
	protected function getStorageImplementation()
	{
		$session = new Session(new MockArraySessionStorage());

		// TODO remove as soon as Symfony >= 5.3 is required
		if (!\method_exists(RequestStack::class, 'getSession')) {
			return new SessionStorage($session);
		}

		$requestStackMock = $this->getMockBuilder(RequestStack::class)->onlyMethods(['getSession'])->getMock();

		$requestStackMock
			->method('getSession')
			->willReturn($session)
		;

		return new SessionStorage($requestStackMock);
	}
}
