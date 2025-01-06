---
title: "Helpers"
weight: 2
---

## VEnumHelper

this trait brings useful static methods to enum classes.

#### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[toArray](#toarray)
{{< /column >}}

{{< column "method" >}}
[toArrayKeys](#toarraykeys)
{{< /column >}}

{{< column "method" >}}
[toArrayExcept](#toarraykeys)
{{< /column >}}

{{< column "method" >}}
[toArrayKeysExcept](#toarraykeysexcept)
{{< /column >}}

{{< column "method" >}}
[toArrayOnly](#toarrayonly)
{{< /column >}}

{{< column "method" >}}
[toArrayKeysOnly](#toarraykeysonly)
{{< /column >}}

{{< column "method" >}}
[all](#all)
{{< /column >}}

{{< column "method" >}}
[IndexedAll](#indexedall)
{{< /column >}}

{{< column "method" >}}
[tryFromKey](#tryfromkey)
{{< /column >}}

{{< /column >}}

##### toArray

Convert values of an enum class to an array.

##### toArrayKeys

Convert keys of an enum class to an array.

##### toArrayExcept

Convert values of an enum class to an array except the given values.

##### toArrayKeysExcept

Convert keys of an enum class to an array except the given keys.

##### toArrayOnly

Convert given values of an enum class to an array.

##### toArrayKeysOnly

Convert given keys of an enum class to an array.

##### all

Create an array using all enum members.

##### IndexedAll

Create an array using values of all enum members.

##### tryFromKey

Find a value using the given key, otherwise return the default value.

## Functions

Valravn includes several global functions that you can use in your code.

{{< column "methods-container" >}}

{{< column "method" >}}
[user](#user)
{{< /column >}}

{{< column "method" >}}
[resolveRelatedIdToModel](#resolverelatedidtomodel)
{{< /column >}}

{{< column "method" >}}
[resolveMorphableToResource](#resolvemorphabletoresource)
{{< /column >}}

{{< column "method" >}}
[vlog](#vlog)
{{< /column >}}

{{< column "method" >}}
[slugify](#slugify)
{{< /column >}}

{{< /column >}}

##### user

Return authenticated user or optional null.

##### resolveRelatedIdToModel

Resolve the given id to a related model.

##### resolveMorphableToResource

Resolve given Model to a resource class.

##### vlog

Log the error in a dedicated channel with a simplified format.

```php
class someClass {
    public function someMethod(): void
    {
        try{
            // do something
        }catch (Exception $e){
            vlog('The reason');
        }
    }
}
```
The logged content should be like this:

```text
[2025-01-06 07:57:54] testing.DEBUG: At: [Namespace\someClass::someMethod] => "The reason"
```

Also, You can pass the exception to track the issue.

```php
catch (Exception $e){
    vlog('The reason',['previous' => $e]);
}
```

And if you just want to log the error, you need to pass the exception only.

```php
catch (Exception $e){
    vlog($e);
}
```

##### slugify

Make a english or non-english string to a slug.
