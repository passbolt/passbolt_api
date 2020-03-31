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

use Cake\Event\EventListenerInterface;
use Passbolt\EmailDigest\Utility\Factory\DigestMarshallerFactory;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerPool;
use Passbolt\EmailDigest\Utility\Marshaller\DigestMarshallerRegisterTrait;
use Passbolt\EmailDigest\Utility\Marshaller\Type\ByTemplateAndExecutedByDigestMarshaller;
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
            ->addDigestMarshaller($this->createGroupAddDigestMarshaller())
            ->addDigestMarshaller($this->createDeleteGroupDigestMarshaller());
    }

    /**
     * @return ByTemplateAndExecutedByDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupAddDigestMarshaller()
    {
        $templates = [
            "LU/group_add",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} added the groups", "{0}"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndExecutedByDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupAddMemberDigestMarshaller()
    {
        $templates = [
            "LU/group_user_add",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} added you to groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndExecutedByDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createDeleteGroupDigestMarshaller()
    {
        $templates = [
            "LU/group_delete",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} deleted the groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndExecutedByDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupUpdateMembershipDigestMarshaller()
    {
        $templates = [
            "LU/group_user_update",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} updated your membership in groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }

    /**
     * @return ByTemplateAndExecutedByDigestMarshaller|MinimumThresholdSwitchDigestMarshaller
     */
    private function createGroupDeleteMemberDigestMarshaller()
    {
        $templates = [
            "LU/group_user_delete",
        ];

        $digestMarshaller = $this->digestMarshallerFactory->createByTemplateAndExecutedByEmailDigestMarshaller(
            __("{0} removed you from groups"),
            "admin"
        );

        foreach ($templates as $template) {
            $digestMarshaller->addSupportedTemplate($template);
        }

        return $this->digestMarshallerFactory->createThresholdMinimumSwitchDigestMarshaller($digestMarshaller);
    }
}
