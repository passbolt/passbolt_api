<?php
declare(strict_types=1);

namespace Passbolt\BrowserAuth\Test\TestCase\Service;

use InvalidArgumentException;
use Passbolt\BrowserAuth\Service\BrowserGrantBinding;
use PHPUnit\Framework\TestCase;

class BrowserGrantBindingTest extends TestCase
{
    public function testVerifierMustMatchChallenge(): void
    {
        $verifier = str_repeat('a', 43);
        $challenge = rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
        $this->assertTrue(BrowserGrantBinding::matches($challenge, $verifier));
        $this->assertFalse(BrowserGrantBinding::matches($challenge, str_repeat('b', 43)));
        $this->assertFalse(BrowserGrantBinding::matches($challenge, 'short'));
    }

    public function testRequestRejectsMalformedAndOversizedValues(): void
    {
        $this->expectException(InvalidArgumentException::class);
        BrowserGrantBinding::validate(['challenge' => str_repeat('a', 10000)]);
    }
}
