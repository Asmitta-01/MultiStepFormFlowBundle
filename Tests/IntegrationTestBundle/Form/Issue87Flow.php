<?php

namespace Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form;

use Asmitta\FormFlowBundle\Form\FormFlow;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Issue87Flow extends FormFlow
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
			],
			[
				'label' => 'step2',
			],
			[
				'label' => 'step3',
			],
		];
	}
}
