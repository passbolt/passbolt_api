<?php
declare(strict_types=1);

namespace Passbolt\EmailDigest\Test\Factory;

use App\Model\Entity\Group;
use App\Model\Entity\User;
use App\Notification\Email\Redactor\Group\GroupUserDeleteEmailRedactor;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;

/**
 * GroupUserDeleteEmailQueueFactory
 */
class GroupUserDeleteEmailQueueFactory extends EmailQueueFactory
{
    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();
        $this
            ->setSubject()
            ->setTemplate(GroupUserDeleteEmailRedactor::TEMPLATE)
            ->setGroup()
            ->setOperator();
    }

    /**
     * @param User|null $user user who deleted the resource
     * @return self
     */
    public function setOperator(?User $user = null)
    {
        if (is_null($user)) {
            $user = UserFactory::make()->getEntity();
        }

        return $this->setField('template_vars.body.admin', $user->toArray());
    }

    /**
     * @param Group|null $group resource deleted
     * @return self
     */
    public function setGroup(?Group $group = null)
    {
        if (is_null($group)) {
            $group = GroupFactory::make()->getEntity();
        }

        return $this->setField('template_vars.body.group', $group->toArray());
    }
}
