<?php

namespace Asmitta\FormFlowBundle\Tests\IntegrationTestBundle;

use Asmitta\FormFlowBundle\Tests\IntegrationTestBundle\DependencyInjection\Compiler\DoctrineStorageCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class IntegrationTestBundle extends Bundle
{

	public function build(ContainerBuilder $container): void
	{
		parent::build($container);

		$container->addCompilerPass(new DoctrineStorageCompilerPass());
	}
}
