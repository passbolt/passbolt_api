<?php
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
namespace App\Utility;

use Cake\Core\Exception\Exception;
use Cake\Http\ServerRequest;

/**
 * ActionLog singleton class.
 * Used to track user actions throughout the application.
 */
class UserAction
{
    /**
     * Unique user action id.
     * @var string (uuid)
     */
    private $userActionId;

    /**
     * User access control.
     * @var UserAccessControl
     */
    private $userAccessControl;

    /**
     * actionName.
     * @var string
     */
    private $actionName;

    /**
     * Description of the context
     * - In case of a controller/action, the controller action pair (Resources.create)
     * - In case of a command line being executed, the corresponding command line (without arguments).
     * @var string
     */
    private $context;

    /**
     * Instance of class used for singleton.
     * @var
     */
    private static $instance;

    /**
     * ActionLog constructor.
     * @param UserAccessControl $accessControl user access control object.
     * @param string $actionName action name
     * @param string $context context
     */
    private function __construct(UserAccessControl $accessControl, string $actionName, string $context)
    {
        $this->userActionId = UuidFactory::uuid();
        $this->actionName = $actionName;
        $this->context = $context;
        $this->userAccessControl = $accessControl;

        return $this;
    }

    /**
     * Get ActionLog singleton.
     *
     * @param UserAccessControl $accessControl user access control object
     * @param string $action action name. Example: "Resources.create"
     * @param string $context context. Example: "POST resources.json"
     *
     * @return UserAction
     */
    public static function getInstance(UserAccessControl $accessControl = null, string $action = null, string $context = null)
    {
        if (isset($accessControl) && isset($action) && isset($context)) {
            self::$instance = new UserAction($accessControl, $action, $context);
        }

        if (!isset(self::$instance)) {
            throw new Exception(__('UserAction has not been initialized yet.'));
        }

        return self::$instance;
    }

    /**
     * Init singleton from a Cakephp request.
     *
     * @param UserAccessControl|null $accessControl user access control object
     * @param ServerRequest|null $request server request object
     *
     * @return UserAction userAction object
     */
    public static function initFromRequest(UserAccessControl $accessControl, ServerRequest $request)
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }

        // Extract action name.
        $controller = $request->getParam('controller');
        $action = $request->getParam('action');
        $actionName = "$controller.$action";

        // Extract url.
        $uri = trim(strtok($request->getRequestTarget(), '?'));
        $method = $request->getMethod();
        $url = $method . ' ' . $uri;

        return self::getInstance($accessControl, $actionName, $url);
    }

    /**
     * delete singleton.
     * @return void
     */
    public static function destroy()
    {
        self::$instance = null;
    }

    /**
     * Get predictable user action id.
     * @return string uuid
     */
    public function getUserActionId()
    {
        return $this->userActionId;
    }

    /**
     * Get action  name.
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * Get Action id (based on action name).
     * Also used by Action entity
     * @return string action name
     */
    public function getActionId()
    {
        return UuidFactory::uuid($this->getActionName());
    }

    /**
     * Get context.
     * @return string context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Get User access control.
     * @return string context
     */
    public function getUserAccessControl()
    {
        return $this->userAccessControl;
    }

    /**
     * Set User access control.
     * This function exists because a user access control can possibly change during the execution.
     * @param UserAccessControl $userAccessControl user access control.
     * @return void
     */
    public function setUserAccessControl(UserAccessControl $userAccessControl)
    {
        $this->userAccessControl = $userAccessControl;
    }
}
