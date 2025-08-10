<?php

namespace Lokus\Dto;

readonly abstract class SimpleDTO
{
    /**
     * Creates object from array.
     * Validates passed array with names of parameters in __construct and keys inside array
     * 
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     */
    public static function fromArray(array $data): static
    {
        $keys = array_keys($data);

        $reflector = new \ReflectionClass(static::class);
        $constructor = $reflector->getConstructor();

        $parameters = $constructor->getParameters();

        $parametersNames = array_map(fn (\ReflectionParameter $param) => $param->name, $parameters);
        
        $isNamesOk = count(array_diff($keys, $parametersNames)) === 0;

        if (! $isNamesOk) {
            throw new \InvalidArgumentException('Keys and parameters names are different');
        }

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();

            /** @var \ReflectionNamedType */
            $desiredType = $parameter->getType();
            $desiredTypeName = $desiredType->getName();

            $incomingType = get_debug_type($data[$name]);

            if ($desiredTypeName !== $incomingType) {
                $message = "Type mismatch. Desired: \"{$desiredTypeName}\", given: \"{$incomingType}\"";
                throw new \UnexpectedValueException($message);
            }
        }

        /** @phpstan-ignore new.static */
        $result = new static(...$data);

        return $result;
    }
}