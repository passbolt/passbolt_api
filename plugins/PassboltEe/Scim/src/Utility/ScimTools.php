<?php
declare(strict_types=1);

namespace Passbolt\Scim\Utility;

use Cake\I18n\DateTime;
use Cake\Routing\Router;

/**
 * Utility class
 */
class ScimTools
{
    public const API_URL_PLACEHOLDER = '{scimUrl}';
    public const API_FORMAT_DATETIME = 'Y-m-d\TH:i:s.v\Z';

    /**
     * @param string $json
     * @param string $settingId
     * @return string
     */
    public static function replacePlaceholders(string $json, string $settingId): string
    {
        return str_replace('{scimUrl}', Router::url('scim/v2/' . $settingId, true), $json);
    }

    /**
     * @param \Cake\I18n\DateTime $dateTime
     * @return string
     */
    public static function formatDateTimeToScim(DateTime $dateTime): string
    {
        return $dateTime->format(self::API_FORMAT_DATETIME);
    }
}
