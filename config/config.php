<?php

use Hans\Valravn\Services\Filtering\Filters\LikeVFilter;
use Hans\Valravn\Services\Filtering\Filters\OrderPivotVFilter;
use Hans\Valravn\Services\Filtering\Filters\OrderVFilter;
use Hans\Valravn\Services\Filtering\Filters\OrWhereRelationLikeVFilter;
use Hans\Valravn\Services\Filtering\Filters\OrWhereRelationVFilter;
use Hans\Valravn\Services\Filtering\Filters\WherePivotVFilter;
use Hans\Valravn\Services\Filtering\Filters\WhereRelationLikeVFilter;
use Hans\Valravn\Services\Filtering\Filters\WhereRelationVFilter;
use Hans\Valravn\Services\Filtering\Filters\WhereVFilter;
use Hans\Valravn\Services\Includes\Actions\LimitAction;
use Hans\Valravn\Services\Includes\Actions\OrderAction;
use Hans\Valravn\Services\Includes\Actions\SelectAction;

return [

    /*
    |--------------------------------------------------------------------------
    | Actions registration
    |--------------------------------------------------------------------------
    |
    | The actions allow you to do a customization on what and how you want
    | your data. You can register your own custom actions here.
    |
    */
    'actions' => [
        'select' => SelectAction::class,
        'order' => OrderAction::class,
        'limit' => LimitAction::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Filters registration
    |--------------------------------------------------------------------------
    |
    | The filters allow you to apply a logic on your data that
    | come from the endpoint. You can register your own custom filters here.
    |
    */
    'filters' => [
        'like_filter' => LikeVFilter::class,
        'order_filter' => OrderVFilter::class,
        'order_pivot_filter' => OrderPivotVFilter::class,
        'where_filter' => WhereVFilter::class,
        'where_pivot_filter' => WherePivotVFilter::class,
        'where_relation_filter' => WhereRelationVFilter::class,
        'where_relation_like_filter' => WhereRelationLikeVFilter::class,
        'or_where_relation_filter' => OrWhereRelationVFilter::class,
        'or_where_relation_like_filter' => OrWhereRelationLikeVFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration paths
    |--------------------------------------------------------------------------
    |
    | You can specify a custom path for you migration files. All subfolders
    | register automatically.
    |
    */
    'migrations' => [
        database_path('migrations'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration paths
    |--------------------------------------------------------------------------
    |
    | You can specify a custom path for you migration files. All subfolders
    | register automatically.
    |
    */
    'middlewares' => [
        'api',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration version
    |--------------------------------------------------------------------------
    |
    | Every update in config file will increase the config version. It will be
    | used to inform devs to update their published config file.
    |
    */
    'config_version' => '1.0.1',
];
