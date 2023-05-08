<?php

	namespace Hans\Tests\Valravn\Feature\Services\Routing;

	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleApiController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleController;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Services\Routing\RoutingService;


	class RoutingServiceTest extends TestCase {

		private RoutingService $service;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->service = app( RoutingService::class );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function apiResource(): void {
			$this->service->apiResource( 'samples', SampleApiController::class );
			unset( $this->service );
			$this->getJson( route( 'samples.index' ) )->assertOk();
			$this->postJson( route( 'samples.store' ), [] )->assertOk();
			$this->getJson( route( 'samples.show', 1 ) )->assertOk();
			$this->patchJson( route( 'samples.update', 1 ), [] )->assertOk();
			$this->deleteJson( route( 'samples.destroy', 1 ) )->assertOk();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function resource(): void {
			$this->service->resource( 'samples', SampleController::class );
			unset( $this->service );
			$this->getJson( route( 'samples.index' ) )->assertOk();
			$this->getJson( route( 'samples.create' ) )->assertOk();
			$this->postJson( route( 'samples.store' ), [] )->assertOk();
			$this->getJson( route( 'samples.show', 1 ) )->assertOk();
			$this->getJson( route( 'samples.edit', 1 ) )->assertOk();
			$this->patchJson( route( 'samples.update', 1 ), [] )->assertOk();
			$this->deleteJson( route( 'samples.destroy', 1 ) )->assertOk();
		}

	}