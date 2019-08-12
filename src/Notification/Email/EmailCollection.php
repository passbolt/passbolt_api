<?php

namespace App\Notification\Email;

class EmailCollection
{
    /**
     * @var Email[]
     */
    private $emails = [];

    /**
     * @param Email[] $emails
     */
    public function __construct(array $emails = [])
    {
        foreach ($emails as $email) {
            $this->addEmail($email);
        }
    }

    /**
     * @param Email $email
     * @return $this
     */
    public function addEmail(Email $email)
    {
        $this->emails[] = $email;

        return $this;
    }

    /**
     * @return Email[]
     */
    public function getEmails()
    {
        return $this->emails;
    }
}
