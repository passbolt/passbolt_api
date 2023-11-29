<?php
declare(strict_types=1);

namespace Passbolt\EmailDigest\Test\Factory;

use App\Model\Entity\Resource;
use App\Model\Entity\User;
use App\Notification\Email\Redactor\Resource\ResourceDeleteEmailRedactor;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;

/**
 * EmailQueueFactory
 *
 * @method \Cake\ORM\Entity|\Cake\ORM\Entity[] persist()
 * @method \Cake\ORM\Entity getEntity()
 * @method \Cake\ORM\Entity[] getEntities()
 */
class ResourceDeleteEmailQueueFactory extends EmailQueueFactory
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
            ->setTemplate(ResourceDeleteEmailRedactor::TEMPLATE)
            ->setResource()
            ->setOperator()
            ->setSubject($this->getFaker()->sentence())
            ->setField('template_vars.body.showUsername', true)
            ->setField('template_vars.body.showUri', true)
            ->setField('template_vars.body.showDescription', true);
    }

    /**
     * @param User|null $user user who deleted the resource
     * @return ResourceDeleteEmailQueueFactory
     */
    public function setOperator(?User $user = null)
    {
        if (is_null($user)) {
            $user = UserFactory::make()->getEntity();
        }

        return $this->setField('template_vars.body.user', $user->toArray());
    }

    /**
     * @param Resource|null $resource resource deleted
     * @return ResourceDeleteEmailQueueFactory
     */
    public function setResource(?Resource $resource = null)
    {
        if (is_null($resource)) {
            $resource = ResourceFactory::make()->getEntity();
        }

        return $this->setField('template_vars.body.resource', $resource->toArray());
    }
}
