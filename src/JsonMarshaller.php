<?php

declare(strict_types=1);

namespace Suin\Marshaller;

use Suin\Json\Decoder;

class JsonMarshaller
{
    /**
     * @var Protocol
     */
    private $protocol;

    /**
     * @var Decoder
     */
    private $decoder;

    /**
     * @param Protocol $protocol
     */
    public function __construct(Protocol $protocol)
    {
        $this->protocol = $protocol;
        $this->decoder = (new Decoder)->preferArray();
    }

    /**
     * @param array|object $value
     * @param int          $options
     * @return mixed
     */
    public function marshal($value, $options = 0)
    {
        return json_encode((new Marshaller($this->protocol))->marshall($value), $options);
    }

    /**
     * @param mixed  $data
     * @param string $typeExpression
     * @return array|object
     */
    public function unmarshal($data, string $typeExpression)
    {
        return (new ConstructorUnmarshaller($this->protocol))->unmarshal($this->decoder->decode($data), $typeExpression);
    }
}
