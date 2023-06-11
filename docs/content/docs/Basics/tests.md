---
title: "Tests"
weight: 2
--- 

For testing, there is some helpful methods that can be found so handy.

### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[getJsonRequest](#getjsonrequest)
{{< /column >}}

{{< column "method" >}}
[postJsonRequest](#postjsonrequest)
{{< /column >}}

{{< column "method" >}}
[patchJsonRequest](#patchjsonrequest)
{{< /column >}}

{{< column "method" >}}
[deleteJsonRequest](#deletejsonrequest)
{{< /column >}}

{{< column "method" >}}
[resourceToJson](#resourcetojson)
{{< /column >}}

{{< /column >}}

##### getJsonRequest

It's a shorthand for sending a json get request.

##### postJsonRequest

Shorthand for sending a json post request.

##### patchJsonRequest

It sends a json patch request.

##### deleteJsonRequest

Shorthand for sending a json delete request.

##### resourceToJson

This method converts you resource or resource collection instance to an array,
then you can compare converted data with response from som route.

```php
/**
 * @test
 *
 * @return void
 */
public function example(): void {
    $token   = $this->actingAsAdminUser();
    $model   = Post::find(1);
    $related = $model->comments();

    $this->getJsonRequest(
        uri: "/api/blog/posts/{$model->id}/comments",
        token: $token
    )
         ->assertOk()
         ->assertExactJson(
             $this->resourceToJson(
                 Comment::getResourceCollection( $related->paginate() )
             )
         );
}
```

### Factory contract

This contract helps to create fake data for your test suite. the implementation class should be like this.

```php
use Hans\Valravn\Repositories\Contracts\Repository;
use Hans\Valravn\Tests\Contracts\Factory;
use Illuminate\Database\Eloquent\Factories\Factory as EloquentFactory;

class PostFactory extends Factory {

    /**
     * Return related factory instance
     *
     * @return EloquentFactory
     */
    protected static function getFactory(): EloquentFactory {
        return PostFactory::new(); // or Post::factory();
    }

    /**
     * Return related repository instance
     *
     * @return Repository
     */
    public static function getRepository(): Repository {
        return app( IPostRepository::class )->disableAuthorization();
    }
}
```

{{< tip >}}
To prevent authorization errors, it's recommended to call `disableAuthorization()` on your repository instance.
{{< /tip >}}

You can make your own methods for generating fake data for your relationships.

#### Available methods

{{< column "methods-container" >}}

{{< column "method" >}}
[preCreateHook](#precreatehook)
{{< /column >}}

{{< column "method" >}}
[create](#create)
{{< /column >}}

{{< column "method" >}}
[make](#make)
{{< /column >}}

{{< column "method" >}}
[createMany](#createmany)
{{< /column >}}

{{< column "method" >}}
[getModel](#getmodel)
{{< /column >}}

{{< /column >}}

##### preCreateHook

PreCreate hook executes before running factory. so, if your factory requited data from other entities, you can create
your needed data in `preCreateHook` method. for instance, we might have a `belongsTo` relationship and before creating
our data, we need a model from related entity.

##### create

Create an instance and stores it into the database.

##### make

Create an instance but doesn't store it on database.

##### createMany

Create many fake data at once and return the created data as a collection.

##### getModel

It returns created model by factory class.