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
 * @since         4.0.0
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\ORM\Query;

class ObfuscateFieldsComponent extends Component
{
    /**
     * Default placeholder
     */
    public const FIELD_PLACEHOLDER = '__PASSBOLT_OBFUSCATE_FIELDS_COMPONENT_PLACEHOLDER__';

    /**
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [
        'fields' => [],
        'placeholder' => self::FIELD_PLACEHOLDER,
    ];

    /**
     * Initialize callback
     *
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        if (empty($config['placeholder'])) {
            $this->setConfig(
                'placeholder',
                Configure::read('passbolt.obfuscateFields.placeholder', self::FIELD_PLACEHOLDER)
            );
        }
    }

    /**
     * beforeFilter callback
     *
     * This callback is executed before action. It inspects data
     * from request to remove any monitored field which didn't change
     * (Field Value = Placeholder)
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        if ($this->getController()->getRequest()->is(['post', 'put'])) {
            $data = $this->removePlaceholderFromData($this->getController()->getRequest()->getData());
            $this->getController()->setRequest($this->getController()->getRequest()->withParsedBody($data));
        }
    }

    /**
     * beforeRender callback
     *
     * This callback is executed after action. It inspects
     * body viewVar to replace any monitored field with
     * the placeholder.
     *
     * `body` may contain a Query or be an array. If a
     * Query it iterates results and obfuscate fields on each
     * element. If array it just calls obfuscateFields method.
     *
     * @param \Cake\Event\EventInterface $event Event
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        $body = $this->getController()->viewBuilder()->getVar('body');
        if ($body instanceof Query) {
            $body = $body->toArray();
        }

        if (!is_array($body)) {
            return;
        }

        $body = $this->obfuscateFields($body);
        $this->getController()->set('body', $body);
    }

    /**
     * Replace the current value of the field with a placeholder
     *
     * Iterates over the monitored fields and checks body. If present
     * it replaces the value with the placeholder
     *
     * @param array $body Data to obfuscate fields
     * @return array
     */
    protected function obfuscateFields(array $body): array
    {
        $fields = $this->getConfig('fields');
        $placeholder = $this->getConfig('placeholder');
        foreach ($body as $field => $value) {
            if ($value instanceof EntityInterface) {
                $value = $value->toArray();
            }
            if (is_array($value)) {
                $body[$field] = $this->obfuscateFields($value);
            } elseif (in_array($field, $fields, true)) {
                $body[$field] = $placeholder;
            }
        }

        return $body;
    }

    /**
     * Remove un-modified fields from data
     *
     * Fields which were modified will have a value
     * different than PLACEHOLDER. We want to remove
     * from data, any field with value = PLACEHOLDER
     *
     * It only applies to fields set by config.
     *
     * @param array $data Data to check modified fields
     * @return array
     */
    protected function removePlaceholderFromData(array $data): array
    {
        $fields = $this->getConfig('fields');
        $placeholder = $this->getConfig('placeholder');
        $result = [];
        foreach ($data as $field => $value) {
            if (is_array($value)) {
                $result[$field] = $this->removePlaceholderFromData($value);
            } elseif (!in_array($field, $fields) || $value !== $placeholder) {
                $result[$field] = $value;
            }
        }

        return $result;
    }
}
