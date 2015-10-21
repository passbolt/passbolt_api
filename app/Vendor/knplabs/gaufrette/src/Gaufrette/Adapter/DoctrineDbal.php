<?php

namespace Gaufrette\Adapter;

use Gaufrette\Adapter;
use Gaufrette\Util;

use Doctrine\DBAL\Connection;

/**
 * Doctrine DBAL adapter
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 * @author Antoine Hérault <antoine.herault@gmail.com>
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class DoctrineDbal implements Adapter,
                              ChecksumCalculator,
                              ListKeysAware
{
    protected $connection;
    protected $table;
    protected $columns = array(
        'key'      => 'key',
        'content'  => 'content',
        'mtime'    => 'mtime',
        'checksum' => 'checksum',
    );

    /**
     * Constructor
     *
     * @param Connection $connection The DBAL connection
     * @param string     $table      The files table
     * @param array      $columns    The column names
     */
    public function __construct(Connection $connection, $table, array $columns = array())
    {
        $this->connection = $connection;
        $this->table      = $table;
        $this->columns    = array_replace($this->columns, $columns);
    }

    /**
     * {@inheritDoc}
     */
    public function keys()
    {
        $keys = array();
        $stmt = $this->connection->executeQuery(sprintf(
            'SELECT %s FROM %s',
            $this->getQuotedColumn('key'),
            $this->getQuotedTable()
        ));

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritDoc}
     */
    public function rename($sourceKey, $targetKey)
    {
        return (boolean) $this->connection->update(
            $this->table,
            array($this->getQuotedColumn('key') => $targetKey),
            array($this->getQuotedColumn('key') => $sourceKey)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function mtime($key)
    {
        return $this->getColumnValue($key, 'mtime');
    }

    /**
     * {@inheritDoc}
     */
    public function checksum($key)
    {
        return $this->getColumnValue($key, 'checksum');
    }

    /**
     * {@inheritDoc}
     */
    public function exists($key)
    {
        return (boolean) $this->connection->fetchColumn(
            sprintf(
                'SELECT COUNT(%s) FROM %s WHERE %s = :key',
                $this->getQuotedColumn('key'),
                $this->getQuotedTable(),
                $this->getQuotedColumn('key')
            ),
            array('key' => $key)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function read($key)
    {
        return $this->getColumnValue($key, 'content');
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key)
    {
        return (boolean) $this->connection->delete(
            $this->table,
            array($this->getQuotedColumn('key') => $key)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function write($key, $content)
    {
        $values = array(
            $this->getQuotedColumn('content')  => $content,
            $this->getQuotedColumn('mtime')    => time(),
            $this->getQuotedColumn('checksum') => Util\Checksum::fromContent($content),
        );

        if ($this->exists($key)) {
            $this->connection->update(
                $this->table,
                $values,
                array($this->getQuotedColumn('key') => $key)
            );
        } else {
            $values[$this->getQuotedColumn('key')] = $key;
            $this->connection->insert($this->table, $values);
        }

        return Util\Size::fromContent($content);
    }

    /**
     * {@inheritDoc}
     */
    public function isDirectory($key)
    {
        return false;
    }

    private function getColumnValue($key, $column)
    {
        $value = $this->connection->fetchColumn(
            sprintf(
                'SELECT %s FROM %s WHERE %s = :key',
                $this->getQuotedColumn($column),
                $this->getQuotedTable(),
                $this->getQuotedColumn('key')
            ),
            array('key' => $key)
        );

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function listKeys($prefix = '')
    {
        $prefix = trim($prefix);

        $keys = $this->connection->fetchAll(
            sprintf(
                'SELECT %s AS _key FROM %s WHERE %s LIKE :pattern',
                $this->getQuotedColumn('key'),
                $this->getQuotedTable(),
                $this->getQuotedColumn('key')
            ),
            array('pattern' => sprintf('%s%%', $prefix))
        );

        return array(
            'dirs' => array(),
            'keys' => array_map(function ($value) {
                    return $value['_key'];
                },
                $keys)
        );
    }

    private function getQuotedTable()
    {
        return $this->connection->quoteIdentifier($this->table);
    }

    private function getQuotedColumn($column)
    {
        return $this->connection->quoteIdentifier($this->columns[$column]);
    }
}
