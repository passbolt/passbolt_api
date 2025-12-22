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

namespace Passbolt\SecretRevisions\Test\TestCase\Model\Table;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SecretRevisions\Model\Entity\SecretRevision;
use Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

/**
 * @covers \Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable
 */
class SecretRevisionsTableAfterSaveTest extends TestCase
{
    use TruncateDirtyTables;

    private SecretRevisionsTable $SecretRevisions;

    public function setUp(): void
    {
        parent::setUp();
        $this->SecretRevisions = TableRegistry::getTableLocator()->get('Passbolt/SecretRevisions.SecretRevisions');
        $this->loadPlugins([SecretRevisionsPlugin::class]);
    }

    public function tearDown(): void
    {
        SecretRevisionsSettingsGetService::clear();
        unset($this->SecretRevisions);
        parent::tearDown();
    }

    public function testSecretRevisionsTableAfterSave(): void
    {
        [$r1, $r2] = ResourceFactory::make(2)->persist();

        $maxRevision = 3;
        SecretRevisionsSettingsFactory::make()->setMaxRevisions($maxRevision)->persist();
        $activeSecretRevision = SecretRevisionFactory::make()
            ->with('Resources', $r1)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r1->get('id')))
            ->persist();
        SecretRevisionFactory::make(10)
            ->with('Resources', $r1)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r1->get('id'))->deleted())
            ->deleted()
            ->persist();
        SecretRevisionFactory::make(5)
            ->with('Resources', $r2)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r2->get('id'))->deleted())
            ->deleted()
            ->persist();

        // Soft delete the actual secret revision
        $this->SecretRevisions->softDelete($r1->get('id'));
        // and create a new one
        $secretRevision = $this->SecretRevisions->newEntity([
            'resource_id' => $r1->get('id'),
            'resource_type_id' => $r1->get('resource_type_id'),
        ], ['accessibleFields' => ['resource_id' => true, 'resource_type_id' => true]]);
        $this->SecretRevisions->save($secretRevision);

        // Assert the active secret revision was not deleted
        $this->assertInstanceOf(SecretRevision::class, $this->SecretRevisions->get($activeSecretRevision->get('id')));
        // Assert that all the secrets of the active revision are not deleted
        $this->assertSame(10, $this->SecretRevisions->Secrets->find()->where(['secret_revision_id' => $activeSecretRevision->get('id')])->count());
        // Assert that all secret revisions are left
        $this->assertSame(12, $this->SecretRevisions->find()->where(['resource_id' => $r1->get('id')])->count());
        // Assert that only 10 * ($maxRevisions - 1) secrets are left for r1
        $this->assertSame(10 * ($maxRevision - 1), $this->SecretRevisions->Secrets->find()->where(['resource_id' => $r1->get('id')])->count());

        // Assert that all the secret revision for r2 were untouched
        $this->assertSame(5, $this->SecretRevisions->find()->where(['resource_id' => $r2->get('id')])->count());
        // Assert that all the secrets for r2 are untouched
        $this->assertSame(10 * 5, $this->SecretRevisions->Secrets->find()->where(['resource_id' => $r2->get('id')])->count());
    }

    public function testSecretRevisionsTableAfterSave_With_Max_Secret_Revision_To_One(): void
    {
        $r1 = ResourceFactory::make()->persist();

        SecretRevisionFactory::make()
            ->with('Resources', $r1)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r1->get('id')))
            ->persist();
        SecretRevisionFactory::make(10)
            ->with('Resources', $r1)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r1->get('id'))->deleted())
            ->deleted()
            ->persist();

        // Soft delete the actual secret revision
        $this->SecretRevisions->softDelete($r1->get('id'));
        // and create a new one
        $secretRevision = $this->SecretRevisions->newEntity([
            'resource_id' => $r1->get('id'),
            'resource_type_id' => $r1->get('resource_type_id'),
        ], ['accessibleFields' => ['resource_id' => true, 'resource_type_id' => true]]);
        $this->SecretRevisions->save($secretRevision);

        // Assert that all the soft deleted revisions were dropped
        $this->assertSame(12, SecretRevisionFactory::find()->where(['resource_id' => $r1->get('id')])->count());
        // Since no secrets are associated to the new secret revision, no secret should be left in DB
        $this->assertSame(0, SecretFactory::find()->where(['resource_id' => $r1->get('id')])->count());
    }
}
