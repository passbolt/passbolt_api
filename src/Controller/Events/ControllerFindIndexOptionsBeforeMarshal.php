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
 * @since         2.0.0
 */

namespace App\Controller\Events;

use App\Controller\AppController;
use App\Model\Table\Dto\FindIndexOptions;
use Cake\Event\Event;

class ControllerFindIndexOptionsBeforeMarshal extends Event
{
    public const EVENT_NAME = 'Controller.findIndexOptions.beforeMarshal';

    /**
     * @var \App\Controller\AppController
     */
    private $controller;

    /**
     * @var \App\Model\Table\Dto\FindIndexOptions
     */
    private $options;

    /**
     * @param string $name Name
     * @param \App\Controller\AppController $subject Subject must be an instance of Table
     * @param array $data Data
     */
    final public function __construct($name, AppController $subject, $data = null)
    {
        $this->setController($subject);
        $this->setOptions($data['options']);

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param \App\Model\Table\Dto\FindIndexOptions $options Options
     * @param \App\Controller\AppController $controller Table
     * @return \App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal
     */
    public static function create(FindIndexOptions $options, AppController $controller)
    {
        return new static(static::EVENT_NAME, $controller, [
            'options' => $options,
        ]);
    }

    /**
     * @param \App\Controller\AppController $controller Instance of Controller
     * @return $this
     */
    private function setController(AppController $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param \App\Model\Table\Dto\FindIndexOptions $options Instance of FindIndexOptions
     * @return $this
     */
    private function setOptions(FindIndexOptions $options)
    {
        $this->options = clone $options;

        return $this;
    }

    /**
     * Return an AppController
     *
     * @return \App\Controller\AppController
     */
    public function getController(): AppController
    {
        return $this->controller;
    }

    /**
     * @return \App\Model\Table\Dto\FindIndexOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
