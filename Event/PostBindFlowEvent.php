<?php

namespace Asmitta\FormFlowBundle\Event;

use Asmitta\FormFlowBundle\Form\FormFlowInterface;

/**
 * Is called once after binding all step's saved form data and determining the current step.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PostBindFlowEvent extends FormFlowEvent
{

	/**
	 * @var mixed
	 */
	protected $formData;

	/**
	 * @param FormFlowInterface $flow
	 * @param mixed $formData
	 */
	public function __construct(FormFlowInterface $flow, $formData)
	{
		parent::__construct($flow);
		$this->formData = $formData;
	}

	/**
	 * @return mixed
	 */
	public function getFormData()
	{
		return $this->formData;
	}
}
