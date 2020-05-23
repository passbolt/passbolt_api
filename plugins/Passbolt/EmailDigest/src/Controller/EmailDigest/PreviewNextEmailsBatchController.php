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
 * @since         3.0.0
 */
namespace Passbolt\EmailDigest\Controller\EmailDigest;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Exception;
use Passbolt\EmailDigest\Service\PreviewEmailBatchService;

class PreviewNextEmailsBatchController extends AppController
{
    /**
     * @param Event $event Event to use
     * @return Response|null
     */
    public function beforeFilter(Event $event)
    {
        if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
            $this->Auth->allow('preview');
        } else {
            throw new NotFoundException();
        };

        return parent::beforeFilter($event);
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function preview()
    {
        $batchLimit = $this->getRequest()->getQueryParams()['limit'] ?? 10;

        $previewService = new PreviewEmailBatchService();
        $previews = $previewService->previewNextEmailsBatch($batchLimit);

        $previewContent = '';
        foreach ($previews as $preview) {
            $previewContent .= '<pre>' . $preview->getHeaders() . '</pre>';
            $previewContent .= $preview->getContent();
            $previewContent .= '<hr>';
        }

        return $this->response
            ->withStringBody($previewContent)
            ->withType('html');
    }
}
