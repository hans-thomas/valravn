---
title: "Repositories"
weight: 2
---

There is a repository base class that contains CRUD and other useful method
which you can use to avoid redundancy.

### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[getQueryBuilder](#getquerybuilder)
{{< /column >}}

{{< column "method" >}}
[disableAuthorization](#disableauthorization)
{{< /column >}}

{{< column "method" >}}
[enableAuthorization](#enableauthorization)
{{< /column >}}

{{< column "method" >}}
[query](#query)
{{< /column >}}

{{< column "method" >}}
[authorize](#authorize)
{{< /column >}}

{{< column "method" >}}
[authorizeThisAction](#authorizethisaction)
{{< /column >}}

{{< column "method" >}}
[all](#all)
{{< /column >}}

{{< column "method" >}}
[find](#find)
{{< /column >}}

{{< column "method" >}}
[create](#create)
{{< /column >}}

{{< column "method" >}}
[update](#update)
{{< /column >}}

{{< column "method" >}}
[batchUpdate](#batchupdate)
{{< /column >}}

{{< column "method" >}}
[delete](#delete)
{{< /column >}}


{{< /column >}}

#### getQueryBuilder

After creating your repository class by your self our using
valravn `repository` [command](commands.md#repository), you must implement this
method to establish related query builder object for repository class.

#### disableAuthorization

Every actions on builder instance should authorize. sometimes you don't want to
authorize your query, so by calling `disableAuthorization` method on your
repository instance, the repository doesn't authorize the action.

#### enableAuthorization

Sometimes you want to run an action without authorization but after that in the
same scope, you want to run an action with authorization. in this scenario, you
need to call `disableAuthorization` method first and after that
call `enableAuthorization` method to enable authorization again.

#### query

When you call an action like `all`, you might don't want all columns of rows. so
you can use `select` method before `all` and pass your specific columns to
fetch. there can be more methods like `select`. the important point here is,
methods like `select` only apply when you use `query` method to get builder
instance.

> Notice: it's recommended to get builder instance using `query` method instead
> of `getQueryBuilder`.

#### authorize

Using this method you will be able to authorize actions. there is some examples.

```php
public function create( array $data ): Model {
  $this->authorize();

  return $this->query()->create( $data );
}
```

Above example shows how to authorize an action without any parameter. it will
authorize `create` ability on related policy class. to determine your custom
ability to authorize, you just have to pass the name of your ability
to `authorize` method.

```php
public function create( array $data ): Model {
  $this->authorize('makeAndStore');

  return $this->query()->create( $data );
}
```

Then, you need to create `makeAndStore` method in your related policy class.

Also, you can only pass the model object and let the `authorize` method guess
the ability.

```php
public function viewComments( Post $model ): Builder {
  $this->authorize( $model );

  return $model->comments()
}
```

In above example, the `authorize` method will authorize the `viewComments`
method of related policy class. the `viewComments` method on policy class will
receive `$model` parameter.

And there is the way you can pass your custom ability with parameter(s).

```php
public function viewComments( Post $model ): Builder {
  $this->authorize( 'viewAllComments', $model );

  return $model->comments()
}
```

#### authorizeThisAction

It's just a shorthand for `authorize` method.

```php
$this->authorizeThisAction( $param );
// instead of
$this->authorize( null, $param );
```

#### all

Return query builder instance and ables us to access the all rows.

#### find

Find a specific resource using `id` by default. but if you want to find a row by
another column, you just need to do something like this:

```php
app( SampleRepository::class )->find('specific-slug-for-example','slug');
```

First parameter is the value and the second value is the column name that
Valravn should search in.

#### create

Create a resource using given data and return the created model as result.

#### update

Update specific Model using given data and return a boolean as result.

#### batchUpdate

Update many resources in one query at once and also return a bool value as
result.

#### delete

Delete a specific resource and return `true`. otherwise throw an
exception. `delete` method contains two `deleting` and `deleted` hook.
