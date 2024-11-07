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
namespace Passbolt\Tags\Controller\Tags;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\Tags\Form\MetadataResourcesAddExistingTagForm;
use Passbolt\Tags\Form\MetadataResourcesTagsAddForm;
use Passbolt\Tags\Service\Metadata\MetadataTagsRenderService;
use Passbolt\Tags\Service\Tags\ResourcesTagsAddService;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 * @property \Passbolt\Tags\Model\Table\TagsTable $Tags
 */
class ResourcesTagsAddController extends AppController
{
    use MetadataSettingsAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Resources = $this->fetchTable('Resources');
        $this->Tags = $this->fetchTable('Passbolt/Tags.Tags');
    }

    /**
     * Add tags for a given resource.
     * Providing an empty list of tags delete all the personal tags
     *
     * @param string $resourceId The identifier of the resource to add a comment to
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    public function addPost(string $resourceId)
    {
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        $uac = $this->User->getAccessControl();
        $data = $this->formatRequestData();
        $data = $this->validateRequestData($data);

        $options = ['contain' => ['all_tags' => 1, 'permission' => 1]];
        /** @var \App\Model\Entity\Resource|null $resource */
        $resource = $this->Resources->findView($uac->getId(), $resourceId, $options)->first();
        if (is_null($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        $tags = (new ResourcesTagsAddService())->add($uac, $resource, $data);
        $tags = (new MetadataTagsRenderService())->renderTags($tags);
        $this->success(__('The operation was successful.'), $tags);
    }

    /**
     * Get and format the request data.
     *
     * @return array
     */
    private function formatRequestData(): array
    {
        $data = $this->getRequest()->getData();

        // Data given in V1 format.
        // @deprecated with v2
        if (isset($data['Tags'])) {
            return $data['Tags'];
        }
        if (isset($data['tags'])) {
            return $data['tags'];
        }

        return [];
    }

    /**
     * Validates the request data.
     *
     * @param array $data Data to validate.
     * @return array Valid request data
     * @throws \App\Error\Exception\CustomValidationException If data is invalid.
     * @throws \Cake\Http\Exception\BadRequestException If V5 or V4 tag creation/modification is not allowed.
     */
    private function validateRequestData(array $data): array
    {
        $errors = [];
        foreach ($data as $i => $tag) {
            $errors[$i] = [];

            if (!is_string($tag) && !is_array($tag)) {
                $errors[$i][] = __('The tags data should be a string or an array.');
                continue;
            }

            if (is_array($tag)) {
                $this->assertV5TagCreationEnabled();

                if (array_key_exists('id', $tag)) {
                    $form = new MetadataResourcesAddExistingTagForm($this->User->getAccessControl());
                } else {
                    $form = new MetadataResourcesTagsAddForm();
                }

                if (!$form->validate($tag)) {
                    $errors[$i] = array_merge($errors[$i], $form->getErrors());
                }
            } else {
                $this->assertV4TagCreationEnabled();
            }

            if (empty($errors[$i])) {
                unset($errors[$i]);
            }
        }

        if (!empty($errors)) {
            throw new CustomValidationException(
                __('Could not validate request data.'),
                $errors,
                null,
                400
            );
        }

        return $data;
    }
}
