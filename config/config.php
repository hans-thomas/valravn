<?php

	use Hans\Valravn\Services\Includes\Actions\LimitAction;
	use Hans\Valravn\Services\Includes\Actions\OrderAction;
	use Hans\Valravn\Services\Includes\Actions\SelectAction;

	return [
		'actions' => [
			'select' => SelectAction::class,
			'order'  => OrderAction::class,
			'limit'  => LimitAction::class,
		]
	];