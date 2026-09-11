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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Service\Settings;

use App\Error\Exception\CustomValidationException;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\OfflineMode\Form\OfflineSettingsFormInterface;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;

class OfflineSettingsGetService
{
    use LocatorAwareTrait;

    private OfflineSettingsFormInterface $form;

    /**
     * @param \Passbolt\OfflineMode\Form\OfflineSettingsFormInterface $form The settings form.
     */
    public function __construct(OfflineSettingsFormInterface $form)
    {
        $this->form = $form;
    }

    /**
     * Read the offline-mode settings and validates it. Returns null when the org has not configured the feature.
     *
     * @return \Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto|null
     * @throws \App\Error\Exception\CustomValidationException When the stored row is unusable, or the stored
     *   values don't pass the validation.
     */
    public function get(): ?OfflineSettingsDto
    {
        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $table */
        $table = $this->fetchTable('Passbolt/OfflineMode.OfflineModeSettings');
        /** @var \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting|null $entity */
        $entity = $table->find()->first();
        if ($entity === null) {
            return null;
        }

        try {
            $dto = OfflineSettingsDto::createFromEntity($entity);
        } catch (InvalidArgumentException $e) {
            $msg = 'OfflineMode: the stored settings row is unusable.';
            if (Configure::read('debug')) {
                $msg .= ' ' . $e->getMessage();
            }
            Log::error($msg);

            throw new CustomValidationException(
                __('Could not validate offline settings data.'),
                ['value' => ['invalid' => __('The stored offline settings are not valid.')]],
            );
        }

        if (!$this->form->execute($dto->toSettingsArray())) {
            throw new CustomValidationException(
                __('Could not validate offline settings data.'),
                $this->form->getErrors(),
            );
        }

        return $dto;
    }

    /**
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException When Offline Mode is not enabled.
     */
    public function throwExceptionIfDisabled(): void
    {
        /** @var \Passbolt\OfflineMode\Model\Table\OfflineModeSettingsTable $table */
        $table = $this->fetchTable('Passbolt/OfflineMode.OfflineModeSettings');
        if (!$table->isOfflineModeFeatureEnabled()) {
            throw new ForbiddenException(__('Offline Mode is not enabled at the org level.'));
        }
    }
}
