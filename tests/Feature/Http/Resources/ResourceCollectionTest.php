<?php

	namespace Hans\Valravn\Tests\Feature\Http\Resources;

	use Hans\Valravn\Tests\Instances\Http\Resources\SampleCollection;
	use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithCollectionDefaultExtractCollection;
	use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithDefaultExtractCollection;
	use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithHookCollection;
	use Hans\Valravn\Tests\TestCase;
	use Hans\Valravn\Models\ValravnModel;
	use Illuminate\Support\Collection;

	class ResourceCollectionTest extends TestCase {

		private Collection $models;

		protected function setUp(): void {
			parent::setUp();
			$this->models = collect();
			$object       = new class extends ValravnModel {
				protected $fillable = [ 'name' ];
			};
			foreach ( range( 1, 5 ) as $counter ) {
				$this->models->push(
					clone $object->forceFill( [ 'id' => rand( 1, 999 ), 'name' => fake()->name() ] )
				);
			}
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function make(): void {
			$collection = SampleCollection::make( $this->models );
			self::assertEquals(
				[
					'data' => $this->models->map(
						fn( $model ) => [
							'type' => 'samples',
							'id'   => $model->id,
						]
					)
					                       ->toArray(),
					'type' => 'samples'
				],
				$this->resourceToJson( $collection )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayAsNull(): void {
			$collection = SampleCollection::make( null );
			self::assertEquals(
				[
					'data' => [],
					'type' => 'samples'
				],
				$this->resourceToJson( $collection )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function toArrayAsNullModel(): void {
			$resource = SampleCollection::make( collect( [ new class extends ValravnModel { } ] ) );
			self::assertEquals(
				[
					'data' => [
						[
							'type' => 'samples',
							'id'   => null,
						]
					],
					'type' => 'samples'
				],
				$this->resourceToJson( $resource )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function makeAsDefaultExtract(): void {
			$resource = SampleWithDefaultExtractCollection::make( $this->models );
			self::assertEquals(
				[
					'data' => $this->models->map(
						fn( $model ) => [
							'type' => 'samples',
							...$model->toArray()
						]
					)
					                       ->toArray(),
					'type' => 'samples'
				],
				$this->resourceToJson( $resource )
			);
		}
		/**
		 * @test
		 *
		 * @return void
		 */
		public function makeAsDefaultExtractInCollection(): void {
			$resource = SampleWithCollectionDefaultExtractCollection::make( $this->models );
			self::assertEquals(
				[
					'data' => $this->models->map(
						fn( $model ) => [
							'type' => 'samples',
							'id'      => $model->id,
							'name'    => $model->name,
							'extract' => 'not default on resource'
						]
					)
					                       ->toArray(),
					'type' => 'samples'
				],
				$this->resourceToJson( $resource )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function allLoaded(): void {
			$resource = SampleWithHookCollection::make( $this->models );
			self::assertEquals(
				[
					'data'       => $this->models->map(
						fn( $model ) => [
							'type' => 'samples',
							'id'   => $model->id,
						]
					)
					                             ->toArray(),
					'type'       => 'samples',
					'all-loaded' => 'will you still love me when i no longer young and beautiful?'
				],
				$this->resourceToJson( $resource )
			);
		}


	}