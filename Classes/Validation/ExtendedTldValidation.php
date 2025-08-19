<?php
declare(strict_types=1);

namespace AndreasKiessling\ValidEmail\Validation;

use Egulias\EmailValidator\Result\InvalidEmail;

class ExtendedTldValidation implements \Egulias\EmailValidator\Validation\EmailValidation
{
    /**
     * @var InvalidEmail|null
     */
    private $error = null;

    public function isValid(string $email, \Egulias\EmailValidator\EmailLexer $emailLexer): bool
    {
        $this->error = null;

        // Extract domain part (everything after the @)
        $atPosition = strrpos($email, '@');
        $domainPart = substr($email, $atPosition + 1);

        // Check if domain part contains at least one dot
        if (strpos($domainPart, '.') === false) {
            $this->error = new InvalidEmail(new \AndreasKiessling\ValidEmail\Result\Reason\MissingDotInTld(), $domainPart);
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
