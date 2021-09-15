<?php declare(strict_types=1);

namespace Zrnik\PHPUnit;

use Throwable;

class ThrownResult
{
    public string $type;
    public string $message;
    public int $code;

    final private function __construct(
        string $type, string $message, int $code
    )
    {
        $this->type = $type;
        $this->message = $message;
        $this->code = $code;
    }

    public static function createFromThrowable(Throwable $throwable): ThrownResult
    {
        return new static(
            static::getExceptionType($throwable),
            $throwable->getMessage(),
            $throwable->getCode()
        );
    }

    /**
     * @param Throwable $throwable
     * @return string
     */
    private static function getExceptionType(Throwable $throwable): string
    {
        /**
         * As the type-check makes sure we only get 'Throwable' type,
         * we will never get 'false' from 'get_class' function.
         *
         * get_debug_type is better, but php 7.4 still supported.
         */
        return @get_class($throwable);
    }
}
