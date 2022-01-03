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
namespace Passbolt\Locale\Test\Lib;

use App\Utility\Filesystem\DirectoryUtility;
use Cake\I18n\I18n;

trait DummyTranslationTestTrait
{
    /**
     * Set some translations for testing purpose.
     *
     * @psalm-suppress InternalMethod
     */
    public function setDummyFrenchTranslator(): void
    {
        /** @psalm-suppress InternalMethod */
        I18n::getTranslator('default', 'fr_FR')->getPackage()->addMessages([
            'Sending email from: {0}' => 'Courriel envoyé de: {0}',
            'Sending email to: {0}' => 'Courriel envoyé à: {0}',
            $this->getDummyEnglishEmailSentence() => $this->getDummyFrenchEmailSentence(),
        ]);
    }

    public function clearTranslationCache()
    {
        DirectoryUtility::removeRecursively(CACHE . DS . 'persistent');
    }

    public function getDummyEnglishEmailSentence(): string
    {
        return 'This is an email in english.';
    }

    public function getDummyFrenchEmailSentence(): string
    {
        return 'Ceci est un courriel en francais.';
    }
}
