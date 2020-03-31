<?php

namespace Passbolt\Reports\Utility;

use App\Model\Entity\User;
use Cake\I18n\FrozenTime;
use JsonSerializable;
use const DATE_ATOM;

class Report implements JsonSerializable
{
    /**
     * @var FrozenTime
     */
    private $createdAt;

    /**
     * @var array
     */
    private $data;

    /**
     * @var User
     */
    private $createdBy;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string     $name Name of the report
     * @param string     $description Description of the report
     * @param string     $slug Identifier of the report
     * @param string     $template Template associated to this report
     * @param FrozenTime $createdAt Date where report was generated
     * @param User       $createdBy User who created the report
     * @param array      $data Data which compose the report
     */
    public function __construct(
        string $name,
        string $description,
        string $slug,
        string $template,
        FrozenTime $createdAt,
        User $createdBy,
        array $data
    ) {
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->data = $data;
        $this->slug = $slug;
        $this->template = $template;
        $this->description = $description;
        $this->createdBy = $createdBy;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->createdAt->format(DATE_ATOM),
            'created_by' => $this->createdBy,
            'slug' => $this->slug,
            'data' => $this->data,
        ];
    }

    /**
     * @param string $template Template to use for the render of the report
     * @return $this
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return the template associated to this report.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Return data associated to the report
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
