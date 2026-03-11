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
 * @since         5.10.0
 */
namespace Passbolt\ExportPolicies\Model\Dto;

class ExportPoliciesSettingsDto
{
    /**
     * Source default.
     *
     * @var string
     */
    public const SOURCE_DEFAULT = 'default';

    /**
     * Source env.
     *
     * @var string
     */
    public const SOURCE_ENV = 'env';

    /**
     * Source file.
     *
     * @var string
     */
    public const SOURCE_FILE = 'file';

    /**
     * Default value for allow CSV format.
     *
     * @var bool
     */
    public const DEFAULT_ALLOW_CSV_FORMAT = false;

    /**
     * @var bool|null
     */
    public ?bool $allow_csv_format = null;

    /**
     * @var string|null
     */
    public ?string $source = null;

    /**
     * @param string|bool|null $allowCsvFormat Allow CSV format flag.
     * @param string|null $source Source of these settings (can be: file, env or default).
     */
    public function __construct(
        bool|string|null $allowCsvFormat,
        ?string $source
    ) {
        $this->allow_csv_format = self::marshalBoolean($allowCsvFormat);
        $this->source = $source ?? self::SOURCE_DEFAULT;
    }

    /**
     * Marshal a value to boolean.
     * Handles string values like "true", "false", "1", "0" from environment variables.
     *
     * @param mixed $value Value to marshal.
     * @return bool|null
     */
    private static function marshalBoolean(mixed $value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        } elseif (is_string($value)) {
            $value = strtolower($value);
            if ($value === 'true' || $value === '1') {
                return true;
            }
            if ($value === 'false' || $value === '0') {
                return false;
            }
        }

        return null;
    }

    /**
     * Returns object of itself from provided array.
     *
     * @param array $data Data.
     * @return self
     */
    public static function createFromArray(array $data): self
    {
        return new self(
            $data['allow_csv_format'] ?? null,
            $data['source'] ?? null,
        );
    }

    /**
     * Returns array representation of the object.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'allow_csv_format' => $this->allow_csv_format,
            'source' => $this->source,
        ];
    }

    /**
     * Create DTO from default export policies settings.
     *
     * @param array $data Data to override.
     * @return self
     */
    public static function createFromDefault(array $data = []): self
    {
        return self::createFromArray(array_merge([
            'allow_csv_format' => self::DEFAULT_ALLOW_CSV_FORMAT,
            'source' => self::SOURCE_DEFAULT,
        ], $data));
    }
}
