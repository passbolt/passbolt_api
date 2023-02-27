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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Form\Settings;

use App\Model\Validation\EmailValidationRule;
use Cake\Validation\Validator;

class SelfRegistrationEmailDomainsSettingsForm extends SelfRegistrationBaseSettingsForm
{
    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator = parent::validationDefault($validator);

        $dataValidator = new Validator();
        $dataValidator
            ->notEmptyArray('allowed_domains', __('The list of allowed domains should not be empty.'))
            ->add('allowed_domains', 'areEmailDomainsValid', [
                'rule' => [$this, 'areEmailDomainsValidRule'],
            ]);

        return $validator->addNested('data', $dataValidator);
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->sanitizeData($data);

        return parent::execute($data, $options);
    }

    /**
     * Check that all the domains are valid.
     *
     * @param mixed $domains Value to check
     * @return bool|string True if the validation succeed. Return the error message otherwise.
     */
    public function areEmailDomainsValidRule($domains)
    {
        if (!is_array($domains)) {
            return __('The list of allowed domains should be an array of strings.');
        }
        foreach ($domains as $k => $domain) {
            if (!EmailValidationRule::check("noreply@$domain")) {
                return __('The domain #{0} should be a valid domain.', $k);
            }
        }

        return true;
    }

    /**
     * @param array $data data to sanitize
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        $domains = $data['data']['allowed_domains'] ?? [];
        if (is_array($domains)) {
            $domains = array_values($domains);
        }
        $data['data'] = ['allowed_domains' => $domains];

        return $data;
    }
}
