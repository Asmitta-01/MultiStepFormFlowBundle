<?php

namespace Asmitta\FormFlowBundle\Tests\Resources;

use Craue\TranslationsTests\YamlTranslationsTest;

/**
 * @group unit
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TranslationsTest extends YamlTranslationsTest
{

	protected function defineTranslationFiles()
	{
		return glob(__DIR__ . '/../../Resources/translations/*.yml');
	}
}
