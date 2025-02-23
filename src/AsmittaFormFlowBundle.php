<?php

namespace Asmitta\FormFlowBundle;

use Asmitta\FormFlowBundle\Util\TempFileUtil;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Asmitta\FormFlowBundle\Form\FormFlowInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class AsmittaFormFlowBundle extends AbstractBundle
{

	const FORM_FLOW_TAG = 'asmitta.form.flow';

	/**
	 * @return void
	 */
	public function boot()
	{
		/*
		 * Removes all temporary files created while handling file uploads.
		 * Use a shutdown function to clean up even in case of a fatal error.
		 */
		register_shutdown_function(function (): void {
			TempFileUtil::removeTempFiles();
		});
	}

	public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
	{
		// load an XML, PHP or YAML file
		$container->import(__DIR__ . '/config/services.yaml');
		$builder->registerForAutoconfiguration(FormFlowInterface::class)->addTag(self::FORM_FLOW_TAG);
	}

	public function process(ContainerBuilder $container)
	{
		$baseFlowDefinitionMethodCalls = $container->getDefinition(self::FORM_FLOW_TAG)->getMethodCalls();

		foreach (array_keys($container->findTaggedServiceIds(self::FORM_FLOW_TAG)) as $id) {
			$container->findDefinition($id)->setMethodCalls($baseFlowDefinitionMethodCalls);
		}
	}
}
