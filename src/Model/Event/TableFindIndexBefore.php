<?php
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
use Cake\ORM\Query;
use Cake\ORM\Table;

class TableFindIndexBefore extends Event
{
    const EVENT_NAME = 'Table.findIndex.before';

    /**
     * @var FindIndexOptions
     */
    private $options;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var Table
     */
    private $table;

    /**
     * @param string $name Name
     * @param null $subject Subject
     * @param null $data Data
     */
    public function __construct($name, $subject = null, $data = null)
    {
        $this->setTable($subject);
        $this->setOptions($data['options'] ?? null);
        $this->setQuery($data['query'] ?? null);

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param Table $table Instance of Table
     * @return $this
     */
    private function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param FindIndexOptions $options Instance of FindIndexOptions
     * @return $this
     */
    private function setOptions(FindIndexOptions $options)
    {
        $this->options = clone $options;

        return $this;
    }

    /**
     * @param Query $query Instance of Query
     * @return $this
     */
    private function setQuery(Query $query)
    {
        $this->query = clone $query;

        return $this;
    }

    /**
     * @param Query $query Query
     * @param FindIndexOptions $options Options
     * @param Table $table Table
     * @return TableFindIndexBefore
     */
    public static function create(Query $query, FindIndexOptions $options, Table $table)
    {
        return new static(static::EVENT_NAME, $table, [
            'query' => $query,
            'options' => $options,
        ]);
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return FindIndexOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }
}
