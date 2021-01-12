<?php
declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Entity\Avatar;
use Cake\Routing\Router;
use Cake\View\Helper;

class AvatarHelper extends Helper
{
    /**
     * @param \App\Model\Entity\Avatar $avatar Avatar instance
     * @param string $format Format of the avatar
     * @return string
     */
    public static function getAvatarUrl(Avatar $avatar, string $format = 'small')
    {
        $url = $avatar->url[$format];
        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['scheme']) || $parsedUrl['scheme'] === null) {
            // URL has no scheme, it is a local file path
            if (strpos(substr($url, 0, 1), '/', 0) === false) {
                // Relative path
                $url = ltrim($url, './'); // Cleanup relative paths with dot at the beginning
            } else {
                // Absolute path
                $url = ltrim($url, '/'); // Cleanup absolute path to avoid double slash
            }

            return Router::url('/' . $url, true);
        }

        return $url;
    }
}
