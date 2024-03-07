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
 * @since         3.4.0
 */

namespace Passbolt\EmailDigest\Service;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

/**
 * Class ConvertEmailVariablesToJsonService
 * @see \V331ConvertEmailVariablesToJson
 */
class ConvertEmailVariablesToJsonService
{
    /**
     * Passbolt decided to have its email variables stored in JSON.
     * This is Postgres friendly and more secure.
     * In order to do so, all the emails stored in the email queue prior to v3.3.1
     * and not sent yet will have to be converted from serialized to JSON in a migration.
     * This method convert variables of all emails from Serialize format to JSON.
     *
     * @return void
     */
    public function convert(): void
    {
        $emails = $this->findUnsentEmails();
        if ($emails === false) {
            return;
        }

        foreach ($emails as $email) {
            $this->convertEmail($email);
        }
    }

    /**
     * Find unsent emails in the queue.
     * We do not use the table's find method that would parse data to JSON.
     *
     * @return array|false
     */
    public function findUnsentEmails()
    {
        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');

        return $EmailQueueTable->getConnection()
            ->selectQuery()
            ->select(['id', 'template_vars'])
            ->from($EmailQueueTable->getTable())
            ->where(['sent' => 0])
            ->execute()
            ->fetchAll('assoc');
    }

    /**
     * Convert variables of this email from serialize to json.
     *
     * @param array $email Email
     */
    protected function convertEmail(array $email): void
    {
        $templateVars = $email['template_vars'];

        $toArray = $this->toArray($templateVars ?? '');
        if (empty($toArray)) {
            return;
        }

        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $email = $EmailQueueTable->get($email['id']);
        $email = $EmailQueueTable->patchEntity($email, ['template_vars' => $toArray]);
        $EmailQueueTable->save($email);
    }

    /**
     * Convert serialized string v3.3.0 encoded
     * into an array of arrays.
     *
     * @param string $vars String to convert
     * @return array
     */
    public function toArray(string $vars): array
    {
        try {
            $vars = @unserialize($vars);
        } catch (\Throwable $exception) {
            $vars = [];
        }

        if (empty($vars) || !is_array($vars)) {
            return [];
        }

        foreach ($vars as $var => $value) {
            if ($value instanceof EntityInterface) {
                $vars[$var] = $value->toArray();
            }
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if ($v instanceof EntityInterface) {
                        $vars[$var][$k] = $v->toArray();
                    }
                }
            }
        }

        return $vars;
    }
}
