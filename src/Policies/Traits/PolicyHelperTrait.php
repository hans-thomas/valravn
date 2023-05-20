<?php


	namespace Hans\Valravn\Policies\Traits;

	trait PolicyHelperTrait {

		/**
		 * Guess the ability
		 *
		 * @return string
		 */
		protected function guessAbility(): string {
			return $this->normalizeModelName( $this->getModel() ) . '-' . debug_backtrace()[ 1 ][ 'function' ];
		}

		/**
		 * normalize the model name
		 *
		 * @param string $model
		 *
		 * @return string
		 */
		protected function normalizeModelName( string $model ): string {
			return array_reverse( explode( '\\', strtolower( $model ) ) )[ 1 ] . '-' .
			       array_reverse( explode( '\\', strtolower( $model ) ) )[ 0 ];
		}

		/**
		 * Guess the ability
		 *
		 * @return string
		 * @deprecated use guessAbility() instead
		 */
		public function makeAbility(): string {
			return $this->normalizeModelName( $this->getModel() ) . '-' . debug_backtrace()[ 1 ][ 'function' ];
		}
	}
