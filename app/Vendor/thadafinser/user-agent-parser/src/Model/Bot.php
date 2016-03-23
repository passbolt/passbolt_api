<?php
namespace UserAgentParser\Model;

class Bot
{
    /**
     * 
     * @var boolean
     */
    private $isBot;

    /**
     * 
     * @var string
     */
    private $name;

    /**
     * 
     * @var string
     */
    private $type;

    /**
     * 
     * @param boolean $mode
     */
    public function setIsBot($mode)
    {
        $this->isBot = $mode;
    }

    /**
     * 
     * @return boolean
     */
    public function getIsBot()
    {
        return $this->isBot;
    }

    /**
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'isBot' => $this->getIsBot(),
            'name'  => $this->getName(),
            'type'  => $this->getType(),
        ];
    }
}
