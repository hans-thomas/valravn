<?php

	namespace Hans\Valravn\Services\Includes\Actions;

	use Hans\Valravn\Services\Contracts\Including\Actions;

	class LimitAction extends Actions {

		function apply( array $params ): void {
			if ( ! isset( $params[ 0 ] ) or ! is_numeric( $params[ 0 ] ) ) {
				return;
			}
			if ( ! isset( $params[ 1 ] ) ) {
				$params[ 1 ] = 1;
			}

			$per_page = intval( $params[ 0 ] );
			$page     = is_numeric( $params[ 1 ] ) ? intval( $params[ 1 ] ) : 1;
			$skip     = $per_page * $page - $per_page;

			$this->builder()->limit( $per_page )->skip( $skip );
		}
	}
