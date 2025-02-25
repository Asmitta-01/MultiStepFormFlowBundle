<?php

namespace Asmitta\FormFlowBundle\Tests;

use Asmitta\FormFlowBundle\Form\FormFlow;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Asmitta\FormFlowBundle\Storage\DataManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPUnit\Framework\MockObject\MockObject;

class FormFlowTest extends TestCase
{
    /** @var FormFactoryInterface&MockObject */
    private $formFactory;

    /** @var RequestStack&MockObject */
    private $requestStack;

    /** @var DataManagerInterface&MockObject */
    private $dataManager;

    /** @var EventDispatcherInterface&MockObject */
    private $eventDispatcher;

    /** @var FormFlow&MockObject */
    private $formFlow;

    protected function setUp(): void
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->dataManager = $this->createMock(DataManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->formFlow = $this->getMockForAbstractClass(FormFlow::class);
        $this->formFlow->setFormFactory($this->formFactory);
        $this->formFlow->setRequestStack($this->requestStack);
        $this->formFlow->setDataManager($this->dataManager);
        $this->formFlow->setEventDispatcher($this->eventDispatcher);
    }

    public function testGetName()
    {
        $this->assertIsString($this->formFlow->getName());
    }

    public function testGetStepData()
    {
        $stepNumber = 1;
        $stepData = ['field' => 'value'];

        $this->dataManager->method('load')->willReturn([$stepNumber => $stepData]);

        $this->assertEquals($stepData, $this->formFlow->getStepData($stepNumber));
    }
}
