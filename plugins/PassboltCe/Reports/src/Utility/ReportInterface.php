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
namespace Passbolt\Reports\Utility;

use App\Model\Entity\User;
use App\Model\Table\Dto\FindIndexOptions;

interface ReportInterface
{
    /**
     * @param string $name report name
     * @return \Passbolt\Reports\Utility\ReportInterface $this
     */
    public function setName(string $name): ReportInterface;

    /**
     * Set the report slug
     *
     * @param string $slug report slug
     * @return \Passbolt\Reports\Utility\ReportInterface $this
     */
    public function setSlug(string $slug): ReportInterface;

    /**
     * Set the report description
     *
     * @param string $description report description
     * @return \Passbolt\Reports\Utility\ReportInterface $this
     */
    public function setDescription(string $description): ReportInterface;

    /**
     * Set the report template
     *
     * @param string $template report template (for html rendering)
     * @return \Passbolt\Reports\Utility\ReportInterface $this
     */
    public function setTemplate(string $template): ReportInterface;

    /**
     * Set options to be used to get the data if needed
     *
     * @param \App\Model\Table\Dto\FindIndexOptions $options options to be used in getData
     * @return \Passbolt\Reports\Utility\ReportInterface $this
     */
    public function setOptions(FindIndexOptions $options): ReportInterface;

    /**
     * Set user to be used as 'created by'
     *
     * @param \App\Model\Entity\User $creator creator
     * @return mixed
     */
    public function setCreator(User $creator): mixed;

    /**
     * Return the name of the report
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Return a brief description of the generated report.
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Return the template associated to the generated report by the report generator.
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Return the report type
     *
     * @return string the report type
     */
    public function getType(): string;

    /**
     * Return the report slug
     *
     * @return string|null the report slug
     */
    public function getSlug(): ?string;

    /**
     * Return a list of filters, order, contain available for the given report service.
     * Each report service has a different set of options since filters are bound to the data
     * generated in the report and each report uses different data.
     *
     * @return \App\Model\Table\Dto\FindIndexOptions
     */
    public function getSupportedOptions(): FindIndexOptions;

    /**
     * Return options to be used when fetching data
     *
     * @return \App\Model\Table\Dto\FindIndexOptions
     */
    public function getOptions(): FindIndexOptions;

    /**
     * Return the creator if any
     *
     * @return \App\Model\Entity\User|null
     */
    public function getCreator(): ?User;

    /**
     * Generate and return a report object from the provided data.
     *
     * @throws \InvalidArgumentException if the parameters provided in data are not supported.
     * @return array
     */
    public function createReport(): array;

    /**
     * Generate and return a report object from the provided data.
     *
     * @throws \InvalidArgumentException if the parameters provided in data are not supported.
     * @return array
     */
    public function getData(): array;
}
