<?php

namespace Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Zrnik\PHPUnit\AssertException;
use Zrnik\PHPUnit\Exceptions;

class AssertExceptionTest extends TestCase
{
    use Exceptions;


    //region PHPUnit Way

    public function test_ExpectException_First(): void
    {
        $exampleObject = new ExampleObject();
        $this->expectException(NotInRangeException::class);
        $exampleObject->assertRange(0);
        //The execution ends here, the method will not continue,
        // after first exception thrown, so I need to create
        // method for every exception tested...
    }

    public function test_ExpectException_Second(): void
    {
        $exampleObject = new ExampleObject();
        $this->expectException(NotInRangeException::class);
        $exampleObject->assertRange(11);
    }

    public function test_OK_Values(): void
    {
        $exampleObject = new ExampleObject();

        $exampleObject->assertRange(1);
        $exampleObject->assertRange(10);

        $this->addToAssertionCount(2); // Yey! Not thrown!
    }

    //endregion

    //region try/catch way
    public function test_TryCatch(): void
    {
        $exampleObject = new ExampleObject();

        try {
            $exampleObject->assertRange(0);
            throw new AssertionFailedError(sprintf("Exception '%s' expected, but not thrown!", NotInRangeException::class));
        } catch (NotInRangeException $ex) {
            $this->addToAssertionCount(1); // Yey! Thrown!
        }

        $exampleObject->assertRange(1);
        $exampleObject->assertRange(10);
        $this->addToAssertionCount(2); // Yey! Not thrown!

        try {
            $exampleObject->assertRange(11);
            throw new AssertionFailedError(sprintf("Exception '%s' expected, but not thrown!", NotInRangeException::class));
        } catch (NotInRangeException $ex) {
            $this->addToAssertionCount(1); // Yey! Thrown!
        }

    }


    //endregion

    //region This Library Way

    //region Static

    public function test_Library_Static(): void
    {
        $exampleObject = new ExampleObject();

        AssertException::assertExceptionThrown(
            NotInRangeException::class,
            function () use ($exampleObject) {
                $exampleObject->assertRange(0);
            }
        );
        $this->addToAssertionCount(1); //Must be done by hand!

        AssertException::assertNoExceptionThrown(function () use ($exampleObject) {
            $exampleObject->assertRange(1);
            $exampleObject->assertRange(10);
        });
        $this->addToAssertionCount(2); //Must be done by hand!

        $throwable = AssertException::assertExceptionThrown(
            NotInRangeException::class,
            function () use ($exampleObject) {
                $exampleObject->assertRange(11);
            }
        );

        $this->assertInstanceOf(NotInRangeException::class, $throwable);

        $this->addToAssertionCount(1); //Must be done by hand!
    }

    //endregion

    //region Trait
    public function test_Library_Trait(): void
    {
        $exampleObject = new ExampleObject();

        $throwable = $this->assertExceptionThrown(
            NotInRangeException::class,
            function () use ($exampleObject) {
                $exampleObject->assertRange(0);
            }
        );

        $this->assertInstanceOf(NotInRangeException::class, $throwable);

        $this->assertNoExceptionThrown(
            function () use ($exampleObject) {
                $exampleObject->assertRange(1);
                $exampleObject->assertRange(10);
            }
        );

        $throwable = $this->assertExceptionThrown(
            NotInRangeException::class,
            function () use ($exampleObject) {
                $exampleObject->assertRange(11);
            }
        );

        $this->assertInstanceOf(NotInRangeException::class, $throwable);
    }
    //endregion

    //endregion

}
