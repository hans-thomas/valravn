---
title: "Use case"
weight: 5
---

In this section, we will create an api using `Valravn` to show how you should use this. let's assume there is a `posts`
entity and have a `BelongsToMany` relationship with `categories` entity. the `posts` entity is from `blog` namespace
and `categories` entity is from `core` namespace.

## Entity

First of all, we should create entity files by `entity` command.

```php
php artisan valravn:entity blog posts
```

It will create all files we needed. so let's configure the generated files.

## Database

To set up related table, edit migration file in `database/migration/Blog/{date}_create_posts_table.php`.

```php
use App\Models\Blog\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create( Post::table(), function( Blueprint $table ) {
            $table->id();
            $table->string( 'title' );
            $table->text( 'content' );
            $table->timestamps();
        } );
    }

    public function down() {
        Schema::dropIfExists( Post::table() );
    }
};
```

As we said, there is a `BelongsToMany` relationship with `categories` entity, so we should have a pivot table. to create
a pivot migration file, run this command.

```bash
valravn:pivot blog posts core categories

```

This will create a pivot table in `migrations/Blog/` path. if you want some pivot columns, you can define your columns
here.

```php
// database/migrations/Blog/{data}_create_category_post_table.php

use App\Models\Blog\Post;
use App\Models\Core\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create( 'category_post', function( Blueprint $table ) {
            $table->foreignIdFor( Post::class )->constrained()->cascadeOnDelete();
            $table->foreignIdFor( Category::class )->constrained()->cascadeOnDelete();

            $table->primary( [ Post::foreignKey(), Category::foreignKey() ] );
        } );
    }

    public function down() {
        Schema::dropIfExists( 'category_post' );
    }
};

```

There are a factory and seeder classes that you should configure that classes too.

## Routing

It's recommended to define your routes in separate files and name the files as your namespaces. so i create a `php` file
in `routes/app` directory named `blog.php`. then, we should register our new file in `RouteServiceProvider`.

```php
// app/Providers/RouteServiceProvider.php

class RouteServiceProvider extends ServiceProvider {

    // ...

    public function boot() {
        // ...
        $this->routes( function() {
            // ...
            
            Route::prefix( 'api/blog' )
                 ->name( 'blog.' )
                 ->middleware( 'api' )
                 ->group( base_path( 'routes/app/blog.php' ) );
                 
        } );
    }

    // ...
    
}
```

Now, we can register our entity routes.

```php
// routes/app/blog.php

use Hans\Valravn\Facades\Router;

Router::resource( 'posts', PostCrudController::class )
      ->withBatchUpdate()
      ->relations(
          PostRelationsController::class,
          function( RelationsRegisterer $relations ) {
              $relations->belongsToMany( 'categories' );
          }
      );

```

## Repository

Next, to handle `categories` relationship, add these abstract methods to `IPostRepository`.

```php
// app/Repositories/Contract/Blog/IPostRepository.php

use App\Models\Blog\Post;
use App\Repositories\Contracts\Repository;
use Hans\Valravn\DTOs\ManyToManyDto;
use Illuminate\Contracts\Database\Eloquent\Builder;
    
abstract class IUserRepository extends Repository {

    abstract public function viewCategories( Post $post ): Builder;

    abstract public function updateCategories( Post $post, ManyToManyDto $dto ): array;

    abstract public function attachCategories( Post $post, ManyToManyDto $dto ): array;

    abstract public function detachCategories( Post $post, ManyToManyDto $dto ): int;
    
}
```

Then, implement these methods on `PostRepository`.

```php
// app/Repositories/Blog/PostRepository.php

use App\Models\Core\Category;
use App\Models\Blog\Post;
use App\Repositories\Contracts\Blog\IPostRepository;
use Hans\Valravn\DTOs\ManyToManyDto;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PostRepository extends IPostRepository {

    protected function getQueryBuilder(): Builder {
        return Post::query();
    }
    
    public function viewCategories( Category $model ): Builder {
        $this->authorize( $model );

        return $model->categories();
    }

    public function updateCategories( Category $model, ManyToManyDto $dto ): array {
        $this->authorizeThisAction( $model, $dto->getData() );

        return $model->categories()->sync( $dto->getData() );
    }

    public function attachCategories( Category $model, ManyToManyDto $dto ): array {
        $this->authorizeThisAction( $model, $dto->getData() );

        return $model->categories()->syncWithoutDetaching( $dto->getData() );
    }

    public function detachCategories( Category $model, ManyToManyDto $dto ): int {
        $this->authorizeThisAction( $model, $dto->getData() );

        return $model->categories()->detach( $dto->getData() );
    }
}
```

