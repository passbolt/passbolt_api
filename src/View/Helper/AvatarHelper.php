<?php
declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Entity\Avatar;
use App\Model\Table\AvatarsTable;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\View\Helper;

class AvatarHelper extends Helper
{
    /**
     * @param \App\Model\Entity\Avatar|null $avatar Avatar instance
     * @param string|null $format Format of the avatar
     * @return string
     */
    public static function getAvatarUrl(?Avatar $avatar = null, ?string $format = AvatarsTable::FORMAT_SMALL): string
    {
        if (empty($avatar) || empty($avatar->get('data')) || empty($avatar->get('id'))) {
            return self::getAvatarFallBackUrl($format);
        } else {
            return Router::url([
                'plugin' => null,
                'prefix' => 'Avatars',
                'controller' => 'AvatarsView',
                'action' => 'view',
                'id' => $avatar->get('id'),
                'format' => $format,
            ], true);
        }
    }

    /**
     * @param string|null $format Image format.
     * @return string
     */
    public static function getAvatarFallBackUrl(?string $format = AvatarsTable::FORMAT_SMALL): string
    {
        return Router::url(Configure::readOrFail('FileStorage.imageDefaults.Avatar.' . $format), true);
    }
}
