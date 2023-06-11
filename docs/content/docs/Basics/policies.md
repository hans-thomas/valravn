---
title: "Policies"
weight: 2
---

Valravn has a base class for policies that automatically check authenticated
user has the related permission. there is a roles and permissions management
named [Horus](https://github.com/hans-thomas/horus) that can be very handy.

This class
contains `viewAny`, `view`, `create`, `update`, `batchUpdate`, `delete`, `restore`, `forceDelete`
methods by default. for example, if the related
model was `App\Models\Core\User::class` and we requested a specific id,
the `guessAbility` method returns `core-user-view` ability to authorize. 

There is an example of implementation.

```php
use Hans\Valravn\Policies\Contracts\ValravnPolicy;

class SamplePolicy extends ValravnPolicy {

    /**
     * Set the related model class
     *
     * @return string
     */
    protected function getModel(): string {
        return Post::class; 
    }
    
}
```
