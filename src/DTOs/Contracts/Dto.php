<?php

	namespace Hans\Valravn\DTOs\Contracts;

	use Illuminate\Support\Collection;

	abstract class Dto {
		protected Collection $data;

		public function __construct( array $data ) {
			$this->data = $this->parse( $data );
		}

		abstract protected function parse( array $data ): Collection;

		public static function make( array $data ): static {
			return new static( $data );
		}

		public static function makeFromArray( array|Collection $data, string $keyName = 'related' ): static {
			$output = null;
			$data   = $data instanceof Collection ? $data->toArray() : $data;

			foreach ( $data as $index => $value ) {
				if ( is_int( $value ) ) {
					$output[ $keyName ][] = [ 'id' => $value ];
				} elseif ( is_array( $value ) ) {
					$output[ $keyName ][] = [ 'id' => $index, 'pivot' => $value ];
				}
			}

			return new static( $output );
		}

		public static function export( array $data ): Collection {
			return ( new static( $data ) )->getData();
		}

		public function getData(): Collection {
			return $this->data;
		}
	}
