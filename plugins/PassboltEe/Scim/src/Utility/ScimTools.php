<?php
declare(strict_types=1);

namespace Passbolt\Scim\Utility;

use Cake\Routing\Router;

/**
 * Utility class
 */
class ScimTools
{
    public const API_URL_PLACEHOLDER = '{scimUrl}';

    /**
     * @param string $json
     * @param string $settingId
     * @return string
     */
    public static function replacePlaceholders(string $json, string $settingId): string
    {
        return str_replace('{scimUrl}', Router::url('scim/v2/' . $settingId, true), $json);
    }
}
