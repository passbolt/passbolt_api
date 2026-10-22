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
 * @since         5.17.0
 */

namespace Passbolt\Scim\Test\Utility;

use Cake\Database\Connection;
use Cake\Database\Exception\QueryException;
use Closure;
use PDOException;

/**
 * A database connection whose transactions always fail with the given SQLSTATE, and
 * which counts how many transactions were started.
 */
class FailingConnection extends Connection
{
    /**
     * How many transactions were started.
     *
     * @var int
     */
    public int $transactions = 0;

    /**
     * @var string
     */
    private string $sqlState;

    /**
     * @param array $config Configuration of the database. No connection is ever opened.
     * @param string $sqlState SQLSTATE value to report. `40001` is a deadlock.
     */
    public function __construct(array $config, string $sqlState)
    {
        parent::__construct($config);
        $this->sqlState = $sqlState;
    }

    /**
     * @inheritDoc
     */
    public function transactional(Closure $callback): mixed
    {
        $this->transactions++;

        $pdoException = new PDOException('SQLSTATE[' . $this->sqlState . ']');
        $pdoException->errorInfo = [$this->sqlState, 1213, 'Deadlock found when trying to get lock'];

        throw new QueryException('INSERT INTO users ...', $pdoException);
    }
}
