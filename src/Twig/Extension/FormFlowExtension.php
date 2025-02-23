<?php

namespace Asmitta\FormFlowBundle\Twig\Extension;

use Asmitta\FormFlowBundle\Form\FormFlow;
use Asmitta\FormFlowBundle\Util\FormFlowUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension for form flows.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowExtension extends AbstractExtension
{

	/**
	 * @var FormFlowUtil
	 */
	protected $formFlowUtil;

	public function setFormFlowUtil(FormFlowUtil $formFlowUtil): void
	{
		$this->formFlowUtil = $formFlowUtil;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return 'asmitta_formflow';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFilters(): array
	{
		return [
			new TwigFilter('asmitta_addDynamicStepNavigationParameters', [$this, 'addDynamicStepNavigationParameters']),
			new TwigFilter('asmitta_removeDynamicStepNavigationParameters', [$this, 'removeDynamicStepNavigationParameters']),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('asmitta_isStepLinkable', [$this, 'isStepLinkable']),
		];
	}

	/**
	 * Adds route parameters for dynamic step navigation.
	 * @param mixed[] $parameters Current route parameters.
	 * @param FormFlow $flow The flow involved.
	 * @param int $stepNumber Number of the step the link will be generated for.
	 * @return mixed[] Route parameters plus instance and step parameter.
	 */
	public function addDynamicStepNavigationParameters(array $parameters, FormFlow $flow, $stepNumber): array
	{
		return $this->formFlowUtil->addRouteParameters($parameters, $flow, $stepNumber);
	}

	/**
	 * Removes route parameters for dynamic step navigation.
	 * @param array<mixed> $parameters Current route parameters.
	 * @param FormFlow $flow The flow involved.
	 * @return array<mixed> Route parameters without instance and step parameter.
	 */
	public function removeDynamicStepNavigationParameters(array $parameters, FormFlow $flow): array
	{
		return $this->formFlowUtil->removeRouteParameters($parameters, $flow);
	}

	/**
	 * @param FormFlow $flow The flow involved.
	 * @param int $stepNumber Number of the step the link will be generated for.
	 * @return bool If the step can be linked to.
	 */
	public function isStepLinkable(FormFlow $flow, $stepNumber)
	{
		if (
			!$flow->isAllowDynamicStepNavigation()
			|| $flow->getCurrentStepNumber() === $stepNumber
			|| $flow->isStepSkipped($stepNumber)
		) {
			return false;
		}

		$lastStepConsecutivelyDone = 0;
		for ($i = $flow->getFirstStepNumber(), $lastStepNumber = $flow->getLastStepNumber(); $i < $lastStepNumber; ++$i) {
			if ($flow->isStepDone($i)) {
				$lastStepConsecutivelyDone = $i;
			} else {
				break;
			}
		}

		$lastStepLinkable = $lastStepConsecutivelyDone + 1;

		if ($stepNumber <= $lastStepLinkable) {
			return true;
		}

		return false;
	}
}
