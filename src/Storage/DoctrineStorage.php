<?php

namespace Asmitta\FormFlowBundle\Storage;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

/**
 * Stores data in a Doctrine-managed database.
 *
 * @author Christian Raue <christian.raue@gmail.com>
 * @author Brayan Tiwa <tiwabrayan@gmail.com>
 * @copyright 2011-2024 Christian Raue
 * @copyright 2025 Brayan Tiwa
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class DoctrineStorage implements StorageInterface
{

	const TABLE = 'asmitta_form_flow_storage';
	const KEY_COLUMN = 'key';
	const VALUE_COLUMN = 'value';

	/**
	 * @var Connection
	 */
	private $conn;

	/**
	 * @var StorageKeyGeneratorInterface
	 */
	private $storageKeyGenerator;

	/**
	 * @var AbstractSchemaManager
	 * @phpstan-ignore-next-line
	 */
	private $schemaManager;

	/**
	 * @var string
	 */
	private $keyColumn;

	/**
	 * @var string
	 */
	private $valueColumn;

	public function __construct(Connection $conn, StorageKeyGeneratorInterface $storageKeyGenerator)
	{
		$this->conn = $conn;
		$this->storageKeyGenerator = $storageKeyGenerator;
		$this->schemaManager = $this->conn->createSchemaManager();
		$this->keyColumn = $this->conn->quoteIdentifier(self::KEY_COLUMN);
		$this->valueColumn = $this->conn->quoteIdentifier(self::VALUE_COLUMN);
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value): void
	{
		if (!$this->tableExists()) {
			$this->createTable();
		}

		if ($this->has($key)) {
			$this->conn->update(self::TABLE, [
				$this->valueColumn => serialize($value),
			], [
				$this->keyColumn => $this->generateKey($key),
			]);

			return;
		}

		$this->conn->insert(self::TABLE, [
			$this->keyColumn => $this->generateKey($key),
			$this->valueColumn => serialize($value),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($key, $default = null)
	{
		if (!$this->tableExists()) {
			return $default;
		}

		$rawValue = $this->getRawValueForKey($key);

		if ($rawValue === false) {
			return $default;
		}

		return unserialize($rawValue);
	}

	/**
	 * {@inheritDoc}
	 */
	public function has($key)
	{
		if (!$this->tableExists()) {
			return false;
		}

		return $this->getRawValueForKey($key) !== false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove($key): void
	{
		if (!$this->tableExists()) {
			return;
		}

		$this->conn->delete(self::TABLE, [
			$this->keyColumn => $this->generateKey($key),
		]);
	}

	/**
	 * Gets stored raw data for the given key.
	 * @param string $key
	 * @return string|false Raw data or false, if no data is available.
	 */
	private function getRawValueForKey($key)
	{
		$qb = $this->conn->createQueryBuilder()
			->select($this->valueColumn)
			->from(self::TABLE)
			->where($this->keyColumn . ' = :key')
			->setParameter('key', $this->generateKey($key));

		$result = $qb->executeQuery();

		return $result->fetchOne();
	}

	private function tableExists(): bool
	{
		return $this->schemaManager->tablesExist([self::TABLE]);
	}

	private function createTable(): void
	{
		$table = new Table(self::TABLE, [
			new Column($this->keyColumn, Type::getType(Types::STRING), ['length' => 255]),
			new Column($this->valueColumn, Type::getType(Types::TEXT)),
		]);

		$table->setPrimaryKey([$this->keyColumn]);
		$this->schemaManager->createTable($table);
	}

	/**
	 * @param string $key
	 */
	private function generateKey($key): string
	{
		return $this->storageKeyGenerator->generate($key);
	}
}
