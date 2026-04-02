<?php

namespace AndreasKiessling\ValidEmail\Tests\Unit\Validation;

use AndreasKiessling\ValidEmail\Validation\AsciiLocalPartValidation;
use Egulias\EmailValidator\EmailLexer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AsciiLocalPartValidationTest extends \PHPUnit\Framework\TestCase
{
    private array|null $mailValidatorsBackup = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailValidatorsBackup = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators'] ?? null;
    }

    protected function tearDown(): void
    {
        if ($this->mailValidatorsBackup === null) {
            unset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']);
        } else {
            $GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators'] = $this->mailValidatorsBackup;
        }

        parent::tearDown();
    }

    public function testMakeSureCoreValidationFails():void
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']['validemail_ascii'])) {
            unset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']['validemail_ascii']);
        }
        self::assertTrue(GeneralUtility::validEmail('fofü@example.com'));
    }

    /**
     * @test
     * @dataProvider localPartDataProvider
     */
    public function testIsValid(string $email, bool $expected): void
    {
        $validation = new AsciiLocalPartValidation();
        $this->assertEquals($expected, $validation->isValid($email, new EmailLexer()));
    }

    /**
     * @test
     * @dataProvider invalidAsciiControlCharactersDataProvider
     */
    public function testGeneralUtilityRejectsControlCharactersInLocalPart(string $email): void
    {
        self::assertFalse(GeneralUtility::validEmail($email));
    }

    public static function localPartDataProvider(): array
    {
        return [
            'standard ascii' => ['foo@example.com', true],
            'ascii with dots' => ['foo.bar@example.com', true],
            'ascii with numbers' => ['user123@example.com', true],
            'ascii with underscore' => ['foo_bar@example.com', true],
            'ascii with hyphen' => ['foo-bar@example.com', true],
            'ascii with plus tag' => ['foo+bar@example.com', true],
            'ascii with apostrophe' => ["o'hara@example.com", true],
            'umlaut in local part' => ['fofü@example.com', false],
            'emoji in local part' => ['👋@example.com', false],
            'cyrillic in local part' => ['тест@example.com', false],
            'umlaut in domain is allowed' => ['foo@exämple.com', true],
        ];
    }

    public static function invalidAsciiControlCharactersDataProvider(): array
    {
        return [
            'tab in local part' => ["foo\tbar@example.com"],
            'line feed in local part' => ["foo\nbar@example.com"],
            'carriage return in local part' => ["foo\rbar@example.com"],
        ];
    }
}
