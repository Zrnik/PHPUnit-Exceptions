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

        if ($exceptionResults instanceof ClosureResult || $exceptionResults === null) {
            throw new AssertionFailedError(
                sprintf(
                    "Exception '%s' expected, none thrown!",
                    $expectedType
                )
            );
        }

        if ($exceptionResults instanceof ThrownResult && $exceptionResults->type !== $expectedType) {
            throw new AssertionFailedError(
                sprintf(
                    "Exception '%s' expected, '%s' thrown!\nMessage: %s",
                    $expectedType, $exceptionResults->type, $exceptionResults->throwable->getMessage()
                )
            );
        }
    }

    /**
     * @param Closure $closure
     * @return mixed
     * @see Exceptions::assertNoExceptionThrown for more information!
     */
    public static function assertNoExceptionThrown(
        Closure $closure
    )
    {
        $exceptionResult = self::getThrownExceptionResult($closure);

        if ($exceptionResult instanceof ThrownResult) {
            throw new AssertionFailedError(
                sprintf(
                    "No exception expected, but '%s' was thrown!\nMessage: %s",
                    $exceptionResult->type, $exceptionResult->throwable->getMessage()
                ), $exceptionResult->throwable->getCode(), $exceptionResult->throwable
            );
        }

        if($exceptionResult instanceof ClosureResult) {
            return $exceptionResult->value;
        }

        return null;
    }

    // TODO: assertExceptionCode, assertExceptionMessage

    /**
     * @param Closure $closure
     * @return ThrownResult|ClosureResult|null
     */
    private static function getThrownExceptionResult(Closure $closure)
    {
        try {
            $closureResult = new ClosureResult();
            $closureResult->value = $closure();

            if($closureResult->value === null) {
                // Hello PHPStan :) After I stop support for
                // PHP 7.4 I can remove this if group...
                return null;
            }

            return $closureResult;
        } catch (Throwable $t) {
            return ThrownResult::createFromThrowable($t);
        }
    }

}
