<?php

namespace Asmitta\FormFlowBundle\Storage;

use Asmitta\FormFlowBundle\Form\FormFlowInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
interface DataManagerInterface
{

	/**
	 * @var string Key for storing data of all flows.
	 */
	const STORAGE_ROOT = 'asmitta_form_flow';

	/**
	 * @return StorageInterface
	 */
	function getStorage();

	/**
	 * Saves data of the given flow.
	 * @param FormFlowInterface $flow
	 * @param array $data
	 */
	function save(FormFlowInterface $flow, array $data);

	/**
	 * Checks if data exists for a given flow.
	 * @param FormFlowInterface $flow
	 * @return bool
	 */
	function exists(FormFlowInterface $flow);

	/**
	 * Loads data of the given flow.
	 * @param FormFlowInterface $flow
	 * @return array
	 */
	function load(FormFlowInterface $flow);

	/**
	 * Drops data of the given flow.
	 * @param FormFlowInterface $flow
	 */
	function drop(FormFlowInterface $flow);
}
