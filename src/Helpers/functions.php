<?php

	use Hans\Valravn\Exceptions\Package\PackageException;
	use Hans\Valravn\Exceptions\ValravnException;
	use Hans\Valravn\Models\Contracts\ResourceCollectionable;
	use Illuminate\Contracts\Auth\Authenticatable;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Resources\Json\JsonResource;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Optional;

	if ( ! function_exists( 'user' ) ) {
		/**
		 * Return authenticated user or optional null
		 *
		 * @return Authenticatable|Optional
		 */
		function user(): Authenticatable|Optional {
			return Auth::check() ? Auth::user() : optional();
		}
	}

	if ( ! function_exists( 'generate_order' ) ) {
		/**
		 * Generate a random order for factories
		 *
		 * @return float
		 */
		function generate_order(): float {
			return rand( 111111, 999999 ) / 1000;
		}
	}

	if ( ! function_exists( 'resolveRelatedIdToModel' ) ) {
		/**
		 * Resolve the given id to a related model
		 *
		 * @param int    $id
		 * @param string $entity
		 *
		 * @return Model|false
		 * @throws ValravnException
		 */
		function resolveRelatedIdToModel( int $id, string $entity ): Model|false {
			if ( ! ( class_exists( $entity ) and is_a( $entity, Model::class, true ) ) ) {
				throw PackageException::invalidEntity( $entity );
			}

			try {
				$model = ( new $entity )->query()->applyFilters()->findOrFail( $id );
			} catch ( Throwable $e ) {
				return false;
			}

			return $model;
		}
	}

	if ( ! function_exists( 'resolveMorphableToResource' ) ) {
		/**
		 * Resolve given Model to a resource class
		 *
		 * @param Model|null $morphable
		 *
		 * @return JsonResource
		 */
		function resolveMorphableToResource( ?Model $morphable ): JsonResource {
			if ( $morphable instanceof ResourceCollectionable ) {
				return $morphable->toResource();
			}

			return JsonResource::make( $morphable );
		}
	}

	if ( ! function_exists( 'logg' ) ) {
		/**
		 * Log the given exception to a specific channel and format
		 *
		 * @param string    $location
		 * @param Throwable $e
		 * @param array     $context
		 *
		 * @return void
		 */
		function logg( string $location, Throwable $e, array $context = [] ): void {
			// TODO: log channel should be documented
			Log::channel( 'valravn' )->debug( $location . ' => ' . 'message: ' . $e->getMessage(), $context );
		}
	}

	if ( ! function_exists( 'valravn_config' ) ) {
		/**
		 * Get valravn config data
		 *
		 * @param string $key
		 *
		 * @return string|array
		 */
		function valravn_config( string $key ): string|array {
			return config( "valravn.$key" );
		}
	}

	if ( ! function_exists( 'slugify' ) ) {
		/**
		 * Make a english or non-english string to a slug
		 *
		 * @param string $string
		 * @param string $separator
		 *
		 * @return string|null
		 */
		function slugify( string $string, string $separator = '-' ): string|null {

			$_transliteration = [
				"/ö|œ/" => "e",

				"/ü/" => "e",

				"/Ä/" => "e",

				"/Ü/" => "e",

				"/Ö/" => "e",

				"/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ/" => "",

				"/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª/" => "",

				"/Ç|Ć|Ĉ|Ċ|Č/" => "",

				"/ç|ć|ĉ|ċ|č/" => "",

				"/Ð|Ď|Đ/" => "",

				"/ð|ď|đ/" => "",

				"/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě/" => "",

				"/è|é|ê|ë|ē|ĕ|ė|ę|ě/" => "",

				"/Ĝ|Ğ|Ġ|Ģ/" => "",

				"/ĝ|ğ|ġ|ģ/" => "",

				"/Ĥ|Ħ/" => "",

				"/ĥ|ħ/" => "",

				"/Ì|Í|Î|Ï|Ĩ|Ī| Ĭ|Ǐ|Į|İ/" => "",

				"/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı/" => "",

				"/Ĵ/" => "",

				"/ĵ/" => "",

				"/Ķ/" => "",

				"/ķ/" => "",

				"/Ĺ|Ļ|Ľ|Ŀ|Ł/" => "",

				"/ĺ|ļ|ľ|ŀ|ł/" => "",

				"/Ñ|Ń|Ņ|Ň/" => "",

				"/ñ|ń|ņ|ň|ŉ/" => "",

				"/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ/" => "",

				"/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º/" => "",

				"/Ŕ|Ŗ|Ř/" => "",

				"/ŕ|ŗ|ř/" => "",

				"/Ś|Ŝ|Ş|Ș|Š/" => "",

				"/ś|ŝ|ş|ș|š|ſ/" => "",

				"/Ţ|Ț|Ť|Ŧ/" => "",

				"/ţ|ț|ť|ŧ/" => "",

				"/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/" => "",

				"/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/" => "",

				"/Ý|Ÿ|Ŷ/" => "",

				"/ý|ÿ|ŷ/" => "",

				"/Ŵ/" => "",

				"/ŵ/" => "",

				"/Ź|Ż|Ž/" => "",

				"/ź|ż|ž/" => "",

				"/Æ|Ǽ/" => "E",

				"/ß/" => "s",

				"/Ĳ/" => "J",

				"/ĳ/" => "j",

				"/Œ/" => "E",

				"/ƒ/" => ""
			];

			$quotedReplacement = preg_quote( $separator, '/' );

			$merge = [

				'/[^\s\p{Zs}\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',

				'/[\s\p{Zs}]+/mu' => $separator,

				sprintf( '/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement ) => '',

			];

			$map = $_transliteration + $merge;

			unset( $_transliteration );

			return preg_replace( array_keys( $map ), array_values( $map ), $string );

		}
	}