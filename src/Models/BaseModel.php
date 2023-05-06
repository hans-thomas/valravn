<?php

	namespace Hans\Valravn\Models;

	use Illuminate\Database\Eloquent\Model;

	class BaseModel extends Model {

		public static function aliasForModelAttributes( Model $model, array $aliases ) {
			foreach ( $aliases as $raw => $alias ) {
				if ( array_key_exists( $alias, $model->getAttributes() ) ) {
					$model->{$raw} = $model->getAttributeFromArray( $alias );
					$model->offsetUnset( $alias );
				}
			}
		}

		public static function table(): string {
			return ( new static )->getTable();
		}

		public static function foreignKey(): string {
			return ( new static )->getForeignKey();
		}
	}
