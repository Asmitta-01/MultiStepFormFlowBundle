<?php

namespace Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form;

use Asmitta\FormFlowBundle\Form\FormFlow;
use Asmitta\FormFlowBundle\Form\FormFlowInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CreateVehicleFlow extends FormFlow
{

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig()
	{
		$formType = CreateVehicleForm::class;

		return [
			[
				'label' => 'wheels',
				'form_type' => $formType,
			],
			[
				'label' => 'engine',
				'form_type' => $formType,
				'skip' => function ($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveEngine();
				},
			],
			[
				'label' => 'confirmation',
			],
		];
	}
}
