---
title: "Filters"
weight: 4
---

You can register your custom filter(s) like below example.

```php
return [
    // ...
    'filters' => [
        // ...
        'your_custom_key' => YourCustomFilter::class
    ]
];
```

{{< tip >}}
If you want more information about filters, [see this](../../basics/services/#filteringservice).
{{< /tip >}}