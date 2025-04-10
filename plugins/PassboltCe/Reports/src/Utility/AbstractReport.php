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
use Cake\I18n\DateTime;

abstract class AbstractReport implements ReportInterface
{
    /**
     * @var string|null $name report name
     */
    protected ?string $name = null;

    /**
     * @var string|null $slug report slug
     */
    protected ?string $slug = null;

    /**
     * @var string|null $description report description
     */
    protected ?string $description = null;

    /**
     * @var string $template report template
     */
    protected string $template;

    /**
     * @var \App\Model\Table\Dto\FindIndexOptions $options find options (optional)
     */
    protected FindIndexOptions $options;

    /**
     * @var \App\Model\Entity\User $creator
     */
    protected User $creator;

    /**
     * @inheritDoc
     */
    public function setSlug(string $slug): ReportInterface
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): ReportInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description): ReportInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTemplate(string $template): ReportInterface
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOptions(FindIndexOptions $options): ReportInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCreator(User $creator): mixed
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedOptions(): FindIndexOptions
    {
        return new FindIndexOptions();
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): FindIndexOptions
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        return $this->options ?? new FindIndexOptions();
    }

    /**
     * @return \App\Model\Entity\User|null
     */
    public function getCreator(): ?User
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        return $this->creator ?? null;
    }

    /**
     * @inheritDoc
     */
    public function createReport(): array
    {
        $report = [
            'slug' => $this->getSlug(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'created' => DateTime::now(),
            'data' => $this->getData(),
        ];

        // Creator is optional
        $creator = $this->getCreator();
        if (isset($creator)) {
            $report['creator'] = $creator;
        }

        return $report;
    }

    /**
     * Used by controller to render object
     *
     * @return mixed|array
     */
    public function jsonSerialize(): mixed
    {
        return $this->createReport();
    }

    /**
     * @inheritDoc
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @inheritDoc
     */
    abstract public function getTemplate(): string;

    /**
     * @inheritDoc
     */
    abstract public function getType(): string;

    /**
     * @inheritDoc
     */
    abstract public function getData(): array;
}
