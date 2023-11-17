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
 * @since         3.0.0
 */
namespace Passbolt\EmailDigest\Utility\Digest;

/**
 * DigestsTemplateRegistry collects the digest templates
 *
 * Emails will be gathered in a digest if they match a digest template in this registry
 */
final class DigestTemplateRegistry
{
    /**
     * @var static|null
     */
    private static ?DigestTemplateRegistry $instance = null;

    /**
     * @var \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate[]
     */
    private array $digestTemplates = [];

    /**
     * Return a singleton of the DigestsPool
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Clear the singleton of the DigestsPool
     *
     * @return void
     */
    public static function clearInstance(): void
    {
        self::$instance = null;
    }

    /**
     * If the template is already defined, do nothing
     * Digests are registered only on CLI, when sending emails with the command
     *
     * @param \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate $template Template to add to the pool
     * @return self
     */
    public function addTemplate(AbstractDigestTemplate $template): self
    {
        if (PHP_SAPI !== 'cli') {
            return $this;
        }

        $className = get_class($template);
        if ($this->hasTemplate($className)) {
            return $this;
        }
        $this->digestTemplates[$className] = $template;

        return $this;
    }

    /**
     * @param string $templateClassName template FQN
     * @return bool
     */
    public function hasTemplate(string $templateClassName): bool
    {
        return array_key_exists($templateClassName, $this->digestTemplates);
    }

    /**
     * @return \Passbolt\EmailDigest\Utility\Digest\AbstractDigestTemplate[]
     */
    public function getTemplates(): array
    {
        return $this->digestTemplates;
    }
}
