<?php declare(strict_types=1);

namespace Zrnik\PHPUnit;

use Throwable;

class ThrownResult
{
    public string $type;
    public Throwable $throwable;

    final private function __construct(
        Throwable $throwable
    )
    {
        $this->type =  static::getExceptionType($throwable);
        $this->throwable = $throwable;
    }

    public static function createFromThrowable(Throwable $throwable): ThrownResult
    {
        return new static($throwable);
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
