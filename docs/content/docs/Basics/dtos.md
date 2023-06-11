---
title: "DTO classes"
weight: 2
---

A Data Transfer Object (DTO) is an object that is used to encapsulate data, and
send it through different services. valravn has several dto classes which we are
going to introduce them.

## BatchUpdateDto

This class helps us to pass needed data to related `batchUpdate` method in
service or repository classes. there is some example of how we should use it.

```php
BatchUpdateDto::make( [
  'batch' => [
    [ 'id' => 1 ], // make no change
    [ 'id' => 3, 'the art' => "no limit i'm a fucking soldier" ],
    [ 'id' => 5, 'the artist' => 'g-eazy' ],
  ]
] );
```

You just must pass the value of model's id, however other fillable attributes
can be passed if there is a new value for them.

## HasManyDto

This Dto class makes it easy to update a has many relationship. the usage should
be like:

```php
HasManyDto::make( [
  'related' => [
    [ 'id' => 1 ],
    [ 'id' => 3 ],
    [ 'id' => 5 ],
  ]
] );
```

## ManyToManyDto

ManyToManyDto uses to supply data for many-to-many relationships actions. this
is how we should use it.

```php
ManyToManyDto::make( [
  'related' => [
    [
      'id'    => 1,
      'pivot' => [
        'extra' => "same as it ever was, say it'll change but it never does",
        'song'  => 'farewell'
      ]
    ],
    [ 'id' => 3 ],
    [ 'id' => 5 ],
    [
      'id'    => 8,
      'pivot' => [
        'the art'    => "i'm talking to myself like every night, you could try to be a better guy",
        'the artist' => 'g-eazy'
      ]
    ]
  ]
] );
```

## MorphToDto

in the end, we have MorphToDto class that helps us to pass data to a morphTo
relationship. there is an example:

```php
MorphToDto::make( [
  'related' => [
    'entity' => 'posts',
  ]
] );
```
