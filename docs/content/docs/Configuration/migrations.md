---
title: "Migrations"
weight: 4
---

You can define a custom path for your migrations file or create some migrations in another path. to register your
migrations, pass your custom paths here.

```php
return [
    // ...
    'migrations' => [
        database_path( 'migrations' ),
        // you custom path goes here
    ]
];
```