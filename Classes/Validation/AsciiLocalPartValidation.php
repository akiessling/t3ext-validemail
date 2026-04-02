<?php
declare(strict_types=1);

namespace AndreasKiessling\ValidEmail\Validation;

use Egulias\EmailValidator\Result\InvalidEmail;

class AsciiLocalPartValidation implements \Egulias\EmailValidator\Validation\EmailValidation
{
    /**
     * @var InvalidEmail|null
     */
    private $error = null;

    public function isValid(string $email, \Egulias\EmailValidator\EmailLexer $emailLexer): bool
    {
        $this->error = null;

        $atPosition = strrpos($email, '@');
        if ($atPosition === false) {
            // Let other validators handle this, or treat it as invalid.
            // Egulias parser usually handles basic structure, but we want to be safe.
            return true;
        }
        $localPart = substr($email, 0, $atPosition);

        // Amazon SES requires an ASCII-only local part because SMTPUTF8 is not supported.
        // see https://docs.aws.amazon.com/ses/latest/APIReference/API_SendEmail.html
        // other means of validation are not in the scope of this test
        if (preg_match('/[^\x00-\x7F]/', $localPart)) {
            $this->error = new InvalidEmail(new \AndreasKiessling\ValidEmail\Result\Reason\LocalPartAsciiOnly(), $localPart);
            return false;
        }

        return true;
    }

    /**
     * @return InvalidEmail|null
     */
    public function getError(): ?InvalidEmail
    {
        return $this->error;
    }

    public function getWarnings(): array
    {
        return [];
    }
}
