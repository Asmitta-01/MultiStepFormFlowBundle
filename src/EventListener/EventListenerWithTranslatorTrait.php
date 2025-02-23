<?php

namespace Asmitta\FormFlowBundle\EventListener;

use Asmitta\FormFlowBundle\Exception\InvalidTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @author Brayan Tiwa <tiwabrayan@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @copyright 2025 Brayan Tiwa
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait EventListenerWithTranslatorTrait
{

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	/**
	 * @param TranslatorInterface $translator
	 * @throws InvalidTypeException
	 */
	public function setTranslator(TranslatorInterface $translator)
	{
		$this->translator = $translator;

		return;
	}
}
