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

namespace Passbolt\EmailDigest\Utility\Mailer;

/**
 * An EmailPreview represents a preview of the email exactly as it as be sent.
 * It contains the email rendered exactly as it would be send. It also contains the headers as it would
 * have been sent.
 */
class EmailPreview
{
    /**
     * @var string
     */
    private $headers;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $headers Headers for the email
     * @param string $content Content of the email
     */
    public function __construct(string $headers, string $content)
    {
        $this->headers = $headers;
        $this->content = $content;
    }

    /**
     * Return the generated content of the email
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Return the headers used to send the email
     *
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }
}
