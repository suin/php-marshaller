<?php
declare(strict_types=1);

namespace Suin\Marshaller;

use Suin\Marshaller\Formats\DateTimeFormat;

class StandardProtocol extends Protocol
{
    /**
     * @param object[] $formats
     */
    public function __construct(... $formats)
    {
        parent::__construct(
            new DateTimeFormat,
            ...$formats
        );
    }
}
