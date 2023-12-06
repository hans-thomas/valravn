---
title: "Models"
weight: 2
---

there is a model class that contains some useful and shorthand methods. in
continue, we will introduce the features of our model class.

## Paginatable

You can determine the default number of items in pagination for each model
separately. to do so, you can override the `$perPageMax` property or just
call `setPerPageMax` method inside of `booted` method.

```php
use Hans\Valravn\Models\ValravnModel;

class Example extends ValravnModel {

    protected static int $perPageMax = 20;
    
    protected static function booted() {
        self::setPerPageMax(20);
    }
		
}

```

However, you can override the amount of items per page by `per_page` query
string.

```
domain/api/blog/categories?per_page=5
```

#### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[aliasForModelAttributes](#aliasformodelattributes)
{{< /column >}}

{{< column "method" >}}
[table](#table)
{{< /column >}}

{{< column "method" >}}
[foreignKey](#foreignkey)
{{< /column >}}

{{< /column >}}

##### aliasForModelAttributes

This method is useful when you want to define an alias for an attribute or a
foreign key of a model. its usage should be something like this:

```php
use Hans\Valravn\Models\ValravnModel;

class Comment extends ValravnModel {

  protected $fillable = [
    'content',
    'its_post_id'
  ];

  public function itsPostId(): Attribute {
    return new Attribute( get: fn() => $this->{Post::foreignKey()} );
  }

  protected static function booted() {
    self::saving(
      fn( self $model ) => self::aliasForModelAttributes(
        $model,
        [
          Post::foreignKey() => 'its_post_id'
        ]
      )
    );
  }


}
```

And the `itsPostId` attribute method helps us get the related value using the
same way of a real attribute.

##### table

It's a shorthand for getting table name of a model class.

```php
Example::table()
// instead of
( new Example )->getTable();
```

##### foreignKey

It's a shorthand for getting foreign key of a model class.

```php
Example::foreignKey()
// instead of
( new Example )->getForeignKey();
```

## Contracts

Valravn arrives with a couple of contracts that each one brings some features to
your model class.

#### Available contracts

{{< column "methods-container" >}}

{{< column "method" >}}
[EntityClasses](#entityclasses)
{{< /column >}}

{{< column "method" >}}
[Filterable](#filterable)
{{< /column >}}

{{< column "method" >}}
[Loadable](#loadable)
{{< /column >}}

{{< column "method" >}}
[ResourceCollectionable](#resourcecollectionable)
{{< /column >}}

{{< /column >}}

##### EntityClasses

Force you to implement methods that used for getting related repository and
services classes.

##### Filterable

Using this interface you can determine your filterable columns that could be
access through api requests. there is an example of implementing this interface.

```php
public function getFilterableAttributes(): array {
    return [
        'id',
        Post::foreignKey() => 'its_post_id',
        'title',
        'description',
        'status',
        'created_at',
    ];
}
```

As you can see, you can set alias for a column.

{{< tip >}}
Notice: some features depend on implementing this contract.
{{< /tip >}}

##### Loadable

This contract ables you to define what relations can be loaded and what resource
class is responsible for. there is an example of how you should implement it.

```php
public function getLoadableRelations(): array {
    return [
        'category' => CategoryResource::class,
        'comments' => CommentCollection::class,
    ];
}
```

{{< tip >}}
Notice: there is some dependency of implementing this contract.
{{< /tip >}}

##### ResourceCollectionable

By implementing this contract, you can access resource classes of a model. this
is very useful and handy in writing codes.
