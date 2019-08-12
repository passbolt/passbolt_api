<?php

namespace App\Notification\Email;

/**
 * Immutable email
 * Any modifications made after its creation must return a new instance.
 */
class Email
{
    /**
     * @var string
     */
    private $to;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $template;

    /**
     * @param string $to
     * @param string $subject
     * @param array $data
     * @param string $template
     */
    public function __construct(string $to, string $subject, array $data, string $template)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param array $data
     * @return Email
     */
    public function withData(array $data)
    {
        $new = clone $this;
        $this->data = $data;

        return $new;
    }
}
