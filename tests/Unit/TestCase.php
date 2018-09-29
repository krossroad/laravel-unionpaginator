<?php

namespace Tests\Unit;

use ReflectionClass;

class TestCase extends \PHPUnit\Framework\TestCase {
    /**
     * Helper function to call protected method
     *
     * @param $object
     * @param string $methodName
     * @param array[] $args
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function callMethod($object, string $methodName, array $args = [])
    {
        $class = new ReflectionClass($object);
        $method = $class->getMethod($methodName);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}

