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
    public const IMAGE_EXTENSION = '.jpg';

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
                '_ext' => trim(self::IMAGE_EXTENSION, '.'),
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

    /**
     * Checks if the format provided is medium or small
     *
     * @param bool $withExtension Append the image file extension.
     * @return array
     * @throws \RuntimeException if the avatar config is not set in config/file_storage.php
     */
    public static function getValidImageFormats(?bool $withExtension = true): array
    {
        $formats = array_keys(Configure::readOrFail('FileStorage.imageDefaults.Avatar'));
        if ($withExtension) {
            array_walk($formats, function (&$value, $key) {
                $value .= self::IMAGE_EXTENSION;
            });
        }

        return $formats;
    }
}
