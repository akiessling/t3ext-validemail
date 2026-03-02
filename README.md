
# validemail - Enhanced Email TLD Validation for TYPO3 v11 to v13

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

## Testing (local Docker matrix)

This repository contains a local Docker-based test matrix to run the unit tests against multiple TYPO3 and PHP versions.

### Prerequisites

- Docker with `docker compose`
- GNU Make (optional, but convenient)

### Build / rebuild the PHP test runner images

Build all images (uses cache where possible):

```bash
make build
```

Force a clean rebuild (helpful after changing `docker/php-cli/Dockerfile`):

```bash
make rebuild
```

### Run the full test matrix

```bash
make test-matrix
```


Each matrix entry runs in its own sandbox under:

- `.Build/matrix/<profile>/`

This keeps your working tree clean (no `vendor/` or `composer.lock` changes in the repo root).

### Run only a single matrix entry (or a subset)

You can filter matrix entries by substring:

```bash
make test-matrix-filter FILTER=t14 
make test-matrix-filter FILTER=13.4:php84:t13
```


Tip: To see all available entries, open `scripts/test-matrix.sh` and look at the `MATRIX=(...)` list.

### Note about TYPO3 v11 and Composer security advisories

TYPO3 v11 is EOL and flagged by Packagist security advisories. For local matrix testing, the script disables Composer's "block insecure" mechanism inside the TYPO3 v11 sandbox only. This is done purely to keep legacy test coverage; it is not a recommendation for production usage.


This extension will hopefully be obsolete once https://github.com/egulias/EmailValidator/issues/359 is solved.
