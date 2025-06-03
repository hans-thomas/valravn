# V2 -> V3

- The `Hans\Valravn\Http\Resources\Contracts\VJsonResource` abstract class 
moved to `Hans\Valravn\Http\Resources\VJsonResource` namespace. The resource classes must extend
  `Hans\Valravn\Http\Resources\VJsonResource` and `Hans\Valravn\Http\Resources\VResourceCollection` abstracts and
to do a type check, you can use `Hans\Valravn\Http\Resources\Contracts\VJsonResource` and
  `Hans\Valravn\Http\Resources\Contracts\VResourceCollection` interfaces
- The `ResourceCollectionable` interface's methods renamed to avoid conflicts with Laravel internal methods:
The `getResource` renamed to `getVResource`, `toResource` renamed to `toVResource` and `getResourceCollection`
renamed to `getVCollection`.
- The `toResource` method renamed to `toVResource` in `Hans\Valravn\Http\Resources\Contracts\Includes` contract.
- The `resolveMorphableToResource` helper function renamed to `resolveMorphableToVResource`.
- Rename the `parameters` method of `actionsregisterer` class to `withParameters` to matches with other methods of its class.
- Rename `Hans\Valravn\Services\Contracts\Service` contract to `Hans\Valravn\Services\Contracts\VService`
- Rename `Hans\Valravn\Services\Contracts\Filters\Filter` contract to `Hans\Valravn\Services\Contracts\Filters\VFilter`
- Rename `Hans\Valravn\Services\Contracts\Including\Action` contract to `Hans\Valravn\Services\Contracts\Including\VAction`
- Rename `Hans\Valravn\Services\Contracts\Notification\Notifiable` contract to `Hans\Valravn\Services\Contracts\Notification\VNotifiable`
- Rename `Hans\Valravn\Services\Contracts\Routeing\Relations` contract to `Hans\Valravn\Services\Contracts\Routeing\VRelations`
- Rename `Hans\Valravn\Repositories\Contracts\Repository` contract to `Hans\Valravn\Repositories\Contracts\VRepository`
- Rename `Hans\Valravn\Http\Resources\Contracts\ResourceQuery` contract to `Hans\Valravn\Http\Resources\Contracts\VResourceQuery`
- Rename `Hans\Valravn\Http\Resources\Contracts\Includes` contract to `Hans\Valravn\Http\Resources\Contracts\VIncludes`
- Rename `Hans\Valravn\Http\Resources\Contracts\CollectionQuery` contract to `Hans\Valravn\Http\Resources\Contracts\VCollectionQuery`
- Rename `getAvailableIncludes` method of Resource classes to `getAvailableVIncludes`
- Rename `getAvailableQueries` method of Resource classes to `getAvailableVQueries`