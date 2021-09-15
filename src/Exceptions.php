<?php

namespace Zrnik\PHPUnit;

use Closure;

trait Exceptions
{
    // PHPUnit Method
    abstract protected function addToAssertionCount(int $howMuch);

    /**
     * This method will run the closure and
     * checks if the exception is instance
     * of the required type. If no exception
     * thrown, it fails too.
     *
     * @param string $expectedType
     * @param Closure $closure
     */
    public function assertExceptionThrown(
        string $expectedType,
        Closure $closure
    ): void
    {
        AssertException::assertExceptionThrown(
            $expectedType, $closure
        );

        $this->addToAssertionCount(1);
    }

    /**
     * This method will run the closure and
     * will fail the test, it ANY exception
     * was thrown.
     *
     * @param Closure $closure
     */
    public function assertNoExceptionThrown(
        Closure $closure
    ): void
    {
        AssertException::assertNoExceptionThrown(
            $closure
        );

        $this->addToAssertionCount(1);
    }

}
