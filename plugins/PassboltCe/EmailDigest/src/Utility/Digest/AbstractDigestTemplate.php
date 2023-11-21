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

use App\Utility\Purifier;
use Cake\Log\Log;
use Passbolt\Locale\Service\LocaleService;

/**
 * Components interested into adding new digest through the DigestTemplateRegistry should extend this class.
 */
abstract class AbstractDigestTemplate
{
    public const DEFAULT_PRIORITY = 10;

    /**
     * Priority of the template over the others.
     * 1 is the highest priority
     * 10 the default
     *
     * @var int
     */
    protected int $priority;

    /**
     * Subject of the digest if the operator is the recipient
     *
     * @return string
     */
    abstract public function getDigestSubjectIfRecipientIsTheOperator(): string;

    /**
     * Subject of the digest if the operator is not the recipient
     *
     * @return string
     */
    abstract public function getDigestSubjectIfRecipientIsNotTheOperator(): string;

    /**
     * Templates of all the emails that will be gathered by the digest using this template
     *
     * @return array
     */
    abstract public function getSupportedTemplates(): array;

    /**
     * Variable name in the emails pointing to the operator.
     * E.g. user, or admin
     *
     * @return string
     */
    abstract public function getOperatorVariableKey(): string;

    /**
     * The name of the template to use when generating a
     * digest with general information, when the number of
     * emails in the digest is higher tan the threshold
     *
     * @return string
     */
    abstract public function getDigestTemplate(): string;

    /**
     * @param int $priority priority of this template
     */
    public function __construct(int $priority = self::DEFAULT_PRIORITY)
    {
        $this->priority = $priority;
    }

    /**
     * Defines the priority of a digest to be rendered over the others
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param \Passbolt\EmailDigest\Utility\Digest\Digest $digest digest
     * @return string
     */
    final public function getTranslatedSubject(Digest $digest): string
    {
        if ($digest->isRecipientTheOperator()) {
            $makeSubject = function (): string {
                return __($this->getDigestSubjectIfRecipientIsTheOperator());
            };
        } else {
            $operatorProfile = $digest->getOperator()->profile;
            $operatorFullName = Purifier::clean(
                $operatorProfile['first_name'] . ' ' . $operatorProfile['last_name']
            );
            $makeSubject = function () use ($operatorFullName): string {
                return __($this->getDigestSubjectIfRecipientIsNotTheOperator(), $operatorFullName);
            };
        }
        $emailLocale = $digest->getLocale();
        if ($emailLocale !== null) {
            $subject = (new LocaleService())->translateString($emailLocale, $makeSubject);
        } else {
            $subject = $makeSubject();
        }

        return $subject;
    }

    /**
     * @return string
     */
    protected function logErrorIfTheRecipientCannotBeTheOperator(): string
    {
        Log::error('The recipient cannot be the operator in this email digest template: ' . self::class);

        return $this->getDigestSubjectIfRecipientIsNotTheOperator();
    }
}
