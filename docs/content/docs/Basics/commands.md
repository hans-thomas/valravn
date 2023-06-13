---
title: "Commands"
weight: 2
---

Valravn arrives with a set of commands that makes generating files and
classes easier.
all commands accept two parameter. first one is `namespace` and second one is
`name`. in addition, sometimes for versioning we have a `v` parameter as well.
`namespace` parameter determine the group of the entity. for instance, we could
have a `Blog` namespace and create entities like `Post` or `Comment` in it.
the `v` parameter determines the version of entity. we can create a `Blog`
namespace with several entities in it but in different versions.

## Controller

this command helps to generate controller classes.

```bash 
valravn:controller namespace name --v=1 --relations --actions --requests --resources
```

the generated classes structure should be like:

```
app/
└── Http/
    ├── V1/
    │   └── Namespace/
    │       └── Name/
    │           ├── NameController
    │           ├── NameRelationsController
    │           └── NameActionsController
    ├── Resources/
    │   └── V1/
    │       └── Namespace/
    │           └── Name/
    │               ├── NameResource
    │               └── NameCollection
    └── Requests/
        └── V1/
            └── Namespace/
                └── Name/
                    ├── NameStoreRequest
                    └── NameUpdateRequest

```

## Controllers

Controllers command is a shorthand for `valravn:controller` command that
generates controllers and other related classes at once.

```bash 
valravn:controllers namespace name --v=1 --requests --resources
```

## Requests

to generate only request classes, use this command.

```bash
valravn:requests namespace name --v=1

```

This command generates `store` and `update` requests by default. if you defined your entity with
a [batch update](services.md#withbatchupdate) route, you can create a batch update request using `--batch-update` flag.

```bash
valravn:requests namespace name --v=1 --batch-update

```

## Relation

Using `relations` command, you can generate your requests for handling relationships.

```bash
valravn:relation namespace name related-namespace related-name --v=1

```

It's the basic form of `relation` command.

{{< tip >}}
By default, `--v` parameter is `1` and it's not necessary to pass each time.
{{< /tip >}}

After passing needed parameters, you should determine the relation type
between two entities. for instance, assume there is a `BelongsToMany` relationship between `posts` entity from `blog`
namespace and `categories` entity from `core` namespace.

```bash
valravn:relation blog posts core categories --belongs-to-many

```

This will create a request file in `app/Http/Requests/V1/Blog/Post/PostCategoriesRequest.php` path, and you can set up
your request then. if there are some pivot columns, you can define them in `pivots` method.

```php
use App\Models\Blog\Post;
use Hans\Valravn\Http\Requests\Contracts\Relations\BelongsToManyRequest;

class PostCategoriesWithPivotRequest extends BelongsToManyRequest {

    protected function model(): string {
        return Post::class;
    }

    protected function pivots(): array {
        return [
            'order' => [ 'numeric', 'min:1', 'max:99' ],
            'info'  => [ 'string', 'max:128' ],
        ];
    }

}
```

Available relation flags
are: `--belongs-to-many`, `--has-many`, `--morphed-by-many`, `--morph-to-many`, `--morph-to`.

The usage of `--morph-to` flag is a bit different. so let's go for having an example.

```bash
valravn:relation core likes likable --morph-to

```

As we know, the `morphTo` relationship is between one model and many other models, and there is not a target model to
refer to. so as third parameter, just pass the morphable relation name. then in the created request file, you can limit
the related entities.

There is a `--with-pivot` flag that allows you to create a pivot migration file for your many-to-many relationship.

```bash
valravn:relation blog posts core categories --belongs-to-many --with-pivot

```

{{< tip >}}
If you want more information about this command, [see this](../../use-case/#database)
{{< /tip >}}

## Resources

This command generates only resource and resource collection classes.

```bash
valravn:requests namespace name --v=1

```

## Exceptions

this command generates Exception and ErrorCode classes.

```bash
valravn:exception namespace name

```

The generated exception classes go there:

```
app/
└── Exceptions/
    └── Namespace/
        └── Name/
            ├── NameException
            └── NameErrorCode
```

for more information [see this](exceptions.md).

## Migration

you can generate migration file using this command.

```bash
valravn:migration namespace name

```

it will generate the migration file
in `database/migrations/Namespace/date_create_samples_table.php` path.

{{< tip >}}
Valravn will register your migration files in sub folders. so, you can create and organize you migrations in namespaces.
{{< /tip >}}

## Pivot

To create pivot migration file, just enter the base entity and the related one's names and namespaces.

```bash
valravn:pivot namespace name related-namespace related-name

```

## Model

it generates model and related classes for you.

```bash
valravn:model namespace name --factory --seeder --migration

```

it will generate the migration file
in `database/migrations/Namespace/date_create_samples_table.php` path.

## Policy

Policy command generates a policy class.

```bash
valravn:policy namespace name

```

the policy class will locate here

```
app/
└── Policies/
    └── Namespace/
        └── NamePolicy
```

## Repository

This command generates repository and repository contract.

{{< tip >}}
Reminder: bind the contract to the repository class in RepositoryServiceProvider
{{< /tip >}}

```bash
valravn:policy namespace name

```

generated files should be there

```
app/
└── Repositories/
    ├── Contracts/
    │   └── Namespace/
    │       └── INameRepository
    └── Namespace/
        └── NameRepository
```

## Service

Service command generates your service classes such as relation and action
services.

```bash
valravn:service namespace name --relations --actions

```

generated files would be like:

```
app/
└── Services/
    └── Namespace/
        └── Name/
            ├── NameCrudService
            ├── NameRelationsService
            └── NameActionsService
```

## Entity

And in the end, we have this command to create all classes and files at once.

```bash
valravn:entity namespace name --v=1

```


