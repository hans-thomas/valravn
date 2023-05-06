<?php

	namespace Hans\Tests\Valravn\Feature\Services;

	use Closure;
	use Hans\Tests\Valravn\Instances\Services\CachingServiceProxy;
	use Hans\Tests\Valravn\Instances\Services\SampleService;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Services\Contracts\Service;
	use Illuminate\Support\Facades\Cache;

	class CachingServiceTest extends TestCase {

		private Service $service;
		private CachingServiceProxy $cachingServiceProxy;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->service             = app( SampleService::class );
			$this->cachingServiceProxy = app( CachingServiceProxy::class, [ 'service' => $this->service ] );
		}


		/**
		 * @test
		 *
		 * @return void
		 */
		public function remember(): void {
			Cache::shouldReceive( 'remember' )
			     ->once()
			     ->with(
				     $this->cachingServiceProxy->_makeCachingKey( 'addition', [ 1, 2 ] ),
				     $this->cachingServiceProxy->_calcTtlTime(),
				     Closure::class
			     )
			     ->andReturn( 3 );
			$this->service->cache()->addition( 1, 2 );
		}

	}