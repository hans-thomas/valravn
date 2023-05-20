<?php

	namespace Hans\Tests\Valravn\Feature\Services\Routing;

	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleGatheringController;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Services\Routing\GatheringRegisterer;
	use Hans\Valravn\Services\Routing\RoutingService;
	use Illuminate\Support\Facades\Route;

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
			$this->getJson( route( 'samples.gatherings.something-v1' ) )->assertOk();
			self::assertEquals(
				url( "samples/-gathering/v1/something" ),
				route( 'samples.gatherings.something-v1' )
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
			$this->getJson( route( 'samples.gatherings.something-else-v7' ) )->assertOk();
			$this->getJson( route( 'samples.gatherings.something-v1' ) )->assertOk();
			self::assertEquals(
				url( "samples/-gathering/v7/something-else" ),
				route( 'samples.gatherings.something-else-v7' )
			);
			self::assertEquals(
				url( "samples/-gathering/v1/something" ),
				route( 'samples.gatherings.something-v1' )
			);
		}

	}