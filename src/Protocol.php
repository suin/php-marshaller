<?php

declare(strict_types=1);

namespace Suin\Marshaller;

class Protocol
{
    /**
     * @var array
     */
    private $formats = [];

    /**
     * @param object[] $formats
     */
    public function __construct(...$formats)
    {
        array_walk($formats, [$this, 'add']);
    }

    /**
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool
    {
        return array_key_exists($type, $this->formats);
    }

    /**
     * @param mixed  $value
     * @param string $type
     * @return object
     */
    public function read($value, string $type)
    {
        return $this->formats[$type]->read($value);
    }

    /**
     * @param object $value
     * @param string $type
     * @return mixed
     */
    public function write($value, string $type)
    {
        return $this->formats[$type]->write($value);
    }

    /**
     * Add a format to this protocol.
     *
     * @param object $format
     * @return self
     */
    private function add($format): self
    {
        assert(is_object($format));
        assert(method_exists($format, 'read'));
        assert(method_exists($format, 'write'));
        $read = new \ReflectionMethod($format, 'read');
        $type = $read->getReturnType();
        assert($type instanceof \ReflectionType);
        $typeName = $type->getName();
        assert(!array_key_exists($typeName, $this->formats));
        $this->formats[$typeName] = $format;
        return $this;
    }
}
