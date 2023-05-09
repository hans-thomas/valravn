<?php

	namespace Hans\Valravn\Models\Traits;

	trait Paginatable {
		/**
		 * Determine maximum item per page
		 *
		 * @var int
		 */
		protected static int $perPageMax = 30;

		/**
		 * Get the number of models to return per page.
		 *
		 * @return int
		 */
		public function getPerPage(): int {
			$perPage = request( 'per_page', $this->perPage );

			if ( $perPage === 'all' ) {
				$perPage = $this->count( 'id' );
			}

			return max( 1, min( static::$perPageMax, (int) $perPage ) );
		}

		/**
		 * Set amount of maximum items on a page
		 *
		 * @param int $perPageMax
		 */
		public static function setPerPageMax( int $perPageMax ): void {
			static::$perPageMax = $perPageMax;
		}
	}
