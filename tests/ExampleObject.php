<?php

namespace Tests;

class ExampleObject
{

    const Min = 1;
    const Max = 10;

    /**
     * @param int $number
     */
    public function assertRange(int $number): void
    {
        if($number < static::Min)
            throw new NotInRangeException(
                sprintf(
                    "Number '%s' is lower than '%s'",
                    $number, self::Min
                )
            );

        if($number > static::Max)
            throw new NotInRangeException(
                sprintf(
                    "Number '%s' is larger than '%s'",
                    $number, self::Max
                )
            );
    }

    /**
     * @param int $number
     */
    public function assertRangeAlwaysError(int $number): void
    {
        throw new NotInRangeException(
            sprintf(
                "Number '%s' is bad!",
                $number
            )
        );
    }

}
