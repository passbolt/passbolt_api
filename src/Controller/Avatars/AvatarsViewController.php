<?php
declare(strict_types=1);

namespace App\Controller\Avatars;

use App\Controller\AppController;
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

        /** @var \App\Model\Entity\Avatar $avatar */
        $avatar = $this->Avatars->findById($id)->first();

        if (is_null($avatar)) {
            $fileName = $this->Avatars->getFallBackFileName($format);
        } else {
            $fileName = $this->Avatars->readFromCache($avatar, $format);
        }

        return $this->getResponse()->withFile($fileName);
    }
}
