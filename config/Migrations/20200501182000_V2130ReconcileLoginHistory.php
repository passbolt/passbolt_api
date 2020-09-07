<?php
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
 * @since         2.13.0
 */

use Migrations\AbstractMigration;
use Cake\Validation\Validation;

/**
 * Class V2130ReconcileLoginHistory
 * Reconcile all action logs for a successful login with their corresponding  user_id.
 */
class V2130ReconcileLoginHistory extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $loginActions = $this->fetchAll("SELECT id, created FROM action_logs WHERE user_id IS NULL AND action_id=(SELECT id FROM actions WHERE name='AuthLogin.loginPost') AND status=1 ORDER BY created ASC");
        if (empty($loginActions) || !Validation::datetime($loginActions[0]['created'])) {
            return;
        }

        $loginTokens = $this->fetchAll("SELECT id, user_id, modified FROM authentication_tokens WHERE type='login' AND active=0 AND modified >= '{$loginActions[0]['created']}'");
        $loginActionsToUpdate = [];
        foreach ($loginTokens as $loginToken) {
            foreach($loginActions as $key => $loginAction) {
                if($loginAction['created'] == $loginToken['modified']) {
                    $loginAction['user_id'] = $loginToken['user_id'];
                    $loginActionsToUpdate[] = $loginAction;
                    unset($loginAction[$key]);
                    break;
                }
            }
        }

        if (!empty($loginActionsToUpdate)) {
            foreach($loginActionsToUpdate as $loginAction) {
                if (Validation::uuid($loginAction['user_id']) && Validation::uuid($loginAction['id'])) {
                    $this->execute("UPDATE action_logs SET user_id='{$loginAction['user_id']}' where id='{$loginAction['id']}'");
                }
            }
        }
    }
}
