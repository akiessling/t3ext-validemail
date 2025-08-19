<?php

namespace AndreasKiessling\ValidEmail\Tests\Unit\Validation;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExtendedTldValidationTest extends \PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testMakeSureCoreValidationFails():void
    {
        if (isset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']['validemail'])) {
            unset($GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']['validemail']);
        }
        self::assertTrue(GeneralUtility::validEmail('foo@bar'));
    }

    public function testDomainPartWithEndingDot():void
    {
        self::assertFalse(GeneralUtility::validEmail('foo@bar.'));
    }

    public function testDomainPartWithoutDot():void
    {
        self::assertFalse(GeneralUtility::validEmail('foo@bar'));
    }

    public function testDomainWithOnlyDot():void
    {
        self::assertFalse(GeneralUtility::validEmail('foo@.'));
    }

    public function testDomainPartWithDot():void
    {
        self::assertTrue(GeneralUtility::validEmail('foo@bar.baz'));
    }



}
