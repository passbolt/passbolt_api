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
namespace Passbolt\EmailDigest\Utility\Marshaller;

/**
 * Components interested into adding new marshaller through the event system can use this convenience trait.
 * It eases the registration of marshaller by providing boilerplate code to add new digest marshaller instances to the marshaller pool.
 * Only the method "addDigestMarshaller" needs to be implemented by registers.
 */
trait DigestMarshallerRegisterTrait
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            DigestMarshallerRegisterEvent::EVENT_NAME => $this,
        ];
    }

    /**
     * A digest marshaller register must implement this method to register the marshaller that it want to provide.
     * @param DigestMarshallerPool $digestMarshallerPool Marshaller Pool
     * @return mixed
     */
    abstract public function addDigestMarshallers(DigestMarshallerPool $digestMarshallerPool);

    /**
     * @param DigestMarshallerRegisterEvent $event An instance of the event
     * @return void
     */
    public function __invoke(DigestMarshallerRegisterEvent $event)
    {
        $this->addDigestMarshallers($event->getEmailDigestMarshallerPool());
    }
}
