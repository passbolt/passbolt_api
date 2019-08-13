<?php

namespace App\Notification\Email;

use Exception;

class EmailSenderException extends Exception
{
    /**
     * @var Email
     */
    private $email;
    /**
     * @var array
     */
    private $options;

    /**.
     * @param Email $email
     * @param array $options
     */
    public function __construct(Email $email, array $options)
    {
        parent::__construct('Failed to send email');
        $this->email = $email;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
