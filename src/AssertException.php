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
        $exceptionResults = self::getThrownExceptionResult($closure);

        if ($exceptionResults === null) {
            throw new AssertionFailedError(
                sprintf(
                    "Exception '%s' expected, none thrown!",
                    $expectedType
                )
            );
        }

        if ($exceptionResults->type !== $expectedType) {
            throw new AssertionFailedError(
                sprintf(
                    "Exception '%s' expected, '%s' thrown!\nMessage: %s",
                    $expectedType, $exceptionResults->type, $exceptionResults->message
                )
            );
        }
    }

    /**
     * @param Closure $closure
     * @see Exceptions::assertNoExceptionThrown for more information!
     */
    public static function assertNoExceptionThrown(
        Closure $closure
    ): void
    {
        $exceptionResult = self::getThrownExceptionResult($closure);

        if ($exceptionResult !== null) {
            throw new AssertionFailedError(
                sprintf(
                    "No exception expected, but '%s' was thrown!\nMessage: %s",
                    $exceptionResult->type, $exceptionResult->message
                )
            );
        }
    }

    // TODO: assertExceptionCode, assertExceptionMessage

    /**
     * @param Closure $closure
     * @return ThrownResult|null
     */
    private static function getThrownExceptionResult(Closure $closure): ?ThrownResult
    {
        try {
            $closure();
        } catch (Throwable $t) {
            return ThrownResult::createFromThrowable($t);
        }

        return null;
    }

}
