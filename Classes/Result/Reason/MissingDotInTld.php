<?php
declare(strict_types=1);

namespace AndreasKiessling\ValidEmail\Result\Reason;

class MissingDotInTld implements \Egulias\EmailValidator\Result\Reason\Reason
{
    public function code(): int
    {
        return 1752957821;
    }

    public function description(): string
    {
        return 'Domain part must contain at least one dot';
    }

}
