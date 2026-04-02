<?php
declare(strict_types=1);

namespace AndreasKiessling\ValidEmail\Result\Reason;

class LocalPartAsciiOnly implements \Egulias\EmailValidator\Result\Reason\Reason
{
    public function code(): int
    {
        return 1740956400; // Random timestamp-based unique integer
    }

    public function description(): string
    {
        return 'Local part of email address must only contain ASCII characters';
    }

}
