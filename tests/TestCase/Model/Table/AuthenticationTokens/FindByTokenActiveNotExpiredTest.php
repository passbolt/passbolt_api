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
 * @since         5.17.0
 */
namespace App\Test\TestCase\Model\Table\AuthenticationTokens;

use App\Model\Entity\AuthenticationToken;
use App\Model\Table\AuthenticationTokensTable;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;

class FindByTokenActiveNotExpiredTest extends AppTestCase
{
    private AuthenticationTokensTable $AuthenticationTokens;

    public function setUp(): void
    {
        parent::setUp();
        /** @var \App\Model\Table\AuthenticationTokensTable $tokens */
        $tokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $this->AuthenticationTokens = $tokens;
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_ReturnsMatchingRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->active()
            ->persist();

        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $row->token, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNotNull($found);
        $this->assertSame($row->id, $found->id);
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_ReturnsNothingWhenTokenUnknown(): void
    {
        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: UuidFactory::uuid(), tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNull($found);
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_ExcludesInactiveRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->inactive()
            ->persist();

        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $row->token, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNull($found);
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_ExcludesExpiredRow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->active()
            ->created(DateTime::now()->modify('-6 minutes'))
            ->persist();

        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $row->token, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNull($found);
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_ExcludesRowWhenTypeMismatches(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_VERIFY)
            ->active()
            ->persist();

        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $row->token, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNull($found);
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_AcceptsRowJustBeforeExpiry(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->active()
            ->created(DateTime::now()->modify('-4 minutes 55 seconds'))
            ->persist();

        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $row->token, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNotNull($found);
        $this->assertSame($row->id, $found->id);
    }

    public function testAuthenticationTokensTable_FindByTokenActiveNotExpired_HonorsConfiguredExpiry(): void
    {
        Configure::write(
            'passbolt.auth.token.' . AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY . '.expiry',
            '1 hour',
        );
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\AuthenticationToken $row */
        $row = AuthenticationTokenFactory::make()
            ->userId($user->id)
            ->type(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->active()
            ->created(DateTime::now()->modify('-30 minutes'))
            ->persist();

        $found = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $row->token, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY)
            ->first();

        $this->assertNotNull($found);
        $this->assertSame($row->id, $found->id);
    }
}
