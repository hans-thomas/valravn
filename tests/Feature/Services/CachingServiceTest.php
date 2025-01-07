<?php

namespace Hans\Valravn\Tests\Feature\Services;

use Hans\Valravn\Facades\VCache as ValravnCacheFacade;
use Hans\Valravn\Services\Contracts\Service;
use Hans\Valravn\Tests\Instances\Services\SampleService;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;

class CachingServiceTest extends TestCase
{
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SampleService::class);
    }

    #[Test]
    public function remember(): void
    {
        Cache::shouldReceive('remember')
             ->once();

        $this->service->cache()->addition(1, 2);
    }

    #[Test]
    public function cache(): void
    {
        Cache::shouldReceive('remember')
             ->once();

        ValravnCacheFacade::store('unique_key', fn () => 10 / 12);
    }
}
