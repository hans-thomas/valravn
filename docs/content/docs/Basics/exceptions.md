---
title: "Exceptions"
weight: 2
---

## Basic usage
Exceptions use to create an error and response to the client. we have a
predefined structure that includes two base classes. first we should create a
class and extend that from `ValravnException` class.

```php
use Hans\Valravn\Exceptions\ValravnException;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class AppException extends ValravnException {

  public static function failedToDelete( Model $model ): ValravnException {
    return self::make(
      "Failed to delete [" . get_class( $model ) . "] $model->id",
      AppErrorCode::failedToDelete(),
      Response::HTTP_INTERNAL_SERVER_ERROR
    );
  }
  
}
```

Next, we need a class to manage our error codes.

```php
use Hans\Valravn\Exceptions\ErrorCode;

class AppErrorCode extends ErrorCode {
  protected static string $prefix = 'AppECx';

  protected int $failedToDelete = 1;

}
```

The related property can be defined in other ways. for example, you can define
the property like `$FAILED_TO_DELETE` or just define a method like below:

```php
use Hans\Valravn\Exceptions\ErrorCode;

class ValravnErrorCode extends ErrorCode {
  protected static string $prefix = 'ValravnECx';

  public static function failedToDelete(): string {
      return self::$prefix . "1";
  }

}
```
{{< tip >}}
If you are defining an ErrorCode as a method, don't forget to prefix the number.
{{< /tip >}}

## Convert exceptions

To convert all exceptions to Valravn style exceptions, use the `convertUsing` method like below in
`bootstrap/app.php` file:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(\Hans\Valravn\Exceptions\Handler::using());
    })->create();
```