<?php

namespace Asmitta\FormFlowBundle\Form;

use Asmitta\FormFlowBundle\Exception\InvalidTypeException;
use Asmitta\FormFlowBundle\Exception\StepLabelCallableInvalidReturnValueException;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @author Brayan Tiwa <tiwabrayan@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @copyright 2025 Brayan Tiwa
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
final class Step implements StepInterface
{

	/**
	 * @var int
	 */
	protected $number;

	/**
	 * @var string|StepLabel|null
	 */
	protected $label = null;

	/**
	 * @var FormTypeInterface|string|null
	 */
	protected $formType = null;

	/**
	 * @var mixed[]
	 */
	protected $formOptions = [];

	/**
	 * @var callable|null
	 */
	private $skipFunction = null;

	/**
	 * @var bool|null Is only null if not yet evaluated.
	 */
	private $skipped = false;

	/**
	 * @param int $number
	 * @param mixed[] $config
	 */
	public static function createFromConfig(int $number, array $config): static
	{
		$step = new static();

		$step->setNumber($number);

		foreach ($config as $key => $value) {
			switch ($key) {
				case 'label':
					$step->setLabel($value);
					break;
				case 'form_type':
					$step->setFormType($value);
					break;
				case 'form_options':
					$step->setFormOptions($value);
					break;
				case 'skip':
					$step->setSkip($value);
					break;
				default:
					throw new \InvalidArgumentException(sprintf('Invalid step config option "%s" given.', $key));
			}
		}

		return $step;
	}

	/**
	 * @param int $number
	 */
	public function setNumber(int $number): void
	{
		$this->number = $number;

		return;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getNumber()
	{
		return $this->number;
	}

	/**
	 * @param string|StepLabel|null $label
	 */
	public function setLabel($label): void
	{
		if (is_string($label)) {
			$this->label = StepLabel::createStringLabel($label);

			return;
		}

		if ($label === null || $label instanceof StepLabel) {
			$this->label = $label;

			return;
		}

		throw new InvalidTypeException($label, ['null', 'string', StepLabel::class]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getLabel()
	{
		try {
			return $this->label !== null ? $this->label->getText() : null;
		} catch (StepLabelCallableInvalidReturnValueException $e) {
			throw new \RuntimeException(sprintf(
				'The label callable for step %d did not return a string or null value.',
				$this->number
			));
		}
	}

	/**
	 * @param FormTypeInterface|string|null $formType
	 * @throws InvalidTypeException
	 */
	public function setFormType($formType): void
	{
		if ($formType === null || is_string($formType) || $formType instanceof FormTypeInterface) {
			$this->formType = $formType;

			return;
		}

		throw new InvalidTypeException($formType, ['null', 'string', FormTypeInterface::class]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormType()
	{
		return $this->formType;
	}

	/**
	 * @param mixed[] $formOptions
	 */
	public function setFormOptions(array $formOptions): void
	{
		$this->formOptions = $formOptions;

		return;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFormOptions(): array
	{
		return $this->formOptions;
	}

	/**
	 * @param bool|callable $skip
	 * @throws InvalidTypeException
	 */
	public function setSkip($skip): void
	{
		if (is_bool($skip)) {
			$this->skipFunction = null;
			$this->skipped = $skip;

			return;
		}

		if (is_callable($skip)) {
			$this->skipFunction = $skip;
			$this->skipped = null;

			return;
		}

		throw new InvalidTypeException($skip, ['bool', 'callable']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function evaluateSkipping(int $estimatedCurrentStepNumber, FormFlowInterface $flow): void
	{
		if ($this->skipFunction !== null) {
			$returnValue = ($this->skipFunction)(...[$estimatedCurrentStepNumber, $flow]);

			if (!is_bool($returnValue)) {
				throw new \RuntimeException(sprintf(
					'The skip callable for step %d did not return a boolean value.',
					$this->number
				));
			}

			$this->skipped = $returnValue;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function isSkipped()
	{
		return $this->skipped === true;
	}
}
