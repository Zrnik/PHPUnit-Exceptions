<?php declare(strict_types=1);

namespace Zrnik\PHPUnit;

use Closure;
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

final class AssertException
{
    /**
     * @param string $expectedType
     * @param Closure $closure
     * @see Exceptions::assertExceptionThrown for more information!
     */
    public static function assertExceptionThrown(
        string $expectedType, Closure $closure
    ): void
    {
        $exceptionType = self::getThrownExceptionType($closure);

        if ($exceptionType === null)
            throw new AssertionFailedError(
                sprintf(
                    "Exception '%s' expected, none thrown!",
                    $expectedType
                )
            );

        if ($exceptionType !== $expectedType)
            throw new AssertionFailedError(
                sprintf(
                    "Exception '%s' expected, '%s' thrown!",
                    $expectedType, $exceptionType
                )
            );
    }

    /**
     * @param Closure $closure
     * @see Exceptions::assertNoExceptionThrown for more information!
     */
    public static function assertNoExceptionThrown(
        Closure $closure
    ): void
    {
        $exceptionType = self::getThrownExceptionType($closure);

        if ($exceptionType !== null)
            throw new AssertionFailedError(
                sprintf(
                    "No exception expected, but '%s' thrown!",
                    $exceptionType
                )
            );
    }

    // TODO: assertExceptionCode, assertExceptionMessage

    //region Exception Info Mining

    /**
     * @param Closure $closure
     * @return string|null
     */
    private static function getThrownExceptionType(Closure $closure): ?string
    {
        $exception = self::getThrownException($closure);

        if ($exception === null)
            return null;

        /**
         * @var string|false $class
         */
        $class = @get_class($exception);

        if ($class === false)
            return null;

        return $class;
    }

    /**
     * @param Closure $closure
     * @return string|null
     */
    private static function getThrownExceptionMessage(Closure $closure): ?string
    {
        $exception = self::getThrownException($closure);

        if ($exception === null)
            return null;

        return $exception->getMessage();
    }

    /**
     * @param Closure $closure
     * @return int|null
     */
    private static function getThrownExceptionCode(Closure $closure): ?int
    {
        $exception = self::getThrownException($closure);

        if ($exception === null)
            return null;

        return $exception->getCode();
    }

    /**
     * @param Closure $closure
     * @return Throwable|null
     */
    private static function getThrownException(Closure $closure): ?Throwable
    {
        try {
            $closure();
        } catch (Throwable $t) {
            return $t;
        }

        return null;
    }

    //endregion

}