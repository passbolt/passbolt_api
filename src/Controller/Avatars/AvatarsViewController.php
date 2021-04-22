<?php
declare(strict_types=1);

namespace App\Controller\Avatars;

use App\Controller\AppController;
use App\View\Helper\AvatarHelper;
use Cake\Http\Response;

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
     * @return \Cake\Http\Response
     */
    public function view(string $id, string $format): Response
    {
        $this->loadModel('Avatars');

        $formatIsValid = $this->validateImageFormat($format);
        if ($formatIsValid === false) {
            $id = null;
        }

        $stream = $this->Avatars->readSteamFromId($id, $format);

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
