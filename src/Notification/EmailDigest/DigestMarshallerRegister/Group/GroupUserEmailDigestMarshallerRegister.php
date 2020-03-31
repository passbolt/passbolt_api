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

namespace App\Notification\EmailDigest\DigestMarshallerRegister\Group;

use App\Notification\Email\Redactor\Group\GroupDeleteEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUpdateMembershipEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserAddEmailRedactor;
use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use Cake\Event\EventListenerInterface;
use Passbolt\EmailDigest\Utility\Factory\DigestMarshallerFactory;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerRegisterTrait;
use Passbolt\EmailDigest\Utility\Marshaller\Type\ByTemplateAndOperatorDigestMarshaller;
use Passbolt\EmailDigest\Utility\Marshaller\Type\MinimumThresholdSwitchDigestMarshaller;

class GroupUserEmailDigestMarshallerRegister implements EventListenerInterface
{
    use DigestMarshallerRegisterTrait;

    /**
     * @var DigestMarshallerFactory
     */
    private $digestMarshallerFactory;

    /**
     * GroupUserEmailDigestMarshallerRegister constructor.
     * @param DigestMarshallerFactory|null $digestMarshallerFactory Factory
     */
    public function __construct(DigestMarshallerFactory $digestMarshallerFactory = null)
    {
        $this->digestMarshallerFactory = $digestMarshallerFactory ?? DigestMarshallerFactory::getInstance();
    }

    /**
     * @param DigestMarshallerPool $digestMarshallerPool Instance of the marshallers pool
     * @return void
     */
    public function addDigestMarshallers(DigestMarshallerPool $digestMarshallerPool)
    {
        $digestMarshallerPool
            ->addDigestMarshaller($this->createGroupAddMemberDigestMarshaller())
            ->addDigestMarshaller($this->createGroupUpdateMembershipDigestMarshaller())
            ->addDigestMarshaller($this->createGroupDeleteMemberDigestMarshaller())
            ->addDigestMarshaller($this->createDeleteGroupDigestMarshaller());
    }

    /**
     * @return ByTemplateAndOperatorDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupAddMemberDigestMarshaller()
    {
        $templates = [
            GroupUserAddEmailRedactor::TEMPLATE,
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndOperatorEmailDigestMarshaller(
            __("{0} added you to groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndOperatorDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createDeleteGroupDigestMarshaller()
    {
        $templates = [
            GroupDeleteEmailRedactor::TEMPLATE,
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndOperatorEmailDigestMarshaller(
            __("{0} deleted the groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndOperatorDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupUpdateMembershipDigestMarshaller()
    {
        $templates = [
            GroupUpdateMembershipEmailRedactor::TEMPLATE,
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndOperatorEmailDigestMarshaller(
            __("{0} updated your membership in groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndOperatorDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupDeleteMemberDigestMarshaller()
    {
        $templates = [
            GroupUserDeleteEmailRedactor::TEMPLATE,
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndOperatorEmailDigestMarshaller(
            __("{0} removed you from groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }
}
