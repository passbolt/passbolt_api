/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) 2022 Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2022 Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.1
 */

importScripts('/js/vendors/openpgp.min.js');

/**
 * Service worker that has for aim to generate the server OpenPGP key pair.
 * @param {string} name The key name
 * @param {string} email The key email
 * @param {string} port The communication port to use to return the result.
 * @return {Promise<object>} Object containing the generated key pair
 * @throw Error If the key cannot be generated
 */
onmessage = async ({data: {name, email}, ports: [port]}) => {
  try {
    const gpgKey = await openpgp.generateKey({
      type: 'rsa',
      rsaBits: 3072,
      userIDs: [{name, email}],
    });
    port.postMessage(gpgKey);
  } catch (error) {
    port.postMessage(error);
  }
}
