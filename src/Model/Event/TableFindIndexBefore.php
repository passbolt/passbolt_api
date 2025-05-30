<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Model\Event;

use App\Model\Table\Dto\FindIndexOptions;
use Cake\Event\Event;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;

class TableFindIndexBefore extends Event
{
    public const EVENT_NAME = 'Table.findIndex.before';

    /**
     * @var \App\Model\Table\Dto\FindIndexOptions
     */
    private FindIndexOptions $options;

    /**
     * @var \Cake\ORM\Query\SelectQuery
     */
    private SelectQuery $query;

    /**
     * @var \Cake\ORM\Table
     */
    private Table $table;

    /**
     * @param string $name Name
     * @param \Cake\ORM\Table $subject Subject
     * @param array $data Data
     */
    final public function __construct(string $name, Table $subject, array $data)
    {
        $this->setTable($subject);
        $this->setOptions($data['options']);
        $this->setQuery($data['query']);

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param \Cake\ORM\Table $table Instance of Table
     * @return $this
     */
    private function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param \App\Model\Table\Dto\FindIndexOptions $options Instance of FindIndexOptions
     * @return $this
     */
    private function setOptions(FindIndexOptions $options)
    {
        $this->options = clone $options;

        return $this;
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query Instance of Query
     * @return $this
     */
    private function setQuery(SelectQuery $query)
    {
        $this->query = clone $query;

        return $this;
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param \App\Model\Table\Dto\FindIndexOptions $options Options
     * @param \Cake\ORM\Table $table Table
     * @return self
     */
    public static function create(SelectQuery $query, FindIndexOptions $options, Table $table): self
    {
        return new static(static::EVENT_NAME, $table, [
            'query' => $query,
            'options' => $options,
        ]);
    }

    /**
     * @return \Cake\ORM\Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * @return \App\Model\Table\Dto\FindIndexOptions
     */
    public function getOptions(): FindIndexOptions
    {
        return $this->options;
    }

    /**
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function getQuery(): SelectQuery
    {
        return $this->query;
    }
}
