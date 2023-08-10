<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AuditLog\Test\TestCase\Utility;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Passbolt\AuditLog\Test\Lib\ActionLogsOperationsTestTrait;
use Passbolt\AuditLog\Utility\ActionLogResultsParser;
use Passbolt\AuditLog\Utility\ResourceActionLogsFinder;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class ActionLogsFinderSecretAccessesTest extends LogIntegrationTestCase
{
    use ActionLogsOperationsTestTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Profiles',
        'app.Base/Resources',
        'app.Base/Permissions',
        'app.Base/Secrets',
    ];

    public function testAuditLogsActionLogsFinderSecretAccessSingle()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->simulateMultipleResourceGetWithSecrets($uac, [$resourceId]);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resource.Secrets.read');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertEquals($actionLogs[0]['data']['resource']['id'], $resourceId);
    }

    /**
     * Test retrieving secret access logs when multiple access to a secret were done during a single operation.
     * This can be the case when a multiple share occurs.
     * Expected result: only the secret access log corresponding to the requested resource should be returned.
     *
     * @throws \Exception
     */
    public function testAuditLogsActionLogsFinderSecretAccessMultiple()
    {
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->simulateMultipleResourceGetWithSecrets($uac, [$resourceId, UuidFactory::uuid('resource.id.cakephp')]);

        $ActionLogsFinder = new ResourceActionLogsFinder();
        $actionLogs = $ActionLogsFinder->find($uac, $resourceId);
        $actionLogs = (new ActionLogResultsParser($actionLogs->all(), ['resources' => [$resourceId]]))->parse();

        $this->assertEquals(count($actionLogs), 1);
        $this->assertEquals($actionLogs[0]['type'], 'Resource.Secrets.read');
        $this->assertTrue(isset($actionLogs[0]['data']));
        $this->assertTrue(isset($actionLogs[0]['data']['resource']));
        $this->assertEquals($actionLogs[0]['data']['resource']['id'], UuidFactory::uuid('resource.id.apache'));
    }
}
