<?php
declare(strict_types=1);

namespace App\Test\Lib\Utility\AuthToken;

use App\Utility\AuthToken\AuthTokenExpiryConfigValidator;
use Cake\TestSuite\TestCase;

class AuthTokenConfigValidatorTest extends TestCase
{
    /**
     * @var AuthTokenExpiryConfigValidator
     */
    private $sut;

    public function setUp(): void
    {
        $this->sut = new AuthTokenExpiryConfigValidator();
        parent::setUp();
    }

    /**
     * @dataProvider provideIncorrectValues
     */
    public function testAuthTokenConfigValidatorReturnNullIfValidationFailed($value)
    {
        $this->assertNull(call_user_func($this->sut, $value));
    }

    /**
     * @dataProvider provideCorrectValues
     */
    public function testAuthTokenConfigValidatorReturnTrueIfValidationFailed($value)
    {
        $this->assertSame($value, call_user_func($this->sut, $value));
    }

    /**
     * @dataProvider provideCorrectValues
     */
    public function testAuthTokenConfigValidatorReturnValueWhenUsedWithFilterVarWithCorrectValues($value)
    {
        $this->assertEquals($value, filter_var($value, FILTER_CALLBACK, ['options' => $this->sut]));
    }

    /**
     * @dataProvider provideIncorrectValues
     */
    public function testAuthTokenConfigValidatorReturnNullWhenUsedWithFilterVarWithIncorrectValues($value)
    {
        $this->assertNull(filter_var($value, FILTER_CALLBACK, ['options' => $this->sut]));
    }

    public function provideIncorrectValues()
    {
        return [
            [''],
            [' '],
            [null],
            [1],
            ['1'],
            ['heure'],
            ['1heure'],
            ['1heure'],
        ];
    }

    public function provideCorrectValues()
    {
        return [
            ['20 hours'],
            ['1 hour'],
            ['10 days'],
            ['1 day'],
            ['10 months'],
            ['1 month'],
            ['10 years'],
            ['1 year'],
            ['10 hours'],
            ['1 hour'],
            ['10 minutes'],
            ['1 minute'],
            ['10 seconds'],
            ['1 second'],
        ];
    }
}
