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
 * @since         3.4.0
 */
namespace Passbolt\EmailDigest\Test\TestCase\Service;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpFixtureFactories\ORM\FactoryTableRegistry;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailDigest\Service\ConvertEmailVariablesToJsonService;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

class ConvertEmailVariablesToJsonServiceTest extends AppTestCase
{
    use TruncateDirtyTables;

    public function testConvertEmailVariablesToJsonService_Convert()
    {
        $this->loadRoutes();

        // Create an email with objects in v3.3.0 mode
        // Forces the serialization to serialize (no Json)
        Configure::write('EmailQueue.serialization_type', 'email_queue.serialize');
        /**
         * @psalm-suppress InternalMethod
         * @psalm-suppress InternalClass
         */
        FactoryTableRegistry::getTableLocator()->clear();

        $users = UserFactory::make(2)->with('Roles')->with('Profiles.Avatars', ['data' => 'Foo'])->persist();
        $resource = ResourceFactory::make()->persist()->toArray();

        $originalUnsentEmail = EmailQueueFactory::make()
            ->setField('template_vars', compact('users', 'resource'))
            ->persist();


        // Switch to v3.3.1 mode, with variables saved in Json
        /**
         * @psalm-suppress InternalMethod
         * @psalm-suppress InternalClass
         */
        FactoryTableRegistry::getTableLocator()->clear();
        TableRegistry::getTableLocator()->clear();
        Configure::write('EmailQueue.serialization_type', 'email_queue.json'); // Set serialize type to Json

        $service = new ConvertEmailVariablesToJsonService();
        $service->convert();
        $this->assertThatEmailsAreInJsonFormat($originalUnsentEmail, $users, $resource);

        // Make a second run in case this conversion is performed multiple times on the same queue.
        // No error should be triggered.
        $service->convert();
        $this->assertThatEmailsAreInJsonFormat($originalUnsentEmail, $users, $resource);
    }

    private function assertThatEmailsAreInJsonFormat(
        EntityInterface $originalUnsentEmail,
        array $users,
        array $resource
    ): void {
        // ConvertEmailVariablesToJsonService was meant to migrate MySQL serialized email variables
        // into JSON. PostGRES came after and is therefore not concerned.
        if (ConnectionManager::get('test')->getDriver() instanceof Postgres) {
            $this->expectNotToPerformAssertions();
            return;
        }
        $hydratedVars = EmailQueueFactory::find()->where([
            'id' => $originalUnsentEmail->get('id'),
            'email' => $originalUnsentEmail->get('email'),
        ])->firstOrFail()->get('template_vars');

        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        $rawVars = $connection
            ->selectQuery()
            ->select('template_vars')
            ->from('email_queue')
            ->execute()
            ->fetchAll('assoc')[0]['template_vars'];

        $assertVars = function (array $vars) use ($users, $resource) {
            $this->assertSame($users[0]->username, $vars['users'][0]['username']);
            $this->assertSame($users[0]->role->name, $vars['users'][0]['role']['name']);
            $this->assertNull($vars['users'][0]['profile']['avatar']['data'] ?? null);
            $this->assertSame($users[1]->username, $vars['users'][1]['username']);
            $this->assertSame($users[1]->role->name, $vars['users'][1]['role']['name']);
            $this->assertNull($vars['users'][1]['profile']['avatar']['data'] ?? null);
            $this->assertSame($resource['name'], $vars['resource']['name']);
            // Dates are accessible under the $date['date] key!
            $this->assertInstanceOf(FrozenTime::class, FrozenTime::parse($vars['resource']['created']['date']));
        };

        $assertVars($hydratedVars);
        $assertVars(json_decode($rawVars, true));
    }

    public function testConvertEmailVariablesToJsonService_findUnsentEmails()
    {
        $service = new ConvertEmailVariablesToJsonService();
        $nSent = 3;
        $nNotSent = 2;
        EmailQueueFactory::make($nSent)->sent()->persist();
        $notSentEmails = EmailQueueFactory::make($nNotSent)->persist();
        $notSentEmailIds = Hash::extract($notSentEmails, '{n}.id');

        $retrievedEmails = $service->findUnsentEmails();
        $this->assertSame($nNotSent, count($retrievedEmails));
        foreach ($retrievedEmails as $email) {
            $this->assertTrue(in_array($email['id'], $notSentEmailIds));
        }
    }

    public function dataFor_testConvertEmailVariablesToJsonService_serializeStringToJson()
    {
        $entity = UserFactory::make()->with('Roles')->getEntity();
        $entities = UserFactory::make(2)->with('Roles')->getEntities();

        return [
            ['', []],
            [
                serialize(compact('entity')),
                ['entity' => $entity->toArray()],
            ],
            [
                serialize([$entity, $entity, ['Foo']]),
                [$entity->toArray(), $entity->toArray(), ['Foo']],
            ],
            [
                serialize(compact('entities')),
                ['entities' => [$entities[0]->toArray(), $entities[1]->toArray()]],
            ],
        ];
    }

    /**
     * @dataProvider dataFor_testConvertEmailVariablesToJsonService_serializeStringToJson
     */
    public function testConvertEmailVariablesToJsonService_serializeStringToJson($templateVars, $jsonVars)
    {
        $service = new ConvertEmailVariablesToJsonService();
        $result = $service->toArray($templateVars);
        $this->assertEquals($jsonVars, $result);
    }
}
