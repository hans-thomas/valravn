---
title: "Resources"
weight: 2
---

Resources have built on top of laravel's resource and resource collection
classes but there are more features! </br>
You can create resource classes on your own or just using our
[resources command](commands.md#resources).

### ValravnJsonResource

it's same as `JsonResource` class on laravel. to start using this, first you
need to create resource class which should see something like this.

```php
use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Illuminate\Database\Eloquent\Model;

class SampleResource extends ValravnJsonResource {

  public function extract( Model $model ): ?array {
      return [
          'id' => $model->id,
          //
      ];
  }
  
  public function type(): string {
    return 'samples';
  }

}
```

You can specify the model's attributes in `extract` method that you wand to see
in response. the `type` method determines related entity on this response for
the front-end dev.

### ValravnResourceCollection

The resource collection class is the same as `ValravnJsonResource`.

```php
use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
use Illuminate\Database\Eloquent\Model;

class SampleCollection extends ValravnResourceCollection {

  public function extract( Model $model ): ?array {
      return null;
  }

  public function type(): string {
    return 'samples';
  }
  
}
```

## Available methods

Resource classes contain several methods and in continue, we will introduce them.

{{< column "methods-container" >}}

{{< column "method" >}}
[only](#only)
{{< /column >}}

{{< column "method" >}}
[loaded](#loaded)
{{< /column >}}

{{< column "method" >}}
[addAdditional](#addadditional)
{{< /column >}}

{{< column "method" >}}
[addExtra](#addextra)
{{< /column >}}

{{< column "method" >}}
[allLoaded](#allloaded)
{{< /column >}}

{{< /column >}}

##### only

If you just don't need some fields in a specific response, you can reduce your exported fields using this method.

```php
SampleResource::make( $this->model )->only( 'name','email' );
// or on a collection class
SampleCollection::make( $this->models )->only( [ 'email' ] );
```

##### loaded

It's a hook on both resource and collection classes that will execute when each item extracted. you can
override `loaded` method on your instance to write your code there.

There is a bit of difference on using `loaded` hook in resource and collection classes. In resource class we have only
`data` parameter.

```php
protected function loaded( &$data ): void {
    $data[ 'sober' ] = "i might regret this when tomorrow comes";
}
```

But in collection class, we have one more parameter named `resource` that let us manipulate loaded pivot data or loaded
relations through related methods.

```php
protected function loaded( &$data, ValravnJsonResource $resource = null ): void {
    $this->addExtra( [
        'sober' => 'i might regret this when tomorrow comes'
    ] );
}
```

##### addAdditional

Using this method, you can add additional data to your response.

```php
SampleResource::make( $model )
              ->addAdditional( [
                  'tulips&roses' => "My star's back shining bright, I just polished it",
              ] );
```

This data will be merged out of `data` wrapper. to merge some data into `data` wrapper, use `addExtra` method.

##### addExtra

It's similar to `addAdditional` method but the difference is, `addExtra` merge data into `data` wrapper.

```php
SampleResource::make( $this->model )
              ->addExtra( [
                  'all facts' => "love don't cost a thing, this is all facts",
              ] );
```

##### allLoaded

This hook will execute when all collection's items extracted.

```php
protected function allLoaded( Collection &$response ): void {
    $this->addAdditional( [
        'all-loaded' => 'will you still love me when i no longer young and beautiful?'
    ] );
}
```

## Queries

A Query is a class that defines for a resource class and front-end dev can
trigger that using a specific query string.

### Resource Query

This contract can be uses in both `ValravnJsonResource` and `ValravnResourceCollection`
classes. first you need to create a class. it's recommended to create the class
in a sub-folder where the resource classes are exist. in addition, it's better
that suffix the classes with `Query` to make recognition easier.

```php
use Hans\Valravn\Http\Resources\Contracts\ResourceQuery;
use Illuminate\Database\Eloquent\Model;

class FirstExampleQuery extends ResourceQuery {

  public function apply( Model $model ): array {
    return [
      'first_example' => ExampleResource::make( $model )
    ];
  }
  
}
```

in `apply` method, you should return an array. we merge the data into the
related resource instance using the array key that you defined.

{{< tip >}}
Make sure there is not any conflict with extracted attributes of the related resource.
{{< /tip >}}

{{< tip >}}
this data will merge into the `data` key on response.
{{< /tip >}}

### Collection Query

Collection Query just can be registered on `ValravnResourceCollection` instances.

```php
use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
use Hans\Valravn\Http\Resources\Contracts\CollectionQuery;

class RelatedExamplesCollectionQuery extends CollectionQuery {

  public function apply( ValravnJsonResource $resource ): array {
    $ids = $resource->resource instanceof Collection ?
      $resource->resource->map( fn( $value ) => [ 'id' => $value->id ] )->flatten() :
      [ $resource->resource->id ];
      
    return [
      'related_examples' => ExampleCollection::make(
        app(ExampleService::class)->relatedToTheseIds($ids)
      )
    ];
  }
  
}
```

{{< tip >}}
It's recommended that suffix the class with `CollectionQuery` to avoid any conflict and mistake.
{{< /tip >}}

### Queries registration

After all, you should register your queries on the resource class. to register a
query, just you need to override the `getAvailableQueries` method and add you
queries.

```php
public function getAvailableQueries(): array {
  return [
    'with_first_example' => FirstExampleQuery::class
  ];
}
```

the array's key is the query string that a front-end dev can trigger this query
and the value must be the query class.

{{< tip >}}
It's recommended to prefix query strings with `with_` to avoid any conflict.
{{< /tip >}}

### Parse queries

By default, the resource classes don't parse queries. to enable this feature,
you should call `parseQueries` on the resource instance that you are returning
as a response.

```php
SampleResource::make($model)->parseQueries();
```

### Manual trigger

Also, you can manually trigger a query on the resource instance by
calling `registerQuery` method.

```php
SampleResource::make($model)->registerQuery(SampleQuery::class);
```

or just create a method on resource class for ease of use.

```php
public function withSampleQuery(): self {
  $this->registerQuery( FirstCommentQuery::class );

  return $this;
}
```

and then you can call this on a resource instance.

```php
SampleResource::make($model)->withSampleQuery();
```

## Includes

Valravn allows you to eager load relationships using query strings and apply
actions on them. first you need to create a class and
extends `Hans\Valravn\Http\Resources\Contracts\Includes` class. next, you should
implement the required methods.

```php
use Hans\Valravn\Http\Resources\Contracts\Includes;

class ExampleIncludes extends Includes {

  public function apply( Model $model ): Builder {
    return $model->example();
  }

  public function toResource(): ValravnJsonResource {
    return ExampleResource::make( $this->getBuilder()->first() );
  }
  
}
```

{{< tip >}}
It's recommended to create includes in a sub folder where the related resource classes are locate.
{{< /tip >}}

### Includes registration

To register a include, you just need to override `getAvailableIncludes` method
on the resource class and then register your includes.

```php
public function getAvailableIncludes(): array {
  return [
    'example'   => ExampleIncludes::class,
  ];
}
```

### Parse includes

By default, resource classes don't parse includes. if you want to parse
includes, just call `parseIncludes` method on your resource instance.

```php
SampleResource::make($model)->parseIncludes();
```

### Manual trigger

Like queries, you can trigger your needed includes by calling `registerInclude`
method.

```php
SampleResource::make($model)->registerInclude(ExampleIncludes::class);
```

or just register your include using a method.

```php
public function withExampleInclude(): self {
  $this->registerInclude( ExampleIncludes::class );

  return $this;
}
```

and then you can call this on a resource instance.

```php
SampleResource::make($model)->withExampleInclude();
```

### Trigger through API

let's talk about how The front-end dev should work with defined includes. let's
assume we just want to include a relationship. all we need to do is this:

```
domain/api/namespace/name?includes=example
```

{{< tip >}}
To eager load a relationship, you must pass the registered include using `includes` key.
{{< /tip >}}

### Nested eager loads

Valravn allows you to use nested eager loads. for that you just need to pass the
nested includes after the first one and split them using a `.` character.

```
domain/api/namespace/name?includes=example.owner
```

{{< tip >}}
We consider the ExampleResource class registered `owner` include. otherwise this will not work.
{{< /tip >}}

### Actions

There are some default actions that you can use on your api calls. you are free
to use an action for two relationship or different actions for any includes.
actions must split using `:` character.

{{< tip >}}
Columns you pass as parameter to actions, must be in filterable list of related model.
{{< /tip >}}

{{< column "methods-container" >}}

{{< column "method" >}}
[select](#select)
{{< /column >}}

{{< column "method" >}}
[order](#order)
{{< /column >}}

{{< column "method" >}}
[limit](#limit)
{{< /column >}}

{{< /column >}}

##### select

This action allows you just fetch columns that you want. it might help create
optimized requests.

```
domain/api/blog/posts?includes=comments:select(content).user:select(first_name|last_name)
```

{{< tip >}}
If there is a belongs to relationship, and you want to use `select`
action, don't forget to select the foreign key too. otherwise the relationship
doesn't resolve by ORM.
{{< /tip >}}

##### order

The `order` action ables you to set order for your relationship results.

```
domain/api/blog/posts?includes=comments:select(content):order(created_at)
```

##### limit

Using this action, you can limit the number of rows must fetch from related
relationship.

```
domain/api/blog/categories?includes=posts:limit(5)
```

this request will return the categories while each category loaded with at least
5 related posts. however the `limit` action accepts two parameter. the second
parameter acts like page number.

```
domain/api/blog/categories?includes=posts:limit(5|2)
```

For example, the above request will skip the first 5 posts and loads the second
5 posts of each category.

### Interact with relations

You can interact with relations and manipulate the relationships' data.

{{< column "methods-container" >}}

{{< column "method" >}}
[resolveRelationsUsing](#resolverelationsusing)
{{< /column >}}

{{< column "method" >}}
[skipRelationsForModel](#skiprelationsformodel)
{{< /column >}}

{{< /column >}}

{{< tip >}}
These methods just effect on current resource instance and other instances that created automatically using current
resource.
{{< /tip >}}

##### resolveRelationsUsing

Accepts relations and their custom resource class to be response to the client.

```php
PostResource::make( $this->post )
->resolveRelationsUsing(
    [
        'comments' => CommentCustomCollection::class
    ]
)
```

##### skipRelationsForModel

Accept models and their related relationships that should be skipped and not present in the output.

```php
PostResource::make( $this->post )
->skipRelationsForModel(
    [
        Post::class => 'comments',
        Comment::class => [ 'post', 'user' ]
    ]
)
```


