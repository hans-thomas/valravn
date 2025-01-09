<?php

    use Hans\Valravn\Services\Filtering\Filters\LikeFilter;
    use Hans\Valravn\Services\Filtering\Filters\OrderFilter;
    use Hans\Valravn\Services\Filtering\Filters\OrderPivotFilter;
    use Hans\Valravn\Services\Filtering\Filters\OrWhereRelationFilter;
    use Hans\Valravn\Services\Filtering\Filters\OrWhereRelationLikeFilter;
    use Hans\Valravn\Services\Filtering\Filters\WhereFilter;
    use Hans\Valravn\Services\Filtering\Filters\WherePivotFilter;
    use Hans\Valravn\Services\Filtering\Filters\WhereRelationFilter;
    use Hans\Valravn\Services\Filtering\Filters\WhereRelationLikeFilter;
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
        'actions'    => [
            'select' => SelectAction::class,
            'order'  => OrderAction::class,
            'limit'  => LimitAction::class,
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
        'filters'    => [
            'like_filter'                   => LikeFilter::class,
            'order_filter'                  => OrderFilter::class,
            'order_pivot_filter'            => OrderPivotFilter::class,
            'where_filter'                  => WhereFilter::class,
            'where_pivot_filter'            => WherePivotFilter::class,
            'where_relation_filter'         => WhereRelationFilter::class,
            'where_relation_like_filter'    => WhereRelationLikeFilter::class,
            'or_where_relation_filter'      => OrWhereRelationFilter::class,
            'or_where_relation_like_filter' => OrWhereRelationLikeFilter::class,
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
        | Configuration version
        |--------------------------------------------------------------------------
        |
        | Every update in config file will increase the config version. It will be
        | used to inform devs to update their published config file.
        |
        */
        'config_version' => '1.0.0',
    ];
