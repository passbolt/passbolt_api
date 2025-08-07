<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Security;

use App\Test\Factory\ProfileFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Security\XssProfilesDataCommand;
use Passbolt\TestData\Command\Security\XssUsersDataCommand;

class TestDataSecurityXssUsersScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $userData = (new XssUsersDataCommand())->getData();
        $profileData = (new XssProfilesDataCommand())->getData();
        $userFactories = [];
        foreach ($userData as $user) {
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
        $profile = [];
        foreach ($profileData as $profile) {
            if ($profile['user_id'] === $userId) {
                break;
            }
        }

        return ProfileFactory::make($profile);
    }
}
