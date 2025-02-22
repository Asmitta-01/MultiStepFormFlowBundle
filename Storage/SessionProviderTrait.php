<?php

namespace Asmitta\FormFlowBundle\Storage;

use Asmitta\FormFlowBundle\Exception\InvalidTypeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @internal
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait SessionProviderTrait
{

	/**
	 * @var RequestStack|null
	 */
	private $requestStack;

	/**
	 * @param RequestStack $requestStack
	 * @throws InvalidTypeException
	 */
	private function setRequestStack($requestStack): void
	{
		if ($requestStack instanceof RequestStack) {
			$this->requestStack = $requestStack;
		} else {
			throw new InvalidTypeException($requestStack, RequestStack::class);
		}
	}

	private function getSession(): SessionInterface
	{
		return $this->requestStack->getSession();
	}
}
