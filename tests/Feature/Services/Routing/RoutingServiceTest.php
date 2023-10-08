<?php

namespace Hans\Valravn\Tests\Feature\Services\Routing;

use Hans\Valravn\Facades\Router;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleApiController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleController;
use Hans\Valravn\Tests\TestCase;

class RoutingServiceTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function apiResource(): void
    {
        Router::apiResource('samples', SampleApiController::class);

        $this->getJson(route('samples.index'))->assertOk();
        $this->postJson(route('samples.store'), [])->assertOk();
        $this->getJson(route('samples.show', 1))->assertOk();
        $this->patchJson(route('samples.update', 1), [])->assertOk();
        $this->deleteJson(route('samples.destroy', 1))->assertOk();
    }

    /**
     * @test
     *
     * @return void
     */
    public function resource(): void
    {
        Router::resource('samples', SampleController::class);

        $this->getJson(route('samples.index'))->assertOk();
        $this->getJson(route('samples.create'))->assertOk();
        $this->postJson(route('samples.store'), [])->assertOk();
        $this->getJson(route('samples.show', 1))->assertOk();
        $this->getJson(route('samples.edit', 1))->assertOk();
        $this->patchJson(route('samples.update', 1), [])->assertOk();
        $this->deleteJson(route('samples.destroy', 1))->assertOk();
    }
}
