<?php

namespace Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form;

use Asmitta\FormFlowBundle\Form\FormFlow;
use Asmitta\FormFlowBundle\Form\FormFlowInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class SkipFirstStepUsingClosureFlow extends FormFlow
{

	protected $allowDynamicStepNavigation = true;

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig()
	{
		return [
			[
				'label' => 'step1',
				'skip' => function ($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return true;
				},
			],
			[
				'label' => 'step2',
			],
		];
	}
}
