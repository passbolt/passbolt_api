<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ObfuscateFieldsComponent;
use App\Test\Factory\UserFactory;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\ObfuscateFieldsComponent Test Case
 */
class ObfuscateFieldsComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Controller\Component\ObfuscateFieldsComponent
     */
    protected $component;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->component);
        unset($this->controller);

        parent::tearDown();
    }

    /**
     * Setup controller and component
     *
     * @param ServerRequest|null $request Server Request
     * @return void
     */
    protected function setupController(?ServerRequest $request = null)
    {
        if (!$request) {
            $request = new ServerRequest();
        }
        $response = new Response();
        $this->controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->setConstructorArgs([$request, $response])
            ->onlyMethods([])
            ->getMock();
        $registry = new ComponentRegistry($this->controller);
        $this->component = new ObfuscateFieldsComponent($registry);
        $this->component->setConfig('fields', ['password']);
    }

    /**
     * Before Filter: Field is removed because placeholder
     * matches and the field is monitored via config
     *
     * @return void
     */
    public function testBeforeFilter_fieldRemovedWhenPlaceholderIsPresent(): void
    {
        $initialData = [
            'username' => 'test',
            'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
        ];
        $expected = [
            'username' => 'test',
        ];
        $request = new ServerRequest([
            'post' => $initialData,
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->setupController($request);

        $this->assertSame($initialData, $request->getData());

        $event = new Event('Controller.beforeFilter', $this->controller);
        $this->component->beforeFilter($event);

        $this->assertSame($expected, $this->controller->getRequest()->getData());
    }

    /**
     * Before Filter: Field is removed because placeholder
     * matches and the field is monitored via config (custom placeholder)
     *
     * @return void
     */
    public function testBeforeFilter_fieldRemovedWhenPlaceholderIsCustom(): void
    {
        Configure::write('passbolt.obfuscateFields.placeholder', 'CUSTOM_PLACEHOLDER');
        $initialData = [
            'username' => 'test',
            'password' => 'CUSTOM_PLACEHOLDER',
        ];
        $expected = [
            'username' => 'test',
        ];
        $request = new ServerRequest([
            'post' => $initialData,
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->setupController($request);

        $this->assertSame($initialData, $request->getData());

        $event = new Event('Controller.beforeFilter', $this->controller);
        $this->component->beforeFilter($event);

        $this->assertSame($expected, $this->controller->getRequest()->getData());
    }

    /**
     * Before Filter: Field is not removed because it
     * is not monitored via config
     *
     * @return void
     */
    public function testBeforeFilter_fieldNotRemovedBecauseIsNotMonitored(): void
    {
        $initialData = [
            'username' => 'test',
            'apiKey' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
        ];
        $request = new ServerRequest([
            'post' => $initialData,
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->setupController($request);

        $this->assertSame($initialData, $request->getData());

        $event = new Event('Controller.beforeFilter', $this->controller);
        $this->component->beforeFilter($event);

        $this->assertSame($initialData, $this->controller->getRequest()->getData());
    }

    /**
     * Before Filter: Field is not removed because the value
     * is different than placeholder
     *
     * @return void
     */
    public function testBeforeFilter_fieldNotRemovedBecauseDifferentThanPlaceholder(): void
    {
        $initialData = [
            'username' => 'test',
            'password' => 'DIFFERENT VALUE',
        ];
        $request = new ServerRequest([
            'post' => $initialData,
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->setupController($request);

        $this->assertSame($initialData, $request->getData());

        $event = new Event('Controller.beforeFilter', $this->controller);
        $this->component->beforeFilter($event);

        $this->assertSame($initialData, $this->controller->getRequest()->getData());
    }

    /**
     * Before Filter: Field is removed in multidimensional
     * arrays when value equals to placeholder
     *
     * @return void
     */
    public function testBeforeFilter_multidimensionalDataReplacement(): void
    {
        $initialData = [
            [
                'username' => 'test1',
                'password' => 'DIFFERENT VALUE',
            ],
            [
                'username' => 'test2',
                'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
            ],
            [
                'username' => 'test3',
                'password' => 'DIFFERENT VALUE',
            ],
            [
                'username' => 'test4',
                'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
            ],
        ];

        $expected = [
            [
                'username' => 'test1',
                'password' => 'DIFFERENT VALUE',
            ],
            [
                'username' => 'test2',
            ],
            [
                'username' => 'test3',
                'password' => 'DIFFERENT VALUE',
            ],
            [
                'username' => 'test4',
            ],
        ];
        $request = new ServerRequest([
            'post' => $initialData,
            'environment' => [
                'REQUEST_METHOD' => 'POST',
            ],
        ]);

        $this->setupController($request);

        $this->assertSame($initialData, $request->getData());

        $event = new Event('Controller.beforeFilter', $this->controller);
        $this->component->beforeFilter($event);

        $this->assertSame($expected, $this->controller->getRequest()->getData());
    }

    /**
     * Before Render: Field is obfuscated because it
     * is monitored via config
     *
     * @return void
     */
    public function testBeforeRender_fieldObfuscated(): void
    {
        $this->setupController();
        $body = [
            'username' => 'test',
            'password' => 'TOP_SECRET',
        ];
        $expected = [
            'username' => 'test',
            'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
        ];
        $this->controller->set('body', $body);
        $event = new Event('Controller.beforeRender', $this->controller);
        $this->component->beforeRender($event);

        $this->assertSame($expected, $this->controller->viewBuilder()->getVar('body'));
    }

    /**
     * Before Render: Field is obfuscated even in
     * multidimensional array body
     *
     * @return void
     */
    public function testBeforeRender_fieldObfuscatedIfMultidimensionalArray(): void
    {
        $this->setupController();
        $body = [
            [
                'user' => [
                    'username' => 'test',
                    'password' => 'TOP_SECRET1',
                ],
                'profile' => [],
            ],
            [
                'user' => [
                    'username' => 'test2',
                    'password' => 'TOP_SECRET2',
                ],
                'profile' => [],
            ],
            [
                'user' => [
                    'username' => 'test3',
                    'password' => 'TOP_SECRET3',
                ],
                'profile' => [],
            ],
            [
                'user' => [
                    'username' => 'test4',
                    'password' => 'TOP_SECRET4',
                ],
                'profile' => [],
            ],
        ];
        $expected = [
            [
                'user' => [
                    'username' => 'test',
                    'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
                ],
                'profile' => [],
            ],
            [
                'user' => [
                    'username' => 'test2',
                    'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
                ],
                'profile' => [],
            ],
            [
                'user' => [
                    'username' => 'test3',
                    'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
                ],
                'profile' => [],
            ],
            [
                'user' => [
                    'username' => 'test4',
                    'password' => ObfuscateFieldsComponent::FIELD_PLACEHOLDER,
                ],
                'profile' => [],
            ],
        ];
        $this->controller->set('body', $body);
        $event = new Event('Controller.beforeRender', $this->controller);
        $this->component->beforeRender($event);

        $this->assertSame($expected, $this->controller->viewBuilder()->getVar('body'));
    }

    /**
     * Before Render: Field is not obfuscated if it is
     * not monitored via config
     *
     * @return void
     */
    public function testBeforeRender_fieldNotObfuscatedBecauseIsNotMonitored(): void
    {
        $this->setupController();
        $body = [
            'username' => 'test',
            'apiKey' => 'TOP_SECRET',
        ];
        $expected = [
            'username' => 'test',
            'apiKey' => 'TOP_SECRET',
        ];
        $this->controller->set('body', $body);
        $event = new Event('Controller.beforeRender', $this->controller);
        $this->component->beforeRender($event);

        $this->assertSame($expected, $this->controller->viewBuilder()->getVar('body'));
    }

    /**
     * Before Render: Field is obfuscated on every entity
     * when body is a query that includes multiple entities
     *
     * @return void
     */
    public function testBeforeRender_obfuscateEntityField(): void
    {
        UserFactory::make(10)->persist();
        $this->setupController();
        $body = TableRegistry::getTableLocator()->get('Users')->find();
        $this->controller->set('body', $body);
        $event = new Event('Controller.beforeRender', $this->controller);
        $this->component->setConfig('fields', ['role_id']);
        $this->component->beforeRender($event);

        $users = $this->controller->viewBuilder()->getVar('body');
        foreach ($users as $user) {
            $this->assertSame(ObfuscateFieldsComponent::FIELD_PLACEHOLDER, $user['role_id']);
        }
    }
}