In the end, we should bind `IPostRepository` contract to `PostRepository` class in the `RepositoryServiceProvider`.

```php
// app/Providers/RepositoryServiceProvider.php

use App\Repositories\Contracts\Blog\IPostRepository;
use App\Repositories\Blog\PostRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    public function register() {
        // ...
        $this->app->bind( IPostRepository::class, PostRepository::class );
    }

    public function boot() {
        //
    }
    
}
```

So, we are done with repositories.

## Services

### Crud service

In service layer, we should create CRUD actions in `PostCrudService` first.

```php
// app/Services/Blog/Post/PostCrudService.php

use App\Exceptions\Blog\Post\PostException;
use App\Models\Blog\Post;
use App\Repositories\Contracts\Blog\IPostRepository;
use Hans\Valravn\DTOs\BatchUpdateDto;
use Hans\Valravn\Exceptions\ValravnException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

class PostCrudService {
    private IPostRepository $repository;

    public function __construct() {
        $this->repository = app( IPostRepository::class );
    }

    public function all(): Paginator {
        return $this->repository->all()->applyFilters()->paginate();
    }

    public function create( array $data ): Post {
        throw_unless( 
                $model = $this->repository->create( $data ),
                PostException::failedToCreate()
             );

        return $model;
    }

    public function find( int $model ): Post {
        return $this->repository->find( $model );
    }

    public function update( Post $model, array $data ): Post {
        throw_unless( 
                $this->repository->update( $model, $data ),
                PostException::failedToUpdate() 
            );

        return $model;
    }

    public function batchUpdate( BatchUpdateDto $dto ): Paginator {
        if ( $this->repository->batchUpdate( $dto ) ) {
            return $this->repository->all()
                                    ->whereIn( 'id', $dto->getData()->pluck( 'id' ) )
                                    ->applyFilters()
                                    ->paginate();
        }

        throw PostException::failedToBatchUpdate();
    }

    public function delete( Post $model ): Post {
        throw_unless( $this->repository->delete( $model ), PostException::failedToDelete() );

        return $model;
    }
}
```

### Relations service

After Crud service, we should set up `PostRelationsService`.

```php
// app/Services/Blog/Post/PostRelationsService.php

use App\Exceptions\Blog\Post\PostException;
use App\Models\Blog\Post;
use App\Repositories\Contracts\Blog\IPostRepository;
use Hans\Valravn\DTOs\ManyToManyDto;
use Hans\Valravn\Exceptions\ValravnException;
use Illuminate\Contracts\Pagination\Paginator;

class PostRelationsService {
    private IPostRepository $repository;

    public function __construct() {
        $this->repository = app( IPostRepository::class );
    }

    public function viewCategories( Post $model ): Paginator {
        return $this->repository->viewCategories( $model )->applyFilters()->paginate();
    }

    public function updateCategories( Post $model, ManyToManyDto $dto ): Paginator {
        if ( $this->repository->updateCategories( $model, $dto ) ) {
            return $this->viewCategories( $model );
        }

        throw PostException::failedToUpdateCategories();
    }

    public function attachCategories( Post $model, ManyToManyDto $dto ): Paginator {
        if ( $this->repository->attachCategories( $model, $dto ) ) {
            return $this->viewCategories( $model );
        }

        throw PostException::failedToAttachCategories();
    }

    public function detachCategories( Post $model, ManyToManyDto $dto ): Paginator {
        if ( $this->repository->detachCategories( $model, $dto ) ) {
            return $this->viewCategories( $model );
        }

        throw PostException::failedToDetachCategories();
    }

}
```

## Exceptions

To handle `PostRelationsService` needed exceptions, open `PostException` class and add these methods.

