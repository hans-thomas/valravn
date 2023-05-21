<?php

	namespace Hans\Valravn\Services\Includes\Actions;

	use Hans\Valravn\Services\Contracts\Including\Actions;

	class OrderAction extends Actions {

		function apply( array $params ): void {
			if ( ! isset( $params[ 0 ] ) ) {
				return;
			}
			$params[ 0 ] = $this->getFilterableColumn( $params[ 0 ] );
			$direction   = 'asc';
			if ( isset( $params[ 1 ] ) ) {
				if ( in_array( $params[ 1 ], [ 'asc', 'desc' ] ) ) {
					$direction = $params[ 1 ];
				}
			}

			$this->builder()->reorder( $params[ 0 ], $direction );
		}
	}
