<?php

namespace Hans\Valravn\Tests\Feature\Services\Routing;

use Hans\Valravn\Services\Routing\ActionsRegisterer;
use Hans\Valravn\Services\Routing\RoutingService;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleActionsController;
use Hans\Valravn\Tests\Instances\Middlewares\SampleMiddleware;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

class ActionsTest extends TestCase
{
    private RoutingService $service;

    #[Test]
    public function parameters(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->withId()->withParameters('related', ['something' => 'some_thing'])->get('action-with-params');
                    $actions->post('action-with-no-param');
                }
            );
        $this->getJson(
            route('samples.actions.action-with-params', ['sample' => 1, 'related' => 2, 'some_thing' => 3])
        )
            ->assertOk();

        $this->postJson(route('samples.actions.action-with-no-param'))->assertOk();

        self::assertEquals(
            url('samples/-actions/1/action-with-params/2/something/3'),
            route('samples.actions.action-with-params', ['sample' => 1, 'related' => 2, 'some_thing' => 3])
        );
        self::assertEquals(
            url('samples/-actions/action-with-no-param'),
            route('samples.actions.action-with-no-param')
        );
    }

    #[Test]
    public function withId(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->withId()->get('action');
                }
            );
        $this->getJson(route('samples.actions.action', 1))->assertOk();
        self::assertEquals(
            url('samples/-actions/1/action'),
            route('samples.actions.action', 1)
        );
    }

    #[Test]
    public function middleware(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->middleware(SampleMiddleware::class)->get('action-with-middleware');
                }
            );
        $this->getJson(
            route('samples.actions.action-with-middleware')
        )
            ->assertOk();

        self::assertEquals(
            [
                SampleMiddleware::class,
            ],
            Route::getCurrentRoute()->middleware()
        );
    }

    #[Test]
    public function getRoute(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->get('action-with-no-param');
                }
            );
        $this->getJson(route('samples.actions.action-with-no-param'))->assertOk();
    }

    #[Test]
    public function postRoute(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->post('action-with-no-param');
                }
            );
        $this->postJson(route('samples.actions.action-with-no-param'))->assertOk();
    }

    #[Test]
    public function patchRoute(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->patch('action-with-no-param');
                }
            );
        $this->patchJson(route('samples.actions.action-with-no-param'))->assertOk();
    }

    #[Test]
    public function deleteRoute(): void
    {
        $this->service
            ->name('samples')
            ->actions(
                SampleActionsController::class,
                function (ActionsRegisterer $actions) {
                    $actions->delete('action-with-no-param');
                }
            );
        $this->deleteJson(route('samples.actions.action-with-no-param'))->assertOk();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(RoutingService::class);
    }
}
