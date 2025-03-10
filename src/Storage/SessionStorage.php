<?php

namespace Asmitta\FormFlowBundle\Storage;

use Asmitta\FormFlowBundle\Exception\InvalidTypeException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Stores data in the session.
 *
 * @author Toni Uebernickel <tuebernickel@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class SessionStorage implements StorageInterface
{

	use SessionProviderTrait;

	/**
	 * @param RequestStack $requestStack
	 * @throws InvalidTypeException
	 */
	public function __construct($requestStack)
	{
		$this->requestStack = $requestStack;
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value): void
	{
		$this->getSession()->set($key, $value);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($key, $default = null)
	{
		return $this->getSession()->get($key, $default);
	}

	/**
	 * {@inheritDoc}
	 */
	public function has($key)
	{
		return $this->getSession()->has($key);
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove($key): void
	{
		$this->getSession()->remove($key);
	}
}
