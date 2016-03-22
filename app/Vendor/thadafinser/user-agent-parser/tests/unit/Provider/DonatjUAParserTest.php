<?php
namespace UserAgentParser\Provider
{

    use UserAgentParserTest\Unit\Provider\DonatjUAParserTest;

    /**
     * This is need to mock the testing!
     *
     * @param  string $userAgent
     * @return array
     */
    function parse_user_agent($userAgent)
    {
        return [
            'browser' => DonatjUAParserTest::$browser,
            'version' => DonatjUAParserTest::$version,
        ];
    }
}

namespace UserAgentParserTest\Unit\Provider
{

    use UserAgentParser\Provider\DonatjUAParser;

    /**
     * @covers UserAgentParser\Provider\DonatjUAParser
     */
    class DonatjUAParserTest extends AbstractProviderTestCase
    {
        public static $browser = null;

        public static $version = null;

        public function testPackageNotLoadedException()
        {
            $file     = 'vendor/donatj/phpuseragentparser/composer.json';
            $tempFile = 'vendor/donatj/phpuseragentparser/composer.json.tmp';

            rename($file, $tempFile);

            try {
                $provider = new DonatjUAParser();
            } catch (\Exception $ex) {
                // we need to catch the exception, since we need to rename the file again!
            }

            $this->assertInstanceOf('UserAgentParser\Exception\PackageNotLoadedException', $ex);

            rename($tempFile, $file);
        }

        public function testName()
        {
            $provider = new DonatjUAParser();

            $this->assertEquals('DonatjUAParser', $provider->getName());
        }

        public function testGetHomepage()
        {
            $provider = new DonatjUAParser();

            $this->assertEquals('https://github.com/donatj/PhpUserAgent', $provider->getHomepage());
        }

        public function testGetPackageName()
        {
            $provider = new DonatjUAParser();

            $this->assertEquals('donatj/phpuseragentparser', $provider->getPackageName());
        }

        public function testVersion()
        {
            $provider = new DonatjUAParser();

            $this->assertInternalType('string', $provider->getVersion());
        }

        public function testUpdateDate()
        {
            $provider = new DonatjUAParser();

            $this->assertInstanceOf('DateTime', $provider->getUpdateDate());
        }

        public function testDetectionCapabilities()
        {
            $provider = new DonatjUAParser();

            $this->assertEquals([

                'browser' => [
                    'name'    => true,
                    'version' => true,
                ],

                'renderingEngine' => [
                    'name'    => false,
                    'version' => false,
                ],

                'operatingSystem' => [
                    'name'    => false,
                    'version' => false,
                ],

                'device' => [
                    'model'    => false,
                    'brand'    => false,
                    'type'     => false,
                    'isMobile' => false,
                    'isTouch'  => false,
                ],

                'bot' => [
                    'isBot' => false,
                    'name'  => false,
                    'type'  => false,
                ],
            ], $provider->getDetectionCapabilities());
        }

        /**
         * @expectedException \UserAgentParser\Exception\NoResultFoundException
         */
        public function testNoResultFoundException()
        {
            self::$browser = null;
            self::$version = null;

            $provider = new DonatjUAParser();

            $result = $provider->parse('A real user agent...');
        }

        /**
         * Browser only
         */
        public function testParseBrowser()
        {
            self::$browser = 'Firefox';
            self::$version = '3.0.1';

            $provider = new DonatjUAParser();

            $result = $provider->parse('A real user agent...');

            // reset
            self::$browser = null;
            self::$version = null;

            $expectedResult = [
                'browser' => [
                    'name'    => 'Firefox',
                    'version' => [
                        'major' => 3,
                        'minor' => 0,
                        'patch' => 1,

                        'alias' => null,

                        'complete' => '3.0.1',
                    ],
                ],
            ];

            $this->assertProviderResult($result, $expectedResult);
        }
    }
}
