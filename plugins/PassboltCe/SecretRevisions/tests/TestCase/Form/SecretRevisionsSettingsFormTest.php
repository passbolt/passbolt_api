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
 * @since         5.7.0
 */

namespace Passbolt\SecretRevisions\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Core\Configure;
use Cake\Event\EventDispatcherTrait;
use Passbolt\SecretRevisions\Form\SecretRevisionsSettingsForm;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;

/**
 * @covers \Passbolt\SecretRevisions\Form\SecretRevisionsSettingsForm
 */
class SecretRevisionsSettingsFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    /**
     * @var SecretRevisionsSettingsForm $form
     */
    protected SecretRevisionsSettingsForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new SecretRevisionsSettingsForm();
        // Load plugin to set configuration
        $this->loadPlugins([SecretRevisionsPlugin::class]);
    }

    public function tearDown(): void
    {
        unset($this->form);
        parent::tearDown();
    }

    public static function getDefaultData(): array
    {
        return [
            'max_revisions' => 1,
            'allow_sharing_revisions' => false,
        ];
    }

    public function testSecretRevisionsSettingsForm_Success(): void
    {
        $result = $this->form->execute($this->getDefaultData());
        $this->assertTrue($result);
    }

    public function testSecretRevisionsSettingsForm_Success_AllowSharingRevisionsEnabled(): void
    {
        Configure::write('passbolt.plugins.secretRevisions.enableAllowSharingRevisions', true);
        $data = array_merge(self::getDefaultData(), ['allow_sharing_revisions' => true]);
        $result = $this->form->execute($data);
        $this->assertTrue($result);
    }

    public function testSecretRevisionsSettingsForm_Error_Empty(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        foreach (['max_revisions', 'allow_sharing_revisions'] as $prop) {
            $this->assertTrue(isset($errors[$prop]['_empty']));
        }
    }

    public static function invalidMaxRevisionsValueProvider(): array
    {
        return [[0], [-1], [-6879], [-99.99]];
    }

    /**
     * @dataProvider invalidMaxRevisionsValueProvider
     * @param mixed $value Value to check.
     * @return void
     */
    public function testSecretRevisionsSettingsForm_Error_InvalidMaxRevisions(mixed $value): void
    {
        $data = array_merge(self::getDefaultData(), ['max_revisions' => $value]);
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey('greaterThan', $this->form->getError('max_revisions'));
    }

    public function testSecretRevisionsSettingsForm_Error_MaxRevisionsLimitExceed(): void
    {
        Configure::write('passbolt.plugins.secretRevisions.maxRevisionsLimit', 5);
        $data = array_merge(self::getDefaultData(), ['max_revisions' => 6]);
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey('maxRevisionsLimit', $this->form->getError('max_revisions'));
    }

    public function testSecretRevisionsSettingsForm_Error_AllowSharingRevisionsDisabled(): void
    {
        $data = array_merge(self::getDefaultData(), ['allow_sharing_revisions' => true]);
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey('isRevisionsSharingAllowed', $this->form->getError('allow_sharing_revisions'));
    }
}
