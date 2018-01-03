<?php

declare(strict_types=1);

namespace Suin\Marshaller;

class ConstructorUnmarshaller
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
     * @param mixed  $data
     * @param string $typeExpression
     * @throws \TypeError
     * @return array|object
     */
    public function unmarshal($data, string $typeExpression)
    {
        return $this->unmarshalData($data, $typeExpression);
    }

    /**
     * @param mixed  $data
     * @param string $typeExpression
     * @return array|object
     */
    private function unmarshalData($data, string $typeExpression)
    {
        if (self::isCollection($typeExpression)) {
            $typeName = self::memberTypeOfCollection($typeExpression);
            return self::map($data, function ($value) use ($typeName) {
                return $this->unmarshalData($value, $typeName);
            });
        }

        if (
            is_array($data) === false &&
            $this->protocol->has($typeExpression)
        ) {
            return $this->protocol->read($data, $typeExpression);
        }

        $constructor = (new \ReflectionClass($typeExpression))->getConstructor();
        assert($constructor !== null);
        assert($constructor->isPublic());
        $parameters = $constructor->getParameters();
        $arguments = self::map($parameters, function (\ReflectionParameter $parameter) use ($data) {
            $parameterName = $parameter->getName();

            if (($type = $parameter->getType()) && $type->isBuiltin() === false) {
                $typeName = $type->getName();

                if ($this->protocol->has($typeName)) {
                    return $this->protocol->read($data[$parameterName] ?? null, $typeName);
                }
                return $this->unmarshalData($data[$parameterName] ?? [], $type->getName());
            }
            return $data[$parameterName] ?? null;
        });
        return new $typeExpression(...$arguments);
    }

    /**
     * Determine if the type expression represents a collection of the type.
     *
     * @param string $typeExpression
     * @return bool
     */
    private static function isCollection(string $typeExpression): bool
    {
        return substr($typeExpression, -2) === '[]';
    }

    /**
     * @param string $typeExpression
     * @return string
     */
    private static function memberTypeOfCollection(string $typeExpression): string
    {
        return substr($typeExpression, 0, -2);
    }

    /**
     * @param array    $v
     * @param callable $f
     * @return array
     */
    private static function map(array $v, callable $f)
    {
        return array_map($f, $v);
    }
}
