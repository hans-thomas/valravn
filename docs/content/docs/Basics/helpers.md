---
title: "Helpers"
weight: 2
---

## EnumHelper

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

#### Available functions

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
[logg](#logg)
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

##### logg

Log the given exception to a specific channel and format.

##### slugify

Make a english or non-english string to a slug.
