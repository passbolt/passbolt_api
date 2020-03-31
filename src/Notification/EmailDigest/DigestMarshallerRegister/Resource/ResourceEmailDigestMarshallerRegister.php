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
 * @since         2.14.0
 */

namespace App\Notification\EmailDigest\DigestMarshallerRegister\Resource;

use Cake\Core\Configure;
use Cake\Event\EventListenerInterface;
use Cake\ORM\Entity;
use Passbolt\EmailDigest\Utility\Factory\DigestMarshallerFactory;
use Passbolt\EmailDigest\Utility\Mailer\EmailDigest;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerRegisterTrait;
use Passbolt\EmailDigest\Utility\Marshaller\Type\ByTemplateAndExecutedByDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\MaximumThresholdSwitchDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\MinimumThresholdSwitchDigestMarshaller;

/**
 * Register new digest marshallers related to Resources.
 * All templates matching the list of supported templates will be aggregated together in a same email.
 */
class ResourceEmailDigestMarshallerRegister implements EventListenerInterface
{
    use DigestMarshallerRegisterTrait;

    /**
     * @var DigestMarshallerFactory
     */
    private $digestMarshallerFactory;

    /**
     * ResourceEmailDigestMarshallerRegister constructor.
     * @param DigestMarshallerFactory|null $digestMarshallerFactory Factory
     */
    public function __construct(DigestMarshallerFactory $digestMarshallerFactory = null)
    {
        $this->digestMarshallerFactory = $digestMarshallerFactory ?? DigestMarshallerFactory::getInstance();
    }

    /**
     * @param DigestMarshallerPool $digestMarshallerPool Instance of the marshaller
     * @return void
     */
    public function addDigestMarshallers(DigestMarshallerPool $digestMarshallerPool)
    {
        $digestMarshallerPool->addDigestMarshaller($this->createResourceShareDigestMarshaller(2, 50));
        $digestMarshallerPool->addDigestMarshaller($this->createResourceChangesDigestMarshaller(2, 50));
    }

    /**
     * Create a new email digest marshaller for all emails related to changes on resources.
     * It will aggregate the emails for Create, Update and Delete operations in the same digest.
     *
     * The marshaller will create a digest only if there is minimum 2 emails.
     * It will create another digest if there is more than 50 emails.
     *
     * @param int $minimumThreshold Minimum threshold
     * @param int $maximumThreshold Maximum threshold
     * @return ByTemplateAndExecutedByDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createResourceChangesDigestMarshaller(int $minimumThreshold, int $maximumThreshold)
    {
        $supportedTemplates = [
            "LU/resource_create",
            "LU/resource_update",
            "LU/resource_delete",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} has made changes on resources", "{0}"),
            'user'
        );

        foreach ($supportedTemplates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        $digestMarshaller = $this->digestMarshallerFactory->createThresholdMaximumSwitchDigestMarshaller(
            $digestMarshaller,
            $maximumThreshold,
            function (Entity $emailData, int $emailCount) {
                $digest = (new EmailDigest())
                    ->setSubject(__('Resources are waiting for you on passbolt'))
                    ->setTemplate('LU/resource_change_too_many')
                    ->setEmailRecipient($emailData->email)
                    ->addTemplateVar('user', $emailData->template_vars['body']['user'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);

                return [$digest];
            }
        );

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller, $minimumThreshold);
    }

    /**
     * Create a new email digest marshaller for the resource share emails.
     * The marshaller will create a digest only if there is minimum 2 emails.
     * It will create another digest if there is more than 50 emails.
     * @param int $minimumThreshold Minimum threshold
     * @param int $maximumThreshold Maximum threshold
     * @return ByTemplateAndExecutedByDigestMarshaller|MaximumThresholdSwitchDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createResourceShareDigestMarshaller(int $minimumThreshold, int $maximumThreshold)
    {
        $supportedTemplates = [
            "LU/resource_share",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} has shared resources", "{0}"),
            'owner'
        );

        foreach ($supportedTemplates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        $digestMarshaller = $this->digestMarshallerFactory->createThresholdMaximumSwitchDigestMarshaller(
            $digestMarshaller,
            $maximumThreshold,
            function (Entity $emailData, int $emailCount) {
                $digest = (new EmailDigest())
                    ->setSubject(__('Resources are waiting for you on passbolt'))
                    ->setTemplate('LU/resource_share_too_many')
                    ->setEmailRecipient($emailData->email)
                    ->addTemplateVar('owner', $emailData->template_vars['body']['owner'])
                    ->addTemplateVar('fullBaseUrl', Configure::read('App.fullBaseUrl'))
                    ->addTemplateVar('count', $emailCount);

                return [$digest];
            }
        );

        $digestMarshaller = $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller, $minimumThreshold);

        return $digestMarshaller;
    }
}
