---
title: "Exceptions"
weight: 2
---

## Basic usage
To create simpler and meaningful exceptions, The `VException` class renders errors in a homological output
with a unique code. So, The devs could use the code to find the issue faster or even for translating the error messages
in other platforms. The exceptions arrive in two different form that we will review them in the continue.

### Full form
The full form can contain several errors and able us to keep errors of an entity in a same exception class.

```php
use Hans\Valravn\Exceptions\VException;
use Symfony\Component\HttpFoundation\Response;

class FullFormException extends VException
{
    protected string $errorCodePrefix = 'FFEcx';

    public static function failedRemove(string $param): self
    {
        return new self("Failed to remove $param", 1);
    }

    public static function notFount(): self
    {
        return new self('Failed to find your data', 2, Response::HTTP_NOT_FOUND);
    }
}
```

### Compact form
The compact form is simpler and contain just one error. The other difference between the `Full` and `Compact` forms are
the naming. Take a look at the below `Compact` exception and compare with the `Full` form.

```php
use Hans\Valravn\Exceptions\VException;
use Symfony\Component\HttpFoundation\Response;

class CompactFormException extends VException
{
    protected string $errorCodePrefix = 'CFEcx';

    public function __construct(string $class)
    {
        parent::__construct("Class '$class' does not exist", 1, Response::HTTP_NOT_FOUND);
    }
}
```

## Convert exceptions

To convert all exceptions to Valravn style exceptions, use the `using` method like below in
`bootstrap/app.php` file:

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(\Hans\Valravn\Exceptions\VHandler::using());
});
```