
# validemail - Enhanced Email TLD Validation for TYPO3 v12 to v14

The `validemail` extension extends TYPO3's standard email validation with an improved TLD validation. It adds a check for the domain, so that at least one dot is present after the @-sign.

## How to test in your setup
When doing a manual test, the browser validation will prevent you from submitting the form, if the email address is invalid.

Bots just send the form data to the action URL of the form tag, and then the \TYPO3\CMS\Core\Utility\GeneralUtility::validEmail method is usually used to validate the email address. This method uses egulias/email-validator which does not check if the domain contains a dot.

## Warning:
Do not install this package if you send emails to local addresses or in an intranet environment that do not have a dot in the domain part.


## Test on your own
If you want to test this behavior yourself, deactivate JavaScript in your browser, which will also disable the browser validation. Then submit your form with something like "test@example" - this will be accepted by the validEmail check without this extension.

See these issues for additional information:
* https://forge.typo3.org/issues/102629
* https://github.com/egulias/EmailValidator/issues/359

## Installation

### Via Composer (recommended)

```bash
composer require andreaskiessling/validemail
```

### Testing
Running the unit tests with https://github.com/nektos/act and g1a/composer-test-scenarios locally:

```bash
act --matrix php-version:8.1 --action-offline-mode
act --matrix php-version:8.2 --action-offline-mode
act --matrix php-version:8.3 --action-offline-mode
act --matrix php-version:8.4 --action-offline-mode
```

This extension will hopefully be obsolete once https://github.com/egulias/EmailValidator/issues/359 is solved.
