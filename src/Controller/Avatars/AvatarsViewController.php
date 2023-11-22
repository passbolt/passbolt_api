<?php
declare(strict_types=1);

namespace App\Controller\Avatars;

use App\Controller\AppController;
use App\Service\Avatars\AvatarsCacheService;
use App\View\Helper\AvatarHelper;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\Log\Log;
use League\Flysystem\FilesystemAdapter;

/**
 * AvatarsViewController
 *
 * @property \App\Model\Table\AvatarsTable $Avatars
 */
class AvatarsViewController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['view']);

        return parent::beforeFilter($event);
    }

    /**
     * view method
     *
     * @param string $id Avatar id.
     * @param string $format Avatar format.
     * @param \League\Flysystem\FilesystemAdapter $filesystemAdapter file system adapter to read the avatars
     * @return \Cake\Http\Response
     */
    public function view(
        string $id,
        string $format,
        FilesystemAdapter $filesystemAdapter
    ): Response {
        $formatIsValid = $this->validateImageFormat($format);
        if ($formatIsValid === false) {
            $id = null;
        }

        $service = new AvatarsCacheService($filesystemAdapter);

        try {
            $stream = $service->readSteamFromId($id, $format);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            throw new NotFoundException($e->getMessage());
        }

        return $this->getResponse()
            ->withType('jpg')
            ->withBody($stream);
    }

    /**
     * Checks if the format provided is medium or small,
     * and that the extension is .jpg
     *
     * @param string $format Image format provided in the requested url.
     * @return bool
     */
    protected function validateImageFormat(string $format): bool
    {
        $validFormats = AvatarHelper::getValidImageFormats();

        return in_array($format, $validFormats);
    }
}
