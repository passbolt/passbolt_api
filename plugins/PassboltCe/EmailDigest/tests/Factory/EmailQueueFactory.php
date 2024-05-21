<?php
declare(strict_types=1);

namespace Passbolt\EmailDigest\Test\Factory;

use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * EmailQueueFactory
 *
 * @method \Cake\ORM\Entity|\Cake\ORM\Entity[] persist()
 * @method \Cake\ORM\Entity getEntity()
 * @method \Cake\ORM\Entity[] getEntities()
 */
class EmailQueueFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'EmailQueue.EmailQueue';
    }

    /**
     * @inheritDoc
     */
    protected function initialize(): void
    {
        $this->disablePrimaryKeyOffset();
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->configureEmailTemplateFixturePath();
        $this->configureSerializationType();

        $this->setDefaultData(function (Generator $faker) {
            $email = $faker->email();
            $title = $faker->sentence();
            $locale = 'en-UK';
            $fullBaseUrl = '/';
            $body = compact('fullBaseUrl');

            return [
                'email' => $email,
                'subject' => $faker->sentence(3),
                'config' => 'default',
                'template' => 'test_email',
                'layout' => 'default',
                'template_vars' => json_encode(compact('email', 'title', 'locale', 'fullBaseUrl', 'body')),
                'theme' => '',
                'format' => 'html',
                'sent' => 0,
                'locked' => 0,
                'send_tries' => 0,
                'send_at' => Chronos::now()->subMinutes(1),
                'created' => Chronos::now()->subDays($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDays($faker->randomNumber(4)),
            ];
        });
    }

    /**
     * Sets the path to test the email
     */
    public function configureEmailTemplateFixturePath(): void
    {
        $templatePaths = Configure::readOrFail('App.paths.templates');
        array_push($templatePaths, $this->getEmailDigestTestFixturePath());
        Configure::write('App.paths.templates', $templatePaths);
    }

    /**
     * Sets the serialization type to json by default
     */
    public function configureSerializationType()
    {
        if (!Configure::check('EmailQueue.serialization_type')) {
            Configure::write('EmailQueue.serialization_type', 'email_queue.json');
        }
    }

    public function getEmailDigestTestFixturePath(): string
    {
        return str_replace('/', DS, PLUGINS . 'PassboltCe/EmailDigest/tests/Fixture/templates/');
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate(string $template)
    {
        return $this->patchData(compact('template'));
    }

    /**
     * @param string $email Recipient
     * @return $this
     */
    public function setRecipient(string $email)
    {
        return $this->patchData(compact('email'));
    }

    /**
     * Activates the before save event. This will set the locale in
     * the view vars.
     *
     * @return $this
     */
    public function listeningToBeforeSave()
    {
        return $this->listeningToModelEvents('Model.beforeSave');
    }

    /**
     * Mark the email as sent.
     *
     * @return $this
     */
    public function sent()
    {
        return $this->setField('sent', true);
    }

    /**
     * @param string $fullBaseUrl full base url
     * @return EmailQueueFactory
     */
    public function setFullBaseUrl(string $fullBaseUrl)
    {
        return $this->setField('template_vars.body.fullBaseUrl', $fullBaseUrl);
    }

    /**
     * @param ?string $subject locale of the email
     * @return $this
     */
    public function setSubject(?string $subject = null)
    {
        $subject = $subject ?? $this->getFaker()->sentence(3);
        $this->setField('template_vars.body.subject', $subject);

        return $this->setField('subject', $subject);
    }

    /**
     * @param string $locale locale of the email
     * @return EmailQueueFactory
     */
    public function setLocale(string $locale)
    {
        return $this->setField('template_vars.locale', $locale);
    }

    /**
     * Sets the id of the email. Useful in unit testing, when an ID should be defined, but no persistence needed
     *
     * @param int|null $id email Id
     * @return $this
     */
    public function setId(?int $id = null)
    {
        return $this->setField('id', $id ?? $this->getFaker()->randomNumber(4));
    }
}
