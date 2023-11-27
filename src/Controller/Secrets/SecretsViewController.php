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
 * @since         2.7.0
 */

namespace App\Controller\Secrets;

use App\Controller\AppController;
use App\Model\Entity\Secret;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Exception;

/**
 * SecretsViewController Class
 */
class SecretsViewController extends AppController
{
    /**
     * @var \App\Model\Table\SecretsTable
     */
    protected $Secrets;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Secrets = $this->fetchTable('Secrets');
    }

    /**
     * Secret View action
     *
     * @param string $resourceId uuid Identifier of the resource
     * @throws \Cake\Http\Exception\BadRequestException if the resource id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the user does not have a secret for the resource
     * @return void
     */
    public function view(string $resourceId)
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        // Retrieve the secret.
        $uac = $this->User->getAccessControl();
        /** @var \App\Model\Entity\Secret $secret */
        $secret = $this->Secrets->findByResourceUser($resourceId, $uac->getId())->first();
        if (empty($secret)) {
            throw new NotFoundException(__('The secret does not exist.'));
        }
        $this->_logSecretAccesses($secret, $uac);
        $this->success(__('The operation was successful.'), $secret);
    }

    /**
     * Log secrets accesses in secretAccesses table.
     *
     * @param \App\Model\Entity\Secret $secret secret
     * @param \App\Utility\UserAccessControl $uac user access control object
     * @return void
     */
    protected function _logSecretAccesses(Secret $secret, UserAccessControl $uac)
    {
        try {
            if ($this->Secrets->hasAssociation('SecretAccesses')) {
                $this->Secrets->SecretAccesses->createFromSecretEntity($uac, $secret);
            }
        } catch (Exception $e) {
            throw new InternalErrorException('Could not log secret access entry.', 500, $e);
        }
    }
}
