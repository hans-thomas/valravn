<?php

	namespace Hans\Valravn\Models;

	use Hans\Valravn\Models\Traits\Paginatable;
	use Illuminate\Database\Eloquent\Model;

	class ValravnModel extends Model {
		use Paginatable;

		/**
		 * Make an alias for given model
		 *
		 * @param Model $model
		 * @param array $aliases
		 *
		 * @return void
		 */
		public static function aliasForModelAttributes( Model $model, array $aliases ): void {
			foreach ( $aliases as $raw => $alias ) {
				if ( array_key_exists( $alias, $model->getAttributes() ) ) {
					$model->{$raw} = $model->getAttributeFromArray( $alias );
					$model->offsetUnset( $alias );
				}
			}
		}

		/**
		 * Return table name in a static way
		 *
		 * @return string
		 */
		public static function table(): string {
			return ( new static )->getTable();
		}

		/**
		 * Return foreign key in a static way
		 *
		 * @return string
		 */
		public static function foreignKey(): string {
			return ( new static )->getForeignKey();
		}
	}
