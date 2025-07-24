<?php
declare(strict_types=1);

namespace Passbolt\Scim\Test\TestCase\Controller;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Service\ScimSetSettingsService;
use Passbolt\Scim\Test\Factory\ScimOrgSettingFactory;
use Throwable;

/**
 * Passbolt\Scim\Controller\ScimSetSettingsController Test Case
 */
class ScimSetSettingsControllerTest extends AppIntegrationTestCase
{
    protected OrganizationSetting $current;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ScimPlugin::class);
    }

    public function setUpUpdate(): void
    {
        $this->current = ScimOrgSettingFactory::make()->default()->persist();
    }

    /**
     * Test setSettings method: create operation plugin disabled
     *
     * @return void
     */
    public function testSetSettings_Create_Error_PluginDisabled(): void
    {
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->postJson('/scim/setting.json');
        } catch (Throwable $t) {
        }

        $this->assertResponseCode(404);
    }

    /**
     * Test setSettings method: create operation guest forbidden
     *
     * @return void
     */
    public function testSetSettings_Create_Error_GuestForbidden(): void
    {
        $this->logInAsUser();

        $this->postJson('/scim/settings.json');

        $this->assertResponseCode(403);
    }

    /**
     * Test setSettings method: create operation unauthenticated
     *
     * @return void
     */
    public function testSetSettings_Create_Error_Unauthenticated()
    {
        $this->postJson('/scim/settings.json');

        $this->assertResponseCode(401);
    }

    /**
     * Test setSettings method: create settings already set
     *
     * @return void
     */
    public function testSetSettings_Create_SettingsAlreadySet()
    {
        $this->setUpUpdate();
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);

        $this->assertResponseCode(404);
        $this->assertSame('Please delete previous settings before creating again.', $this->_responseJsonHeader->message);
        $this->assertSame('error', $this->_responseJsonHeader->status);
    }

    /**
     * Data provider for create settings (POST)
     *
     * @return array[]
     * @throws \Exception
     */
    public static function updateDataProvider(): array
    {
        $data = static::generateData();

        $data[] = [
            [
                'setting_id' => UuidFactory::uuid(),
                'scim_user_id' => $data[0][0]['scim_user_id'] ,
            ],
            'setting_id.ensureEmpty',
            'The Setting ID cannot be passed on update.',
        ];

        return $data;
    }

    /**
     * Generate basic data for create/update operations
     *
     * @return array[]
     * @throws \Exception
     */
    protected static function generateData(): array
    {
        /** @var \App\Model\Entity\User $userActive */
        $userActive = UserFactory::make()->active()->persist();
        /** @var \App\Model\Entity\User $userNotActive */
        $userNotActive = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\User $userDisabled */
        $userDisabled = UserFactory::make()->disabled()->persist();

        $scimUserIdActive = $userActive->id;
        $secretToken = Hash::get(ScimOrgSettingFactory::make()->getDefaultValue(), 'secret_token');
        $scimUserIdNotActive = $userNotActive->id;
        $scimUserIdDisabled = $userDisabled->id;

        $data = [
            //Empty secret token
            [
                [
                    'scim_user_id' => $scimUserIdActive,
                ],
                'secret_token._empty',
                'The secret token should not be empty.',
            ],
            //Wrong format secret token
            [
                [
                    'scim_user_id' => $scimUserIdActive,
                    'secret_token' => 'WRONG_TOKEN',
                ],
                'secret_token.correctFormat',
                'The secret token format is incorrect.',
            ],
            //Wrong UUID for user
            [
                [
                    'scim_user_id' => 'WRONG_UUID',
                    'secret_token' => $secretToken,
                ],
                'scim_user_id.uuid',
                'The identifier of the default user should be a valid UUID.',
            ],
            //Not active user
            [
                [
                    'scim_user_id' => $scimUserIdNotActive,
                    'secret_token' => $secretToken,
                ],
                'scim_user_id.activeAndEnabled',
                'The user is not active, disabled or does not exist.',
            ],
            //Disabled user
            [
                [
                    'scim_user_id' => $scimUserIdDisabled,
                    'secret_token' => $secretToken,
                ],
                'scim_user_id.activeAndEnabled',
                'The user is not active, disabled or does not exist.',
            ],
            //Non-existing user
            [
                [
                    'scim_user_id' => UuidFactory::uuid(),
                    'secret_token' => $secretToken,
                ],
                'scim_user_id.activeAndEnabled',
                'The user is not active, disabled or does not exist.',
            ],
        ];

        return $data;
    }

    /**
     * Data provider for create settings (POST)
     *
     * @return array[]
     * @throws \Exception
     */
    public static function createDataProvider(): array
    {
        $settingId = UuidFactory::uuid();

        $data = static::generateData();

        foreach ($data as $i => $dataGroup) {
            $data[$i][0]['setting_id'] = $settingId;
        }

        //Empty setting_id
        $data[] = [
            [
                'setting_id' => null,
                'scim_user_id' => $data[0][0]['scim_user_id'] ,
            ],
            'setting_id._empty',
            'The ID for the SCIM settings should not be empty.',
        ];

        //Wrong UUID for setting_id
        $data[] = [
            [
                'setting_id' => 'WRONG_UUID',
                'scim_user_id' => $data[0][0]['scim_user_id'] ,
            ],
            'setting_id.uuid',
            'The ID for the SCIM settings should be a valid UUID.',
        ];

        //Duplicated setting_id
        $data[] = [
            [
                'setting_id' => '818b3361-e1a5-40cd-b423-775f1bd35c17',
                'scim_user_id' => $data[0][0]['scim_user_id'] ,
            ],
            'setting_id.notInUse',
            'The ID for the SCIM settings is already in use.',
        ];

        return $data;
    }

    /**
     * Test setSettings method: create operation validation errors
     *
     * @dataProvider createDataProvider
     * @return void
     */
    public function testSetSettings_Create_Validation($data, $key, $message)
    {
        $this->logInAsAdmin();

        if ($key === 'setting_id.notInUse') {
            $this->setUpUpdate();
        }

        $this->postJson('/scim/settings.json', $data);

        $response = json_decode(json_encode($this->_responseJsonBody), true);

        $this->assertResponseCode(400);
        $this->assertSame('Could not validate the SCIM settings.', $this->_responseJsonHeader->message);
        $this->assertSame('error', $this->_responseJsonHeader->status);

        $this->assertSame($message, Hash::get((array)$response, $key));
    }

    /**
     * Test setSettings method: create operation success
     *
     * @return void
     */
    public function testSetSettings_Create_Success()
    {
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);

        $response = $this->_responseJsonBody;

        $this->assertSuccess();
        $this->assertSame($data['setting_id'], $response->setting_id);
        $this->assertSame($data['scim_user_id'], $response->scim_user_id);
        $this->assertTrue(!isset($response->secret_token));
        $this->assertObjectHasAttribute('id', $response);

        //Check if secret token was correctly set
        $this->current = TableRegistry::getTableLocator()->get('OrganizationSettings')->find()->first();
        $values = json_decode($this->current->value, true);

        $this->assertSame(Security::hash($data['secret_token'], 'sha256'), $values['secret_token']);
    }

    /**
     * Test setSettings method: update operation plugin disabled
     *
     * @return void
     */
    public function testSetSettings_Update_Error_PluginDisabled(): void
    {
        $this->setUpUpdate();
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->putJson("/scim/settings/{$this->current->id}.json");
        } catch (Throwable $t) {
        }

        $this->assertResponseCode(404);
    }

    /**
     * Test setSettings method: update operation guest forbidden
     *
     * @return void
     */
    public function testSetSettings_Update_Error_GuestForbidden(): void
    {
        $this->setUpUpdate();
        $this->logInAsUser();

        $this->putJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(403);
    }

    /**
     * Test setSettings method: update operation unauthenticated
     *
     * @return void
     */
    public function testSetSettings_Update_Error_Unauthenticated()
    {
        $this->setUpUpdate();
        $this->putJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(401);
    }

    /**
     * Test setSettings method: update operation wrong UUID
     *
     * @return void
     */
    public function testSetSettings_Update_WrongUUID()
    {
        $this->setUpUpdate();
        $this->logInAsAdmin();

        $wrongUuid = UuidFactory::uuid();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $this->putJson("/scim/settings/{$wrongUuid}.json", $data);

        $this->assertResponseCode(404);
    }

    /**
     * Test setSettings method: update operation validation errors
     *
     * @dataProvider updateDataProvider
     * @return void
     */
    public function testSetSettings_Update_Validation($data, $key, $message)
    {
        $this->setUpUpdate();
        $this->logInAsAdmin();

        $this->postJson("/scim/settings/{$this->current->id}.json", $data);

        $response = json_decode(json_encode($this->_responseJsonBody), true);

        $this->assertResponseCode(400);
        $this->assertSame('Could not validate the SCIM settings.', $this->_responseJsonHeader->message);
        $this->assertSame('error', $this->_responseJsonHeader->status);

        $this->assertSame($message, Hash::get((array)$response, $key));
    }

    /**
     * Test setSettings method: update operation success
     *
     * @return void
     */
    public function testSetSettings_Update_Success()
    {
        $this->setUpUpdate();
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->putJson("/scim/settings/{$this->current->id}.json", $data);

        $response = $this->_responseJsonBody;

        $previousValues = json_decode($this->current->value, true);
        $this->assertSuccess();
        $this->assertSame($previousValues['setting_id'], $response->setting_id);
        $this->assertSame($data['scim_user_id'], $response->scim_user_id);
        $this->assertTrue(!isset($response->secret_token));
        $this->assertObjectHasAttribute('id', $response);

        //Check if secret token was correctly updated
        $this->current = TableRegistry::getTableLocator()->get('OrganizationSettings')->find()->first();
        $newValues = json_decode($this->current->value, true);

        $this->assertSame(Security::hash($data['secret_token'], 'sha256'), $newValues['secret_token']);
    }
}
