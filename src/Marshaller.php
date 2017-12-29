<?php
declare(strict_types=1);

namespace Suin\Marshaller;


class Marshaller
{
    /**
     * @var Protocol
     */
    private $protocol;

    /**
     * @param Protocol $protocol
     */
    public function __construct(Protocol $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @param array|object $value
     * @return mixed
     */
    public function marshall($value)
    {
        assert(is_array($value) || is_object($value));
        return is_object($value) ?
            $this->marshallObject($value) :
            $this->marshallCollection($value);
    }

    private function marshallObject($object)
    {
        assert(is_object($object));
        $objectInfo = new \ReflectionObject($object);
        $className = $objectInfo->getName();
        if ($this->protocol->has($className)) {
            return $this->protocol->write($object, $className);
        } else {
            $data = [];
            $properties = $objectInfo->getProperties();
            foreach ($properties as $property) {
                if ($property->isDefault() && !$property->isStatic()) {
                    $property->setAccessible(true);
                    $value = $property->getValue($object);
                    $value = (is_object($value) || is_array($value)) ? $this->marshall($value) : $value;
                    if ($value !== null) {
                        $data[$property->getName()] = $value;
                    }
                }
            }
            return $data;
        }
    }

    private function marshallCollection(array $objects): array
    {
        return array_map([$this, 'marshall'], $objects);
    }
}
