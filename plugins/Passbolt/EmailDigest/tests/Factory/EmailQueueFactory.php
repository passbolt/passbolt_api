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
 * @method \Cake\Datasource\EntityInterface persist()
 * @method \Cake\Datasource\EntityInterface getEntity()
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
            $from_email = $faker->email();

            return [
                'email' => $email,
                'subject' => $faker->sentence(),
                'from_name' => $faker->name(),
                'from_email' => $from_email,
                'config' => 'default',
                'template' => 'test_email',
                'layout' => 'default',
                'template_vars' => json_encode(compact('email', 'from_email')),
                'theme' => '',
                'format' => 'html',
                'sent' => 0,
                'locked' => 0,
                'send_at' => Chronos::now()->subMinute(),
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
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
        return str_replace('/', DS, PLUGINS . 'Passbolt/EmailDigest/tests/Fixture/templates/');
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate(string $template)
    {
        return $this->patchData(compact($template));
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
}
