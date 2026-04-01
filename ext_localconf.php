<?php

$GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']['validemail'] = \AndreasKiessling\ValidEmail\Validation\ExtendedTldValidation::class;
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['validators']['validemail_ascii'] = \AndreasKiessling\ValidEmail\Validation\AsciiLocalPartValidation::class;
