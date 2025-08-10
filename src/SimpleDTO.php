<?php

namespace Lokus\Dto;

use LogicException;
use ReflectionClass;

readonly abstract class SimpleDTO
{
    /**
     * Creates object from array.
     * Validates passed array with names of parameters in __construct and types
     * 
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public static function fromArray(array $data): static
    {
        $keys = array_keys($data);

        $reflector = new \ReflectionClass(static::class);
        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            if ($property->isProtected() || $property->isPrivate()) {
                throw new LogicException('Properties of DTO cannot be protected or private');
            }
        }

        $constructor = $reflector->getConstructor();

        $parameters = $constructor->getParameters();

        $parametersNames = array_map(fn (\ReflectionParameter $param) => $param->name, $parameters);
        
        $isNamesOk = count(array_diff($keys, $parametersNames)) === 0;

        if (! $isNamesOk) {
            throw new \InvalidArgumentException('Keys and parameters names are different');
        }

        $mapValuesParams = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            /** @var \ReflectionNamedType */
            $desiredType = $parameter->getType();
            $desiredTypeName = $desiredType->getName();

            $incomingType = get_debug_type($data[$name]);

            $mapValuesParams[$name] = $data[$name];

            if ($desiredTypeName !== $incomingType) {
                $message = "Type mismatch. Desired: \"{$desiredTypeName}\", given: \"{$incomingType}\"";
                throw new \UnexpectedValueException($message);
            }
        }

        $result = $reflector->newInstanceWithoutConstructor();
        $reflectorResult = new ReflectionClass($result::class);

        foreach ($reflectorResult->getProperties() as $property) {
            $value = $mapValuesParams[$property->getName()];
            $property->setValue($result, $value);
        }

        return $result;
    }
}