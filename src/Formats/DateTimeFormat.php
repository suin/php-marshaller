<?php
declare(strict_types=1);

namespace Suin\Marshaller\Formats;

class DateTimeFormat
{
    public function read(string $dateTime): \DateTime
    {
        return new \DateTime($dateTime);
    }

    public function write(\DateTime $dateTime): string
    {
        return $dateTime->format(DATE_ISO8601);
    }
}
