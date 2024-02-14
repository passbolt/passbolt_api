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
use Cake\I18n\FrozenTime;

abstract class AbstractReport implements ReportInterface
{
    /**
     * @var string $name report name
     */
    protected $name;

    /**
     * @var string $slug report slug
     */
    protected $slug;

    /**
     * @var string $description report description
     */
    protected $description;

    /**
     * @var string $template report template
     */
    protected $template;

    /**
     * @var \App\Model\Table\Dto\FindIndexOptions $options find options (optional)
     */
    protected $options;

    /**
     * @var \App\Model\Entity\User $creator
     */
    protected $creator;

    /**
     * @inheritDoc
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOptions(FindIndexOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedOptions()
    {
        return new FindIndexOptions();
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getOptions()
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        return $this->options ?? new FindIndexOptions();
    }

    /**
     * @return \App\Model\Entity\User|null
     */
    public function getCreator()
    {
        /** @psalm-suppress RedundantPropertyInitializationCheck */
        return $this->creator ?? null;
    }

    /**
     * @inheritDoc
     */
    public function createReport()
    {
        $report = [
            'slug' => $this->getSlug(),
            'type' => $this->getType(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'created' => FrozenTime::now(),
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
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->createReport();
    }

    /**
     * @inheritDoc
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @inheritDoc
     */
    abstract public function getTemplate();

    /**
     * @inheritDoc
     */
    abstract public function getType();

    /**
     * @inheritDoc
     */
    abstract public function getData();
}
