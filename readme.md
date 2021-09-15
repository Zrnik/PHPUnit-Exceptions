# PHPUnit Exceptions

Trait for easier exception testing in [PHPUnit](https://github.com/sebastianbergmann/phpunit).

## Requirements

```
{
    "php": ">=7.4|>=8.0"
}
```

## Usage:

Add a trait `use Zrnik\PHPUnit\Exceptions;` to your test case. 
Then the testing assertions are available.

```php
use PHPUnit\Framework\TestCase;
use Tests\ExampleObject;
use Tests\NotInRangeException;

class ExampleTest extends TestCase
{
    use \Zrnik\PHPUnit\Exceptions; // add this trait to your TestCase

    public function textExample(): void
    {        
        $exampleObject = new ExampleObject();
        
        $this->assertExceptionThrown(
            NotInRangeException::class, // Expected Exception Type
            
            // Closure running the code we expect to get an exception from.
            function () use ($exampleObject) {
                $exampleObject->assertRange(0);
            }
        );
        
        $this->assertNoExceptionThrown(
            function () use ($exampleObject) {
                $exampleObject->assertRange(1);
                $exampleObject->assertRange(10);
            }
        );
        
        $this->assertExceptionThrown(
            NotInRangeException::class,
            function () use ($exampleObject) {
                $exampleObject->assertRange(11);
            }
        );
    }
}
```

## Why

I had problem with default `expectException`. The problem
was creating unnecessary amount of methods or using try/catch blocks
to check for exceptions. All exceptions are available in 
the [./tests/AssertExceptionTest.php](./tests/AssertExceptionTest.php) file.

**Note:** *Maybe, im just bad at testing. It's **totally** possible...*

### Example 1.: Using too many methods...

```php
use PHPUnit\Framework\TestCase;
use Tests\ExampleObject;
use Tests\NotInRangeException;

class ExampleTest extends TestCase
{
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
}
```


### Example 2.: Using try/catch block...

```php
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use Tests\ExampleObject;
use Tests\NotInRangeException;

class ExampleTest extends TestCase
{
    public function test_TryCatch(): void
    {
        $exampleObject = new ExampleObject();

        try {
            $exampleObject->assertRange(0);
            // I don't want to write so long error text everytime I am checking for exceptions!
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
}
```

