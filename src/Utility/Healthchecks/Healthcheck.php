<?php
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

    const STATUS_ERROR = 'error';
    const STATUS_WARNING = 'warning';
    const STATUS_SUCCESS = 'success';
    const STATUS_INFO = 'info';

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
     * @param bool $success true if success
     * @param array $details optional
     */
    public function __construct(string $name, string $category, bool $success = null, array $details = [])
    {
        $this->name = $name;
        $this->category = $category;
        $this->success = $success;
        $this->details = $details;
    }

    /**
     * Mark check as failed
     * @return $this
     */
    public function fail()
    {
        $this->success = false;

        return $this;
    }

    /**
     * Mark check as pass
     * @return $this
     */
    public function pass()
    {
        $this->success = true;

        return $this;
    }

    /**
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return array details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param string $name name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $category category
     * @return $this
     */
    public function setCategory(string $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param string $detail information message
     * @param string $status success, warning, error, info
     * @return $this
     */
    public function addDetail(string $detail, string $status)
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
