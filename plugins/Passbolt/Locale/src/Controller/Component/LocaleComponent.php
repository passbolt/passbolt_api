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
 * @since         3.2.0
 */
namespace Passbolt\Locale\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Locale\Utility\LocaleUtility;

/**
 * Locale Component
 * Class used for locale validation
 */
class LocaleComponent extends Component
{
    public const DATA_KEY = 'value';

    /**
     * Get the Locale in the request data, validate and return dashed
     *
     * @return string
     */
    public function handleRequestData(): string
    {
        $locale = $this->getController()->getRequest()->getData(self::DATA_KEY, '');
        if (!LocaleUtility::localeIsValid($locale)) {
            throw new BadRequestException(__('The locale {0} is not supported.', $locale));
        }

        return LocaleUtility::dasherizeLocale($locale);
    }
}
