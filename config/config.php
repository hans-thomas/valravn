<?php

	use Hans\Valravn\Services\Filtering\Filters\IncludeFilter;
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
		'actions'    => [
			'select' => SelectAction::class,
			'order'  => OrderAction::class,
			'limit'  => LimitAction::class,
		],
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
		'migrations' => [
			database_path( 'migrations' ),
		]
	];