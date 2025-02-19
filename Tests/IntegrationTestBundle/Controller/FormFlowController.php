<?php

namespace Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Controller;

use Asmitta\FormFlowBundle\Form\FormFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\Issue149Data;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\Issue64Data;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\PhotoCollection;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\PhotoUpload;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\RevalidatePreviousStepsData;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\Topic;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Entity\Vehicle;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\CreateTopicFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\CreateVehicleFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\Demo1Flow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\Issue64Flow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\Issue87Flow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\Issue149Flow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\Issue303Flow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\OnlyOneStepFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\PhotoCollectionUploadFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\PhotoUploadFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\RemoveSecondStepSkipMarkOnResetFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\RevalidatePreviousStepsFlow;
use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\Form\SkipFirstStepUsingClosureFlow;
use Asmitta\FormFlowBundle\Util\FormFlowUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class FormFlowController extends AbstractController
{

	/**
	 * @var FormFlowUtil
	 */
	private $formFlowUtil;

	/**
	 * @var Environment
	 */
	private $twig;

	public function __construct(FormFlowUtil $formFlowUtil, Environment $twig)
	{
		$this->formFlowUtil = $formFlowUtil;
		$this->twig = $twig;
	}

	public function createTopicAction(Request $request, CreateTopicFlow $flow)
	{
		return $this->processFlow($request, new Topic(), $flow);
	}

	public function createTopicRedirectAfterSubmitAction(Request $request, CreateTopicFlow $flow)
	{
		$flow->setAllowDynamicStepNavigation(false);
		$flow->setAllowRedirectAfterSubmit(true);

		return $this->processFlow($request, new Topic(), $flow);
	}

	public function createVehicleAction(Request $request, CreateVehicleFlow $flow)
	{
		return $this->processFlow($request, new Vehicle(), $flow);
	}

	public function demo1Action(Request $request, Demo1Flow $flow)
	{
		return $this->processFlow($request, new \stdClass(), $flow);
	}

	public function issue64Action(Request $request, Issue64Flow $flow)
	{
		return $this->processFlow($request, new Issue64Data(), $flow);
	}

	public function issue87Action(Request $request, Issue87Flow $flow)
	{
		return $this->processFlow($request, new \stdClass(), $flow);
	}

	public function issue149Action(Request $request, Issue149Flow $flow)
	{
		return $this->processFlow($request, new Issue149Data(), $flow);
	}

	public function issue303Action(Request $request, Issue303Flow $flow)
	{
		return $this->processFlow($request, new \stdClass(), $flow);
	}

	public function revalidatePreviousStepsAction(Request $request, RevalidatePreviousStepsFlow $flow, $enabled)
	{
		$flow->setRevalidatePreviousSteps($enabled);

		return $this->processFlow($request, new RevalidatePreviousStepsData(), $flow);
	}

	public function skipFirstStepUsingClosureAction(Request $request, SkipFirstStepUsingClosureFlow $flow)
	{
		return $this->processFlow($request, new \stdClass(), $flow);
	}

	public function removeSecondStepSkipMarkOnResetAction(Request $request, RemoveSecondStepSkipMarkOnResetFlow $flow)
	{
		return $this->processFlow($request, new \stdClass(), $flow);
	}

	public function onlyOneStepAction(Request $request, OnlyOneStepFlow $flow)
	{
		return $this->processFlow($request, new \stdClass(), $flow);
	}

	public function photoUploadAction(Request $request, PhotoUploadFlow $flow)
	{
		return $this->processFlow(
			$request,
			new PhotoUpload(),
			$flow,
			'@IntegrationTest/FormFlow/photoUpload.html.twig'
		);
	}

	public function photoCollectionUploadAction(Request $request, PhotoCollectionUploadFlow $flow)
	{
		return $this->processFlow(
			$request,
			new PhotoCollection(),
			$flow,
			'@IntegrationTest/FormFlow/photoCollectionUpload.html.twig'
		);
	}

	public function usualFormAction(Request $request, CreateTopicFlow $flow, FormFactoryInterface $formFactory)
	{
		return $this->processFlow(
			$request,
			new Topic(),
			$flow,
			'@IntegrationTest/FormFlow/usualForm.html.twig',
			['usualForm' => $formFactory->create()->createView()]
		);
	}

	protected function processFlow(Request $request, $formData, FormFlow $flow, $template = '@IntegrationTest/layout_flow.html.twig', array $templateParameters = [])
	{
		$flow->bind($formData);

		$form = $submittedForm = $flow->createForm();
		if ($flow->isValid($submittedForm)) {
			$flow->saveCurrentStepData($submittedForm);

			if ($flow->nextStep()) {
				// create form for next step
				$form = $flow->createForm();
			} else {
				// flow finished
				$flow->reset();

				return new JsonResponse($formData);
			}
		}

		if ($flow->redirectAfterSubmit($submittedForm)) {
			$params = $this->formFlowUtil->addRouteParameters(array_merge(
				$request->query->all(),
				$request->attributes->get('_route_params')
			), $flow);

			return $this->redirectToRoute($request->attributes->get('_route'), $params);
		}

		return new Response($this->twig->render($template, array_merge($templateParameters, [
			'form' => $form->createView(),
			'flow' => $flow,
			'formData' => $formData,
		])));
	}
}
