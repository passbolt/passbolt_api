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
 * @since         2.13.0
 */
namespace App\Utility\Healthchecks;

use InvalidArgumentException;

class Healthcheck
{
    public const STATUS_ERROR = 'error';
    public const STATUS_WARNING = 'warning';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_INFO = 'info';

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $category
     */
    protected $category;

    /**
     * @var bool $success
     */
    protected $success;

    /**
     * @var array $details additional details
     */
    protected $details;

    /**
     * Health check constructor
     *
     * @param string $name name
     * @param string $category category
     * @param bool|null $success true if success
     * @param array|null $details optional
     */
    public function __construct(string $name, string $category, ?bool $success = null, ?array $details = [])
    {
        $this->name = $name;
        $this->category = $category;
        $this->success = $success;
        $this->details = $details;
    }

    /**
     * Mark check as failed
     *
     * @return self
     */
    public function fail(): Healthcheck
    {
        $this->success = false;

        return $this;
    }

    /**
     * Mark check as pass
     *
     * @return self
     */
    public function pass(): Healthcheck
    {
        $this->success = true;

        return $this;
    }

    /**
     * @return string name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string category
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return array details
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * @param string $name name
     * @return self
     */
    public function setName(string $name): Healthcheck
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $category category
     * @return self
     */
    public function setCategory(string $category): Healthcheck
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param string $detail information message
     * @param string $status success, warning, error, info
     * @return self
     */
    public function addDetail(string $detail, string $status): Healthcheck
    {
        $allowedStatus = [
            self::STATUS_ERROR, self::STATUS_INFO, self::STATUS_SUCCESS, self::STATUS_WARNING,
        ];
        if (!in_array($status, $allowedStatus)) {
            throw new InvalidArgumentException('Invalid health check detail status: ' . $status);
        }
        $this->details[] = [
            'status' => $status,
            'message' => $detail,
        ];

        return $this;
    }
}
