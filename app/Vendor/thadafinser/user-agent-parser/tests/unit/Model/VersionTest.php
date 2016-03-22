<?php
namespace UserAgentParserTest;

use PHPUnit_Framework_TestCase;
use UserAgentParser\Model\Version;

/**
 * @covers UserAgentParser\Model\Version
 */
class VersionTest extends PHPUnit_Framework_TestCase
{
    public function testMajorMinorPatch()
    {
        $version = new Version();

        $this->assertNull($version->getMajor());
        $this->assertNull($version->getMinor());
        $this->assertNull($version->getPatch());
        $this->assertNull($version->getAlias());

        $version->setMajor(2);
        $this->assertEquals(2, $version->getMajor());

        $version->setMinor(3);
        $this->assertEquals(3, $version->getMinor());

        $version->setPatch(4);
        $this->assertEquals(4, $version->getPatch());

        $version->setAlias('Windows XP');
        $this->assertEquals('Windows XP', $version->getAlias());
    }

    public function testCompleteSimple()
    {
        $version = new Version();

        $this->assertNull($version->getComplete());

        // null stays null
        $version->setComplete(null);
        $this->assertNull($version->getComplete());

        $version->setComplete('2.0.1');
        $this->assertEquals('2.0.1', $version->getComplete());
        $this->assertEquals(2, $version->getMajor());
        $this->assertEquals(0, $version->getMinor());
        $this->assertEquals(1, $version->getPatch());

        $version->setComplete('2.0');
        $this->assertEquals('2.0', $version->getComplete());
        $this->assertEquals(2, $version->getMajor());
        $this->assertEquals(0, $version->getMinor());
        $this->assertEquals(null, $version->getPatch());

        $version->setMajor(3);
        $this->assertEquals('3.0', $version->getComplete());
    }

    public function testCompleteFilterZero()
    {
        $version = new Version();

        // 0.0 gets filtered
        $version->setComplete('0.0');
        $this->assertNull($version->getComplete());
    }

    public function testCompleteOnlyAlias()
    {
        $version = new Version();

        $version->setComplete('Windows XP');

        $this->assertEquals('Windows XP', $version->getComplete());
        $this->assertNull($version->getMajor());
        $this->assertNull($version->getMinor());
        $this->assertNull($version->getPatch());
        $this->assertEquals('Windows XP', $version->getAlias());
    }

    public function testCompleteVersionAndAlias()
    {
        $version = new Version();

        $version->setComplete('Windows XP 6.3');

        $this->assertEquals('Windows XP 6.3', $version->getComplete());
        $this->assertEquals(6, $version->getMajor());
        $this->assertEquals(3, $version->getMinor());
        $this->assertNull($version->getPatch());
        $this->assertEquals('Windows XP', $version->getAlias());
    }

    public function testCompleteWithNotAllowedBeta()
    {
        $version = new Version();

        $version->setComplete('5.6.3b');

        $this->assertEquals('5.6.3b', $version->getComplete());
        $this->assertEquals(5, $version->getMajor());
        $this->assertEquals(6, $version->getMinor());
        $this->assertEquals(3, $version->getPatch());
        $this->assertNull($version->getAlias());
    }

    public function testCompleteWithNotAllowedAlpha()
    {
        $version = new Version();

        $version->setComplete('5.6.3alpha');

        $this->assertEquals('5.6.3alpha', $version->getComplete());
        $this->assertEquals(5, $version->getMajor());
        $this->assertEquals(6, $version->getMinor());
        $this->assertEquals(3, $version->getPatch());
        $this->assertNull($version->getAlias());
    }

    public function testToArray()
    {
        $version = new Version();

        $this->assertEquals([
            'major' => null,
            'minor' => null,
            'patch' => null,

            'alias' => null,

            'complete' => null,
        ], $version->toArray());

        $version->setComplete('XP 3.1.5');
        $this->assertEquals([
            'major' => 3,
            'minor' => 1,
            'patch' => 5,

            'alias' => 'XP',

            'complete' => 'XP 3.1.5',
        ], $version->toArray());
    }
}
