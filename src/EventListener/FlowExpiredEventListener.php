<?php

namespace Asmitta\FormFlowBundle\EventListener;

use Asmitta\FormFlowBundle\Event\FlowExpiredEvent;
use Symfony\Component\Form\FormError;

/**
 * Adds a validation error to the current step's form if an expired flow is detected.
 *
 * @author Tim Behrendsen <tim@siliconengine.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FlowExpiredEventListener
{

	use EventListenerWithTranslatorTrait;

	public function onFlowExpired(FlowExpiredEvent $event): void
	{
		$event->getCurrentStepForm()->addError($this->getFlowExpiredFormError());
	}

	/**
	 * @return FormError
	 */
	protected function getFlowExpiredFormError()
	{
		$messageId = 'asmittaFormFlow.flowExpired';
		$messageParameters = [];

		return new FormError($this->translator->trans($messageId, $messageParameters, 'validators'), $messageId, $messageParameters);
	}
}
