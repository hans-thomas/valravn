<?php

namespace Hans\Valravn\Tests\Feature\Services;

use Hans\Valravn\Facades\VCache as ValravnCacheFacade;
use Hans\Valravn\Services\Contracts\VService;
use Hans\Valravn\Tests\Instances\Services\SampleVService;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\Test;

class CachingServiceTest extends TestCase
{
    private VService $service;

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

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SampleVService::class);
    }
}
