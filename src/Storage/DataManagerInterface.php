<?php

namespace Asmitta\FormFlowBundle\Storage;

use Asmitta\FormFlowBundle\Form\FormFlowInterface;

/**
 * @author Christian Raue <christian.raue@gmail.com>
 * @author Brayan Tiwa <tiwabrayan@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @copyright 2025 Brayan Tiwa
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
	function getStorage(): StorageInterface;

	/**
	 * Saves data of the given flow.
	 * @param FormFlowInterface $flow
	 * @param array<mixed> $data
	 */
	function save(FormFlowInterface $flow, array $data): void;

	/**
	 * Checks if data exists for a given flow.
	 * @param FormFlowInterface $flow
	 * @return bool
	 */
	function exists(FormFlowInterface $flow): bool;

	/**
	 * Loads data of the given flow.
	 * @param FormFlowInterface $flow
	 * @return array<mixed>
	 */
	function load(FormFlowInterface $flow): array;

	/**
	 * Drops data of the given flow.
	 * @param FormFlowInterface $flow
	 */
	function drop(FormFlowInterface $flow): void;
}
