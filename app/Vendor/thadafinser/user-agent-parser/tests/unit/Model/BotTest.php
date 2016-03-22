<?php
namespace UserAgentParserTest;

use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\Bot;

/**
 * @covers UserAgentParser\Model\Bot
 */
class BotTest extends PHPUnit_Framework_TestCase
{
    public function testIsBot()
    {
        $bot = new Bot();

        $this->assertNull($bot->getIsBot());

        $bot->setIsBot(true);
        $this->assertTrue($bot->getIsBot());

        $bot->setIsBot(false);
        $this->assertFalse($bot->getIsBot());
    }

    public function testName()
    {
        $bot = new Bot();

        $this->assertNull($bot->getName());

        $name = 'my bot name';
        $bot->setName($name);
        $this->assertEquals($name, $bot->getName());
    }

    public function testType()
    {
        $bot = new Bot();

        $this->assertNull($bot->getType());

        $type = 'crawler';
        $bot->setType($type);
        $this->assertEquals($type, $bot->getType());
    }

    public function testToArray()
    {
        $bot = new Bot();

        $this->assertEquals([
            'isBot' => null,
            'name'  => null,
            'type'  => null,
        ], $bot->toArray());

        $bot->setIsBot(true);
        $bot->setName('my bot name2');
        $bot->setType('backlink');

        $this->assertEquals([
            'isBot' => true,
            'name'  => 'my bot name2',
            'type'  => 'backlink',
        ], $bot->toArray());
    }
}
