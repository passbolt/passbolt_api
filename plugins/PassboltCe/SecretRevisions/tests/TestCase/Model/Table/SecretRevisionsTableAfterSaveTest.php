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
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SecretRevisions\Model\Entity\SecretRevision;
use Passbolt\SecretRevisions\Model\Table\SecretRevisionsTable;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsFactory;
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
        $activeSecretRevision = SecretRevisionsFactory::make()
            ->with('Resources', $r1)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r1->get('id')))
            ->persist();
        SecretRevisionsFactory::make(10)
            ->with('Resources', $r1)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r1->get('id'))->deleted())
            ->deleted()
            ->persist();
        SecretRevisionsFactory::make(5)
            ->with('Resources', $r2)
            ->with('Secrets', SecretFactory::make(10)->setField('resource_id', $r2->get('id'))->deleted())
            ->deleted()
            ->persist();

        $secretRevision = $this->SecretRevisions->newEntity([
            'resource_id' => $r1->get('id'),
            'resource_type_id' => $r1->get('resource_type_id'),
        ], ['accessibleFields' => ['resource_id' => true, 'resource_type_id' => true]]);
        $this->SecretRevisions->save($secretRevision);

        // Assert the active secret revision was not deleted
        $this->assertInstanceOf(SecretRevision::class, $this->SecretRevisions->get($activeSecretRevision->get('id')));
        // Assert that all the secrets of the active revision are not deleted
        $this->assertSame(10, $this->SecretRevisions->Secrets->find()->where(['secret_revision_id' => $activeSecretRevision->get('id')])->count());
        // Assert that only $maxRevision secret revisions are left
        $this->assertSame($maxRevision, $this->SecretRevisions->find()->where(['resource_id' => $r1->get('id')])->count());
        // Assert that only 10 * ($maxRevisions - 1) secrets are left for r1
        $this->assertSame(10 * ($maxRevision - 1), $this->SecretRevisions->Secrets->find()->where(['resource_id' => $r1->get('id')])->count());

        // Assert that all the secret revision for r2 were untouched
        $this->assertSame(5, $this->SecretRevisions->find()->where(['resource_id' => $r2->get('id')])->count());
        // Assert that all the secrets for r2 are untouched
        $this->assertSame(10 * 5, $this->SecretRevisions->Secrets->find()->where(['resource_id' => $r2->get('id')])->count());
    }

    public function testSecretRevisionsTableAfterSave_Trim_Deleted_First(): void
    {
        $maxRevision = 4;
        SecretRevisionsSettingsFactory::make()->setMaxRevisions($maxRevision)->persist();

        $r = ResourceFactory::make()->persist();
        $resourceId = $r->get('id');
        $revisionNotDeleted1 = SecretRevisionsFactory::make()->with('Resources', $r)->persist();
        $revisionNotDeleted2 = SecretRevisionsFactory::make()->with('Resources', $r)->persist();
        SecretRevisionsFactory::make()->with('Resources', $r)->deleted(DateTime::yesterday())->persist();
        $revisionDeletedToday = SecretRevisionsFactory::make()->with('Resources', $r)->deleted(DateTime::today())->persist();

        $newSecretRevision = $this->SecretRevisions->newEntity([
            'resource_id' => $resourceId,
            'resource_type_id' => $r->get('resource_type_id'),
        ], ['accessibleFields' => ['resource_id' => true, 'resource_type_id' => true]]);
        $this->SecretRevisions->save($newSecretRevision);

        $expectedRevisionIdsLeft = [$revisionNotDeleted1->get('id'), $revisionNotDeleted2->get('id'), $revisionDeletedToday->get('id'), $newSecretRevision->get('id')];

        foreach ($expectedRevisionIdsLeft as $id) {
            $this->SecretRevisions->exists(['id' => $id]);
        }
        $this->assertSame($maxRevision, SecretRevisionsFactory::count());
    }
}