```php
// app/Exceptions/Blog/Post/PostException.php

use Hans\Valravn\Exceptions\ValravnException;
use Symfony\Component\HttpFoundation\Response;

class PostException extends ValravnException {

    public static function failedToUpdateCategories(): ValravnException {
        return self::make(
            "Failed to update post's categories!",
            PostErrorCode::failedToUpdateCategories(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function failedToAttachCategories(): ValravnException {
        return self::make(
            "Failed to attach post's categories!",
            PostErrorCode::failedToAttachCategories(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    public static function failedToDetachCategories(): ValravnException {
        return self::make(
            "Failed to detach post's categories!",
            PostErrorCode::failedToDetachCategories(),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

}
```

Then in the `PostErrorCode` class, we should add new error codes.

```php
// app/Exceptions/Blog/Post/PostErrorCode.php

use Hans\Valravn\Exceptions\ErrorCode;

class PostErrorCode extends ErrorCode {
    protected static string $prefix = 'PECx';

    protected int $FAILED_TO_UPDATE_CATEGORIES = 1; // or failedToUpdateCategories
    protected int $FAILED_TO_ATTACH_CATEGORIES = 2;
    protected int $FAILED_TO_DETACH_CATEGORIES = 3;
}
```

## Controllers

### Crud controller

The `PostCrudController` created in `app/Http/Controllers/V1/Blog/Post/PostCrudController.php`. The created crud
controller has the ability to handling the crud actions. However, if you want to customize the methods, you are free to
make your changes.

#### Crud requests

In addition, we should set up our `PostStoreRequest` and `PostUpdateRequest` requests.

```php
// app/Http/Requests/V1/Blog/Post/PostStoreRequest.php

use Hans\Valravn\Http\Requests\Contracts\ValravnFormRequest;

class PostUpdateRequest extends ValravnFormRequest {

    protected function fields(): array {
        return [
            'title'    => [ 'required', 'string', 'max:255' ],
            'content'  => [ 'required', 'string' ],
        ];
    }
}
```

Also, we have these rules for updating request.

```php
// app/Http/Requests/V1/Blog/Post/PostUpdateRequest.php

use Hans\Valravn\Http\Requests\Contracts\ValravnFormRequest;

class PostUpdateRequest extends ValravnFormRequest {

    protected function fields(): array {
        return [
            'title'    => [ 'string', 'max:255' ],
            'content'  => [ 'string' ],
        ];
    }
}
```

### Relations controller

As we have a `BelongsToMany` relationship, we should create related relation request using below command.

```bash
php artisan valravn:relation blog posts core categories --belongs-to-many

```

{{< tip >}}
If you have some pivot columns, [see this](../basics/commands#relation)
{{< /tip >}}

Next, we should add the needed methods to our relations controller.

```php
// app/Http/Controllers/V1/Blog/Post/PostRelationsController.php

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Blog\Post\PostCategoriesRequest;
use App\Http\Resources\V1\Core\Category\CategoryCollection;
use App\Models\Blog\Post;
use App\Models\Core\Category;
use App\Services\Blog\Post\PostRelationsService;
use Hans\Valravn\DTOs\ManyToManyDto;

class PostRelationsController extends Controller {

    private PostRelationsService $service;

    public function __construct() {
        $this->service = app( PostRelationsService::class );
    }

    public function viewCategories( Post $post ): CategoryCollection {
        return Category::getResourceCollection( $this->service->viewCategories( $post ) );
    }

    public function updateCategories( Post $post, PostCategoriesRequest $request ): CategoryCollection {
        return Category::getResourceCollection(
            $this->service->updateCategories( $post, ManyToManyDto::make( $request->validated() ) )
        );
    }

    public function attachCategories( Post $post, PostCategoriesRequest $request ): CategoryCollection {
        return Category::getResourceCollection(
            $this->service->attachCategories( $post, ManyToManyDto::make( $request->validated() ) )
        );
    }

    public function detachOwned( Post $post, PostCategoriesRequest $request ): CategoryCollection {
        return Category::getResourceCollection(
            $this->service->detachOwned( $post, ManyToManyDto::make( $request->validated() ) )
        );
    }

}

```

## Policy

In continue, to register `PostPolicy` class to related model, should register it in the `AuthServiceProvider`.

```php
// app/Providers/AuthServiceProvider.php

use App\Models\Blog\Post;
use App\Policies\Blog\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // ...
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();
    }

}
```