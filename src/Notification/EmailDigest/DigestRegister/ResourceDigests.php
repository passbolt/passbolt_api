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
 * @since         2.13.0
 */

namespace App\Notification\EmailDigest\DigestRegister;

use App\Notification\Email\Redactor\Resource\ResourceCreateEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceDeleteEmailRedactor;
use App\Notification\Email\Redactor\Resource\ResourceUpdateEmailRedactor;
use App\Notification\Email\Redactor\Share\ShareEmailRedactor;
use Cake\Core\Configure;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Digest\Digest;
use Passbolt\EmailDigest\Utility\Digest\DigestRegisterTrait;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;

/**
 * Register new digest related to Resources.
 * All templates matching the list of supported templates will be aggregated together in a same email.
 */
class ResourceDigests implements EventListenerInterface
{
    use DigestRegisterTrait;

    public const RESOURCE_CHANGES_TEMPLATE = 'LU/resources_change';
    public const RESOURCE_SHARE_MULTIPLE_TEMPLATE = 'LU/resources_share';

    /**
     * @param \Passbolt\EmailDigest\Utility\Digest\DigestsPool $digestsPool Instance of the marshaller
     * @return void
     */
    public function addDigestsPool(DigestsPool $digestsPool)
    {
        $digestsPool->addDigest($this->createResourceShareDigest());
        $digestsPool->addDigest($this->createResourceChangesDigest());
    }

    /**
     * Create a new digest for all emails related to changes on resources.
     * It will aggregate the emails for Create, Update and Delete operations in the same digest.
     *
     * The marshaller will create a digest only if there is minimum 2 emails.
     * It will create another digest if there is more than 50 emails.
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\Digest
     */
    private function createResourceChangesDigest()
    {
        return new Digest(
            __('{0} has made changes on several resources', '{0}'),
            [
                ResourceCreateEmailRedactor::TEMPLATE,
                ResourceUpdateEmailRedactor::TEMPLATE,
                ResourceDeleteEmailRedactor::TEMPLATE,
            ],
            'user',
            function (Entity $emailData, int $emailCount) {
                $digest = (new EmailDigest())
                    ->setSubject(__('Multiple passwords have been changed in passbolt'))
                    ->setTemplate(static::RESOURCE_CHANGES_TEMPLATE)
                    ->setEmailRecipient($emailData->get('email'))
                    ->addTemplateVar('user', $emailData->get('template_vars')['body']['user'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);

                return $digest;
            }
        );
    }

    /**
     * Create a new digest for the resource share emails.
     * The marshaller will create a digest only if there is minimum 2 emails.
     * It will create another digest if there is more than 50 emails.
     *
     * @return \Passbolt\EmailDigest\Utility\Digest\Digest
     */
    private function createResourceShareDigest()
    {
        return new Digest(
            __('{0} shared several items with you', '{0}'),
            [
                ShareEmailRedactor::TEMPLATE,
            ],
            'owner',
            function (Entity $emailData, int $emailCount) {
                $digest = (new EmailDigest())
                    ->setSubject(__('Multiple passwords have been shared with you in passbolt'))
                    ->setTemplate(static::RESOURCE_SHARE_MULTIPLE_TEMPLATE)
                    ->setEmailRecipient($emailData->get('email'))
                    ->addTemplateVar('owner', $emailData->get('template_vars')['body']['owner'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);

                return $digest;
            }
        );
    }
}
