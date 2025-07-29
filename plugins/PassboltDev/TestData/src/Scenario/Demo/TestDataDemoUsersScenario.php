<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\ProfileFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\ProfilesDataCommand;
use Passbolt\TestData\Command\Base\UsersDataCommand;

class TestDataDemoUsersScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new UsersDataCommand())->getData();
        $profileData = (new ProfilesDataCommand())->getData();
        $userFactories = [];
        foreach ($data as $user) {
            $userId = $user['id'];
            $userFactory = UserFactory::make($user);

            $profileFactory = $this->getProfileFactory($profileData, $userId);
            $this->setRole($userFactory, $user['role_id']);
            $userFactory
                ->with('Profiles', $profileFactory)
                ->persist();
        }

        return $userFactories;
    }

    /**
     * @param \App\Test\Factory\UserFactory $factory
     * @param string $roleId
     * @return void
     */
    private function setRole(UserFactory $factory, string $roleId): void
    {
        if ($roleId === UuidFactory::uuid('role.id.admin')) {
            $factory->admin();
        } elseif ($roleId === UuidFactory::uuid('role.id.user')) {
            $factory->user();
        } elseif ($roleId === UuidFactory::uuid('role.id.guest')) {
            $factory->guest();
        }
    }

    /**
     * @param array $profileData
     * @param string $userId
     * @return \App\Test\Factory\ProfileFactory
     */
    private function getProfileFactory(array $profileData, string $userId): ProfileFactory
    {
        $firstName = null;
        $profile = [];
        foreach ($profileData as $profile) {
            if ($profile['user_id'] === $userId) {
                /** @var string $firstName */
                $firstName = $profile['first_name'];
                break;
            }
        }

        $profileFactory = ProfileFactory::make($profile);
        $avatarFileName = PLUGINS . 'PassboltDev/TestData/config/img/avatar/' . strtolower($firstName) . '.jpg';
        if (file_exists($avatarFileName)) {
            $profileFactory->withAvatar($avatarFileName);
        }

        return $profileFactory;
    }
}
