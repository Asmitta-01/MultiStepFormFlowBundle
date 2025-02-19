<?php

namespace Asmitta\FormFlowBundle\Tests\Form;

use Asmitta\FormFlowBundle\Form\Step;
use PHPUnit\Framework\TestCase;

/**
 * Tests for BC.
 *
 * @group legacy
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class StepBcTest extends TestCase
{

	/**
	 * @expectedDeprecation Step config option "type" is deprecated since AsmittaFormFlowBundle 3.0. Use "form_type" instead.
	 */
	public function testCreateFromConfig_bcOptionType()
	{
		$step = Step::createFromConfig(1, [
			'type' => 'myFormType',
		]);

		$this->assertEquals('myFormType', $step->getFormType());
	}
}
