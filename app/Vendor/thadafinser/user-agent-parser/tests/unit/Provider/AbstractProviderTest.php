<?php
namespace UserAgentParserTest\Unit\Provider;

/**
 * @covers UserAgentParser\Provider\AbstractProvider
 */
class AbstractProviderTest extends AbstractProviderTestCase
{
    public function testName()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $this->assertNull($provider->getName());

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('name');
        $property->setAccessible(true);
        $property->setValue($provider, 'MyName');

        $this->assertEquals('MyName', $provider->getName());
    }

    public function testHomepage()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $this->assertNull($provider->getHomepage());

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('homepage');
        $property->setAccessible(true);
        $property->setValue($provider, 'https://github.com/vendor/package');

        $this->assertEquals('https://github.com/vendor/package', $provider->getHomepage());
    }

    public function testPackageName()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $this->assertNull($provider->getPackageName());

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $this->assertEquals('vendor/package', $provider->getPackageName());
    }

    public function testVersionNull()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        // no package name
        $this->assertNull($provider->getVersion());

        // no composer.lock found
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $cwdir = getcwd();
        chdir('tests');

        $this->assertNull($provider->getVersion());
        chdir($cwdir);

        // locked file
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $fp = fopen('composer.lock', 'r');
        flock($fp, LOCK_EX);
        $this->assertNull($provider->getVersion());
        flock($fp, LOCK_UN);

        // no package match
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $this->assertNull($provider->getVersion());
    }

    public function testVersion()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'piwik/device-detector');

        // match
        $this->assertInternalType('string', $provider->getVersion());

        // cached
        $this->assertInternalType('string', $provider->getVersion());
    }

    public function testUpdateDateNull()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        // no package name
        $this->assertNull($provider->getUpdateDate());

        // no composer.lock found
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $cwdir = getcwd();
        chdir('tests');

        $this->assertNull($provider->getUpdateDate());
        chdir($cwdir);

        // locked file
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $fp = fopen('composer.lock', 'r');
        flock($fp, LOCK_EX);
        $this->assertNull($provider->getUpdateDate());
        flock($fp, LOCK_UN);

        // no package match
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'vendor/package');

        $this->assertNull($provider->getUpdateDate());
    }

    public function testUpdateDate()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $property   = $reflection->getProperty('packageName');
        $property->setAccessible(true);
        $property->setValue($provider, 'piwik/device-detector');

        // match
        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());

        // cached
        $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
    }

    public function testDetectionCapabilities()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $this->assertInternalType('array', $provider->getDetectionCapabilities());
        $this->assertCount(5, $provider->getDetectionCapabilities());
        $this->assertFalse($provider->getDetectionCapabilities()['browser']['name']);
    }

    public function testIsRealResult()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);
        $method     = $reflection->getMethod('isRealResult');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($provider, ''));
        $this->assertFalse($method->invoke($provider, null));

        $this->assertTrue($method->invoke($provider, 'some value'));
    }

    public function testIsRealResultWithDefaultValues()
    {
        $provider = $this->getMockForAbstractClass('UserAgentParser\Provider\AbstractProvider');

        $reflection = new \ReflectionClass($provider);

        $property = $reflection->getProperty('defaultValues');
        $property->setAccessible(true);
        $property->setValue($provider, [
            'general' => [
                '/^default value$/i',
            ],

            'bot' => [
                'name' => [
                    '/^default other$/i',
                ],
            ],
        ]);

        $method = $reflection->getMethod('isRealResult');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($provider, 'default value'));

        $this->assertTrue($method->invoke($provider, 'default other'));

        $this->assertFalse($method->invoke($provider, 'default other', 'bot', 'name'));
    }
}
