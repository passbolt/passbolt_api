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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility\Object;

use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Utility\ScimObjectInterface;
use Tmilos\ScimFilterParser\Mode;
use Tmilos\ScimFilterParser\Parser;

/**
 * Operation class
 */
class Operation implements ScimObjectInterface
{
    /**
     * Operation types
     */
    public const TYPE_ADD = 'add';
    public const TYPE_REPLACE = 'replace';
    public const TYPE_REMOVE = 'remove';

    /**
     * Operation type
     *
     * @var string|null
     */
    protected ?string $operationType = null;

    /**
     * Attribute path
     *
     * @var string|null
     */
    protected ?string $path = null;

    /**
     * Value
     *
     * @var mixed
     */
    protected mixed $value = null;

    /**
     * Path filter data
     *
     * @var array
     */
    protected array $pathData = [];

    /**
     * Constructor
     *
     * @param string|null $operationType
     * @param string|null $path
     * @param mixed $value
     */
    public function __construct(?string $operationType = null, ?string $path = null, mixed $value = null)
    {
        $this->setType($operationType);
        $this->path = $path;
        $this->setPathData($this->path);
        $this->value = $value;
    }

    /**
     * Set the path filter array
     *
     * @param string|null $path
     * @return void
     */
    protected function setPathData(?string $path): void
    {
        $this->pathData = [];
        if ($path !== null) {
            $parser = new Parser(Mode::PATH());
            $this->pathData = $parser->parse($path)->dump();
        }
    }

    /**
     * Set the data from the SCIM request data array
     *
     * @param array $data Data from SCIM
     * @return self
     */
    public function setFromScim(array $data): self
    {
        $this->setType($data['op'] ?? null);
        $this->path = $data['path'] ?? null;
        $this->setPathData($this->path);
        $this->value = $data['value'] ?? null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        return [
            'op' => $this->operationType,
            'path' => $this->path,
            'value' => $this->value,
        ];
    }

    /**
     * Return the operation type
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->operationType;
    }

    /**
     * @param string|null $operationType
     * @return $this
     */
    protected function setType(?string $operationType)
    {
        if ($operationType === null) {
            $this->operationType = null;

            return $this;
        }
        $operationType = strtolower($operationType);
        if (!self::isValidType($operationType)) {
            throw new ScimException(sprintf('The operation type `%s` is not supported or invalid', $operationType));
        }
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * Return the path
     *
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Return the value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Return the attribute for the operation
     *
     * @return string|null
     */
    public function getAttribute(): ?string
    {
        if (isset($this->pathData['ValuePath'])) {
            return $this->pathData['ValuePath'][0]['AttributePath'] ?? null;
        }

        return $this->pathData['Path']['AttributePath'] ?? null;
    }

    /**
     * Return the subAttribute for the operation
     *
     * @return string|null
     */
    public function getSubAttribute(): ?string
    {
        if (isset($this->pathData['ValuePath'])) {
            return $this->pathData['AttributePath'] ?? null;
        }

        return null;
    }

    /**
     * Return the Comparison Expression for the operation
     *
     * @return string|null
     */
    public function getComparisonExpression(): ?string
    {
        if (isset($this->pathData['ValuePath'])) {
            return $this->pathData['ValuePath'][1]['ComparisonExpression'] ?? null;
        }

        return null;
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isValidType(string $type): bool
    {
        return in_array($type, [
            self::TYPE_ADD,
            self::TYPE_REPLACE,
            self::TYPE_REMOVE,
        ]);
    }
}
