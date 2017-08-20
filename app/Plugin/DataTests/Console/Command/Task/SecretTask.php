<?php
/**
 * Insert Secret Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataExtras.Console.Command.Task.SecretTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Secret', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Gpgkey', 'Model');

class SecretTask extends ModelTask {

    public $model = 'Secret';

    /**
     * Get Dummy Secret Data.
     *
     * The passwords are always returned in the same order, useful for cross checking.
     * This secret was encrypted with the dummy public key, also located in the repository.
     *
     * @return string
     */
    protected function getDummyPassword() {
        static $i = 0;
        $passwords = array(
            "testpassword",
            "123456",
            "qwerty",
            "111111",
            "iloveyou",
            "adbobe123",
            "admin",
            "letmein",
            "monkey",
            "adobe",
            "sunshine",
            "princess",
            "azerty",
            "trustno1",
            "iamgod",
            "love",
            "god",
            "business",
            "passbolt",
            "enova",
            "kevisthebest",
        );
        $password = $passwords[$i];
        $i++;
        if ($i > sizeof($passwords) - 1) {
            $i = 0;
        }
        return $passwords[$i];
    }

    /**
     * Encrypt a password with the user public key.
     * @param $password
     * @param $user
     * @return string $encrypted encrypted password
     */
    protected function encryptPassword($password, $user) {
        $GpgkeyTask = $this->Tasks->load('Data.Gpgkey');
	    $GpgkeyTask->params = $this->params;
        $gpgkeyPath = $GpgkeyTask->getGpgkeyPath($user['User']['id']);

		// Import the key
		exec('gpg --import ' . $gpgkeyPath . ' > /dev/null 2>&1');
		// Encrypt the password
		$command = "echo -n " . escapeshellarg($password) . " | gpg --encrypt -r " . $user['User']['username'] . " -a --trust-model always";
		exec($command, $output);

        $encrypted = implode("\n", $output);
		return $encrypted;
    }

    /**
     * Get a password for a given resource, get a random one if not found / unspecified
     * @param null $resourceId id
     * @return string password
     */
    protected function getPassword($resourceId = null) {
		static $passwords = [];

		if (isset($passwords[$resourceId])) {
			return $passwords[$resourceId];
		}

		$password = '';
        switch ($resourceId) {
            // Apache = very strong
            case Common::uuid('resource.id.apache') :
				$password = '_upjvh-p@wAHP18D}OmY05M';
				break;
            // April = strong
            case Common::uuid('resource.id.april') :
				$password = 'z"(-1s]3&Itdno:vPt';
				break;
            // Bower = fair
            case Common::uuid('resource.id.bower') :
				$password = 'CL]m]x(o{sA#QW';
				break;
            // Bower = this_23-04
            case Common::uuid('resource.id.centos') :
				$password = 'this_23-04';
				break;
            case Common::uuid('resource.id.enlightenment') :
				$password = 'azertyuiop';
				break;
            // Centos = very weak
            default :
				$password = $this->getDummyPassword();
				break;
        }

		$passwords[$resourceId] = $password;
		return $password;
    }

    /**
     * Get all Secret data.
     * @return array
     */
    protected function getData() {
		$User = $this->_getModel('User');
		$UserResourcePermission = $this->_getModel('UserResourcePermission');
		$Resource = $this->_getModel('Resource');
		$Resource->Behaviors->disable('Permissionable'); // cannot do a findAll otherwise
		$users = $User->find('all');

        // Insertion for all users who can access to available resources.
        // We insert dummy data, same secret for everyone.
        $s = [];

		foreach ($users as $user) {
			$resourcesIds = $UserResourcePermission->findAuthorizedResourcesIds($user['User']['id']);
			foreach ($resourcesIds as $resourceId) {
				$password = $this->getPassword($resourceId);
				$passwordEncrypted = $this->encryptPassword($password, $user);
				$s[] = array('Secret'=>array(
					'id' => Common::uuid(),
					'user_id' => $user['User']['id'],
					'resource_id' => $resourceId,
					'data' => $passwordEncrypted,
					'created_by' => $user['User']['id']
				));
			}
		}

        return $s;
    }
}
