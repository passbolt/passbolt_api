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
 * @since         2.0.0
 */

namespace App\Controller\Events;

use App\Model\Table\Dto\FindIndexOptions;
use Cake\Controller\Controller;
use Cake\Event\Event;

class ControllerFindIndexOptionsBeforeMarshal extends Event
{
    const EVENT_NAME = 'Controller.findIndexOptions.beforeMarshal';

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var FindIndexOptions
     */
    private $options;

    /**
     * @param string $name Name
     * @param null $subject Subject must be an instance of Table
     * @param null $data Data
     */
    public function __construct($name, $subject = null, $data = null)
    {
        $this->setController($subject);
        $this->setOptions($data['options'] ?? null);

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param FindIndexOptions $options Options
     * @param Controller $controller Table
     * @return ControllerFindIndexOptionsBeforeMarshal
     */
    public static function create(FindIndexOptions $options, Controller $controller)
    {
        return new static(static::EVENT_NAME, $controller, [
            'options' => $options
        ]);
    }

    /**
     * @param Controller $controller Instance of Controller
     * @return $this
     */
    private function setController(Controller $controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param FindIndexOptions $options Instance of FindIndexOptions
     * @return $this
     */
    private function setOptions(FindIndexOptions $options)
    {
        $this->options = clone $options;

        return $this;
    }

    /**
     * Return an instance of Table
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return FindIndexOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
}
