<?php

	namespace Hans\Tests\Valravn\Feature\Services;

	use Hans\Tests\Valravn\Instances\Services\SampleService;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Facades\Cache as ValravnCacheFacade;
	use Hans\Valravn\Services\Contracts\Service;
	use Illuminate\Support\Facades\Cache;

	class CachingServiceTest extends TestCase {

		private Service $service;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->service = app( SampleService::class );
		}


		/**
		 * @test
		 *
		 * @return void
		 */
		public function remember(): void {
			Cache::shouldReceive( 'remember' )
			     ->once()
			     ->andReturn( 3 );
			$this->service->cache()->addition( 1, 2 );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function cache(): void {
			Cache::shouldReceive( 'remember' )
			     ->once()
			     ->andReturn( '10/12' );
			ValravnCacheFacade::cache( '10/12' );
		}


	}