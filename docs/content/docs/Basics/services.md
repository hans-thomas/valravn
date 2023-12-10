---
title: "Services"
weight: 2
--- 

Valravn has several services that each one has their own functionality.

## Service contract

Service classes must extend `Hans\Valravn\Services\Contracts\Service` class.

#### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[cache](#cache)
{{< /column >}}

{{< column "method" >}}
[cacheWhen](#cachewhen)
{{< /column >}}

{{< /column >}}

##### cache

Cache method's result called on service instance.

```php
app( ExampleService::class )->cache()->calculatePopularExamples(); // result will store in cache 
app( ExampleService::class )->calculatePopularExamples(); // will not store anything in cache
```

##### cacheWhen

Sometimes you need to conditionally determine you want to use cache or not.

```php
app( ExampleService::class )->cacheWhen( user()->isNotAdmin() )->calculatePopularExamples();
```

## Caching logic

This service helps up caching data and retrieve them on next calls. the logic of
this service is a bit complicated but in the simplest way, it caches data and
reset them for a fixed interval. let's assume we set a 15m interval. client send
a request to retrieve some data at :00 o'clock. it's all about the minutes and
seconds. that's why i said :00 o'clock. the requested data fetch and cache for
next 15 minutes. if another request wants the cached data before :15 o'clock,
the data will retrieve from cache. after :15 o'clock requests should receive
fresh data for the first time. and cached data in second 15m will be valid
until :29 o'clock.

#### CachingService

You can set your custom interval by using `setInterval` method.

```php
app( PostRelationsService::class )->cache()->setInterval( 20 )->viewCategories();
```

#### Cache facade

This facade let you cache some data using same [logic](#caching-logic).

```php
use Hans\Valravn\Facades\Cache;

Cache::store( 'unique_key', fn() => 10 / 12 );
```

## FilteringService

The `FilteringService` allows us to apply some logics on query builder instance
through api calls.

{{< tip >}}
Only [filterable columns](models.md#filterable) can be use in filtering requests.
{{< /tip >}}

To apply requested filters on your queries, you should call `applyFilters` method on your query builder instance. it's
recommended to call `applyFilters` in your service layer.

#### Available filters

{{< column "methods-container" >}}

{{< column "method" >}}
[like_filter](#like_filter)
{{< /column >}}

{{< column "method" >}}
[order_filter](#order_filter)
{{< /column >}}

{{< column "method" >}}
[order_pivot_filter](#order_pivot_filter)
{{< /column >}}

{{< column "method" >}}
[where_filter](#where_filter)
{{< /column >}}

{{< column "method" >}}
[where_pivot_filter](#where_pivot_filter)
{{< /column >}}

{{< column "method" >}}
[where_relation_filter](#where_relation_filter)
{{< /column >}}

{{< column "method" >}}
[where_relation_like_filter](#where_relation_like_filter)
{{< /column >}}

{{< column "method" >}}
[or_where_relation_filter](#or_where_relation_filter)
{{< /column >}}

{{< column "method" >}}
[or_where_relation_like_filter](#or_where_relation_like_filter)
{{< /column >}}

{{< /column >}}

##### like_filter

Add a where like condition to the current query builder instance.

```
domain/api/blog/posts?like_filter[title]=something
```

##### order_filter

You can control order of returned items.

```
domain/api/blog/posts?order_filter[id]=asc
```

or set a descending order.

```
domain/api/blog/posts?order_filter[id]=desc
```

##### order_pivot_filter

If you are retrieving data using a many-to-many relationship and there is a
pivot table, you can sort items based on a pivot column.

```
domain/api/blog/posts/1/categories?order_pivot_filter[order]=asc
```

and for reverse:

```
domain/api/blog/posts/1/categories?order_pivot_filter[order]=desc
```

##### where_filter

Using this filter, you can set one or more where condition on current builder
instance.

```
domain/api/blog/posts?where_filter[id]=153,42
```

##### where_pivot_filter

When you are retrieving data from a relationship, you can set a where condition
on a pivot column. let's assume we have a many-to-many relationship between
posts and comments and there is a status column in our pivot table. this is how
we can get accepted comments of a specific post.

```
domain/api/blog/posts/1/comments?where_pivot_filter[status]=accepted
```

##### where_relation_filter

This filter ables you to fetch posts that has a command with a specific title.

```
domain/api/blog/posts?where_relation_filter[comments->title]=something
```

{{< tip >}}
Only [loadable relations](models.md#loadable) is valid.
{{< /tip >}}

##### where_relation_like_filter

It has the same functionality as `where_relation_filter` but apply where like
condition.

```
domain/api/blog/posts?where_relation_like_filter[comments->title]=something
```

##### or_where_relation_filter

Mixing this filter with other filters, give us more flexibility to get the data
we really wanted. below example, shows how to get posts that has a `title`
like `valravn` OR has a comment(s) that their `title` is equal to `something`.

```
domain/api/blog/posts?or_where_relation_filter[comments->title]=something&like_filter[title]=valravn
```

##### or_where_relation_like_filter

It's the same as `or_where_relation_filter` but this filter add a where like
condition instead of where.

## RoutingService

This service helps you to define your routes. the `Hans\Valravn\Facades\Router`
facade is here to make it easy to use this service.

#### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[apiResource](#apiresource)
{{< /column >}}

{{< column "method" >}}
[resource](#resource)
{{< /column >}}

{{< column "method" >}}
[name](#name)
{{< /column >}}

{{< column "method" >}}
[withBatchUpdate](#withbatchupdate)
{{< /column >}}

{{< column "method" >}}
[relations](#relations)
{{< /column >}}

{{< column "method" >}}
[actions](#actions)
{{< /column >}}

{{< column "method" >}}
[gathering](#gathering)
{{< /column >}}

{{< /column >}}

##### apiResource

Register CRUD routes for api. it has a same behaviour as `Route::apiResource` of
laravel.

```php
use Hans\Valravn\Facades\Router;

Router::apiResource( 'posts' , PostCrudController::class );
```

you can access the registered routes as usual in laravel.

##### resource

The same as `apiResource` method but for MPA (Multi Page Application).

```php
use Hans\Valravn\Facades\Router;

Router::resource( 'posts' , PostCrudController::class );
```

##### name

If there isn't any crud controller, you can just determine the name of the
entity. next you will be able to register your related relations or whatever
routes.

```php
use Hans\Valravn\Facades\Router;

Router::name( 'posts' );
```

##### withBatchUpdate

This method determine you want to have a batch update route for your entity.

```php
use Hans\Valravn\Facades\Router;

Router::apiResource( 'posts' , PostCrudController::class )->withBatchUpdate();
```

Created route's name is like `posts.batch-update`.

##### relations

You can register your relationships routes using this method. first you need to
pass the related controller class and then a closure. inside the closure, you
can register your relationships.

```php
use Hans\Valravn\Facades\Router;

Router::apiResource( 'posts' , PostCrudController::class )
        ->withBatchUpdate()
        ->relations(
            PostRelationsController::class,
            function( RelationsRegisterer $relations ) {
                $relations->belongsTo( 'user' )->only(' view' );
                $relations->belongsToMany( 'categories' )->except( 'attach' , 'detach' );
            }
        )
```

In `PostRelationsController` you must have the `viewUser` method for `user`
relationship and `viewCategories`, `updateCategories` for `categories`
relationship. also, the route's name should be `{name}.{relation}.{action}`. for
example, for `categories` relation, we have `posts.categories.view`
and `posts.categories.update`.

##### actions

This method allows you to define some custom actions.

```php
use Hans\Valravn\Facades\Router;

Router::apiResource( 'posts' , PostCrudController::class )
        ->withBatchUpdate()
        ->actions(
            PostActionsController::class,
            function( ActionsRegisterer $actions ) {
                $actions->get( 'markAsReviewNeeded' );
                $actions->withId()->get( 'makeDraft' );
                $actions->withId()->parameters( 'user' )->get( 'setUserToReview' );
                $actions->withId()->parameters( [ 'reviewer' => 'user' ] )->get( 'setUserToSpellCheck' );
            }
        )
```

First action generates `domain/api/blog/posts/-actions/mark-as-review-needed`
path and route that to `markAsReviewNeeded` method on related controller.

Second one used `withId` method that ended up to
register `domain/api/blog/posts/-actions/{post}/make-draft` path which routes
to `makeDraft` method on related controller. the `makeDraft` method receives a
post model object.

In third example, we used `parameters` method which adds more parameter(s) to
our path. if the parameter passes as a string like `user`, it will
create `domain/api/blog/posts/-actions/{post}/setUserToReview/{user}` path and
as always routes it to `setUserToReview` method on related controller.

Fourth example passed a parameter as an array. this will generate the same route
but prefix parameter with array's key. the registered route should be
like `domain/api/blog/posts/-actions/{post}/set-user-to-spell-check/reviewer/{user}`.

In the end, routes will be accessible through `{name}.actions.{action_name}`
naming pattern. for instance, the first action's name should
be `posts.actions.mark-as-review-needed`.

##### gathering

This method helps when we want to gather a page requests and return all needed
data for a page using one request. everything is the same as `actions` method
with several tiny difference.

```php
use Hans\Valravn\Facades\Router;

Router::apiResource( 'posts' , PostCrudController::class )
        ->withBatchUpdate()
        ->gathering(
            PostGatheringController::class,
            function( GatheringRegisterer $gathering ) {
                $gathering->get('posts');
                $gathering->version(2)->withId()->get('post');
            }
        )
```

First example register `domain/api/blog/posts/-gathering/v1/posts` path and
route it to `postsV1` method on related controller. for example, you can return
posts with loaded first related category and user data all together.

Second one as you could guess,
register `domain/api/blog/posts/-gathering/v2/{post}/post` path and route it
to `postV2` method on related controller. you can return given post resource
with categories relationship data and maybe 5 first comment of the related post.

If you want to access gathering routes using route's name, you can
use `{name}.gathering.{action_name}-v{version}` pattern. for example the first
gathering route's name is `posts.gathering.posts-v1`.

{{< tip >}}
The gathering data just depend on you page sections.
{{< /tip >}}

There is an example of implementing a method on gathering controller. you can
use `AnonymousResourceCollection` and pass your resource or resource collection
instances while wrapped with an array.

```php
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HomeGatheringController extends Controller {
 
  public function version( ): AnonymousResourceCollection {
    return AnonymousResourceCollection::make([
        // your resource or resource collection instances go here
    ]);
  }

}
```
