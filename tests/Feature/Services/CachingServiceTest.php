<?php

namespace Hans\Valravn\Tests\Feature\Services;

    use Hans\Valravn\Facades\Cache as ValravnCacheFacade;
    use Hans\Valravn\Services\Contracts\Service;
    use Hans\Valravn\Tests\Instances\Services\SampleService;
    use Hans\Valravn\Tests\TestCase;
    use Illuminate\Support\Facades\Cache;

    class CachingServiceTest extends TestCase
    {
        private Service $service;

        /**
         * @return void
         */
        protected function setUp(): void
        {
            parent::setUp();
            $this->service = app(SampleService::class);
        }

        /**
         * @test
         *
         * @return void
         */
        public function remember(): void
        {
            Cache::shouldReceive('remember')
                 ->once();

            $this->service->cache()->addition(1, 2);
        }

        /**
         * @test
         *
         * @return void
         */
        public function cache(): void
        {
            Cache::shouldReceive('remember')
                 ->once();

            ValravnCacheFacade::store('unique_key', fn () => 10 / 12);
        }
    }
