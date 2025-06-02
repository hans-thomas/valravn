# V2 -> V3

- The `Hans\Valravn\Http\Resources\Contracts\VJsonResource` abstract class 
moved to `Hans\Valravn\Http\Resources\VJsonResource` namespace. The resource classes must extend
  `Hans\Valravn\Http\Resources\VJsonResource` and `Hans\Valravn\Http\Resources\VResourceCollection` abstracts and
to do a type check, you can use `Hans\Valravn\Http\Resources\Contracts\VJsonResource` and
  `Hans\Valravn\Http\Resources\Contracts\VResourceCollection` interfaces