<?php

	namespace Hans\Tests\Valravn\Feature\Http\Resources;

	use Hans\Tests\Valravn\Instances\Http\Resources\SampleCollection;
	use Hans\Tests\Valravn\Instances\Http\Resources\SampleWithCollectionDefaultExtractCollection;
	use Hans\Tests\Valravn\Instances\Http\Resources\SampleWithDefaultExtractCollection;
	use Hans\Tests\Valravn\Instances\Http\Resources\SampleWithHookCollection;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Models\BaseModel;
	use Illuminate\Support\Collection;

	class ResourceCollectionTest extends TestCase {

		private Collection $models;

		protected function setUp(): void {
			parent::setUp();
			$this->models = collect();
			$object       = new class extends BaseModel {
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
			$resource = SampleCollection::make( collect( [ new class extends BaseModel { } ] ) );
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