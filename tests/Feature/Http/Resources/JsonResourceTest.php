<?php

	namespace Hans\Valravn\Tests\Feature\Http\Resources;

	use Hans\Valravn\Tests\Instances\Http\Resources\SampleResource;
	use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithDefaultExtractResource;
	use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithHookResource;
	use Hans\Valravn\Tests\Instances\Http\Resources\SampleWithTypeOverrideResource;
	use Hans\Valravn\Tests\TestCase;
	use Hans\Valravn\Models\ValravnModel;
	use Illuminate\Database\Eloquent\Model;

	class JsonResourceTest extends TestCase {

		private Model $model;

		protected function setUp(): void {
			parent::setUp();
			$this->model = new class extends ValravnModel {
				protected $fillable = [ 'name' ];
			};
			$this->model->forceFill( [ 'id' => rand( 1, 999 ), 'name' => fake()->name() ] );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function make(): void {
			$resource = SampleResource::make( $this->model );
			self::assertEquals(
				[
					'data' => [
						'type' => 'samples',
						'id'   => $this->model->id,
						'name' => $this->model->name,
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
		public function toArrayAsNull(): void {
			$resource = SampleResource::make( null );
			self::assertEquals(
				[
					'data' => [],
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
		public function toArrayAsNullModel(): void {
			$resource = SampleResource::make( new class extends ValravnModel { } );
			self::assertEquals(
				[
					'data' => [
						'type' => 'samples',
						'id'   => null,
						'name' => null,
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
		public function addExtra(): void {
			$resource = SampleResource::make( $this->model )
			                          ->addExtra( [
				                          'all facts' => "love don't cost a thing, this is all facts",
			                          ] );
			self::assertEquals(
				[
					'data' => [
						'type'  => 'samples',
						'id'    => $this->model->id,
						'name'  => $this->model->name,
						'extra' => [
							'all facts' => "love don't cost a thing, this is all facts"
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
		public function addExtrasInChain(): void {
			$resource = SampleResource::make( $this->model )
			                          ->addExtra( [
				                          'all facts' => "love don't cost a thing, this is all facts",
			                          ] )
			                          ->addExtra( [
				                          'introductions' => "give no fucks about no status"
			                          ] );
			self::assertEquals(
				[
					'data' => [
						'type'  => 'samples',
						'id'    => $this->model->id,
						'name'  => $this->model->name,
						'extra' => [
							'all facts'     => "love don't cost a thing, this is all facts",
							'introductions' => "give no fucks about no status"
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
		public function addAdditional(): void {
			$resource = SampleResource::make( $this->model )
			                          ->addAdditional( [
				                          'sober' => "why do people do things that be bad for 'em?",
			                          ] );
			self::assertEquals(
				[
					'data'  => [
						'type' => 'samples',
						'id'   => $this->model->id,
						'name' => $this->model->name,
					],
					'type'  => 'samples',
					'sober' => "why do people do things that be bad for 'em?",
				],
				$this->resourceToJson( $resource )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function addAdditionalInChain(): void {
			$resource = SampleResource::make( $this->model )
			                          ->addAdditional( [
				                          'sober' => "why do people do things that be bad for 'em?",
			                          ] )
			                          ->addAdditional( [
				                          'gerald' => "keep dissing, that's just adding fuel to my engin.",
			                          ] );
			self::assertEquals(
				[
					'data'   => [
						'type' => 'samples',
						'id'   => $this->model->id,
						'name' => $this->model->name,
					],
					'type'   => 'samples',
					'sober'  => "why do people do things that be bad for 'em?",
					'gerald' => "keep dissing, that's just adding fuel to my engin.",
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
			$resource = SampleWithDefaultExtractResource::make( $this->model );
			self::assertEquals(
				[
					'data' => [
						'type' => 'samples',
						... $this->model->toArray()
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
		public function makeAsTypeOverride(): void {
			$resource = SampleWithTypeOverrideResource::make( $this->model );
			self::assertEquals(
				[
					'data' => [
						'type' => 'leaving heaven',
						'id'   => $this->model->id,
						'name' => $this->model->name,
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
		public function loaded(): void {
			$resource = SampleWithHookResource::make( $this->model );
			self::assertEquals(
				[
					'data' => [
						'type'  => 'samples',
						'id'    => $this->model->id,
						'name'  => $this->model->name,
						'sober' => "i might regret this when tomorrow comes"
					],
					'type' => 'samples'
				],
				$this->resourceToJson( $resource )
			);
		}

	}