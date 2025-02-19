<?php

namespace Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form;

use Asmitta\FormFlowBundle\Form\FormFlow;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class OnlyOneStepFlow extends FormFlow
{

	/**
	 * {@inheritDoc}
	 */
	protected function loadStepsConfig()
	{
		return [
			[
				'label' => 'single step',
			],
		];
	}
}
