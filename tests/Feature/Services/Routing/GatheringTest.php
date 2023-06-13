<?php

	namespace Hans\Valravn\Tests\Feature\Services\Routing;

	use Hans\Valravn\Tests\Instances\Http\Controllers\SampleGatheringController;
	use Hans\Valravn\Tests\TestCase;
	use Hans\Valravn\Services\Routing\GatheringRegisterer;
	use Hans\Valravn\Services\Routing\RoutingService;

	class GatheringTest extends TestCase {

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
		public function addRoute(): void {
			$this->service
				->name( 'samples' )
				->gathering(
					SampleGatheringController::class,
					function( GatheringRegisterer $gathering ) {
						$gathering->get( 'something' );
					}
				);
			$this->getJson( route( 'samples.gathering.something-v1' ) )->assertOk();
			self::assertEquals(
				url( "samples/-gathering/v1/something" ),
				route( 'samples.gathering.something-v1' )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function version(): void {
			$this->service
				->name( 'samples' )
				->gathering(
					SampleGatheringController::class,
					function( GatheringRegisterer $gathering ) {
						$gathering->version( 7 )->get( 'something-else' );
						$gathering->get( 'something' );
					}
				);
			$this->getJson( route( 'samples.gathering.something-else-v7' ) )->assertOk();
			$this->getJson( route( 'samples.gathering.something-v1' ) )->assertOk();
			self::assertEquals(
				url( "samples/-gathering/v7/something-else" ),
				route( 'samples.gathering.something-else-v7' )
			);
			self::assertEquals(
				url( "samples/-gathering/v1/something" ),
				route( 'samples.gathering.something-v1' )
			);
		}

	}