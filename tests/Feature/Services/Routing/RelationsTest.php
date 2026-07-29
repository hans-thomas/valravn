<?php

namespace Hans\Valravn\Tests\Feature\Services\Routing;

use Hans\Valravn\Services\Routing\RelationsRegisterer;
use Hans\Valravn\Services\Routing\RoutingService;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleBelongsToController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleBelongsToManyController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleHasManyController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleHasOneController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleMorphedByManyController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleMorphToController;
use Hans\Valravn\Tests\Instances\Http\Controllers\SampleMorphToManyController;
use Hans\Valravn\Tests\Instances\Middlewares\SampleBMiddleware;
use Hans\Valravn\Tests\Instances\Middlewares\SampleMiddleware;
use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;

class RelationsTest extends TestCase
{
    private RoutingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(RoutingService::class);
    }

    #[Test]
    public function relationsBelongsTo(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleBelongsToController::class,
                function (RelationsRegisterer $relations) {
                    $relations->belongsTo('relation');
                }
            );

        $this->getJson(route('samples.relation.view', [1]))->assertOk();
        $this->postJson(route('samples.relation.update', [1, 3]))->assertOk();
    }

    #[Test]
    public function relationsBelongsToMiddleware(): void
    {
        $this->service
            ->name('samplesWithMiddleware')
            ->relations(
                SampleBelongsToController::class,
                function (RelationsRegisterer $relations) {
                    $relations->belongsTo('relation')->only('view');

                    $relations->middleware(SampleMiddleware::class)
                        ->belongsTo('relation')
                        ->only('update');
                }
            );

        $this->getJson(route('samplesWithMiddleware.relation.view', [1]))->assertOk();
        self::assertNotContains(
            SampleMiddleware::class,
            Route::getRoutes()->getByName('samplesWithMiddleware.relation.view')->middleware()
        );

        $this->postJson(route('samplesWithMiddleware.relation.update', [1, 3]))->assertOk();
        self::assertContains(
            SampleMiddleware::class,
            Route::getRoutes()->getByName('samplesWithMiddleware.relation.update')->middleware()
        );
    }

    #[Test]
    public function relationsBelongsToWithoutMiddleware(): void
    {
        $this->service
            ->name('samplesWithoutMiddleware')
            ->relations(
                SampleBelongsToController::class,
                function (RelationsRegisterer $relations) {
                    $relations->withoutMiddleware(SampleBMiddleware::class)
                        ->belongsTo('relation')
                        ->only('view');

                    $relations->middleware(SampleMiddleware::class)
                        ->belongsTo('relation')
                        ->only('update');
                }
            );

        $this->getJson(route('samplesWithoutMiddleware.relation.view', [1]))->assertOk();
        self::assertContains(
            SampleBMiddleware::class,
            Route::getRoutes()->getByName('samplesWithoutMiddleware.relation.view')->excludedMiddleware()
        );
        self::assertNotContains(
            SampleMiddleware::class,
            Route::getRoutes()->getByName('samplesWithoutMiddleware.relation.view')->middleware()
        );

        $this->postJson(route('samplesWithoutMiddleware.relation.update', [1, 3]))->assertOk();
        self::assertContains(
            SampleMiddleware::class,
            Route::getRoutes()->getByName('samplesWithoutMiddleware.relation.update')->middleware()
        );
        self::assertNotContains(
            SampleBMiddleware::class,
            Route::getRoutes()->getByName('samplesWithoutMiddleware.relation.update')->excludedMiddleware()
        );
    }

    #[Test]
    public function relationsBelongsToMany(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleBelongsToManyController::class,
                function (RelationsRegisterer $relations) {
                    $relations->belongsToMany('relations');
                }
            );
        $this->getJson(route('samples.relations.view', [1]))->assertOk();
        $this->postJson(route('samples.relations.update', [1]), [])->assertOk();
        $this->patchJson(route('samples.relations.attach', [1]), [])->assertOk();
        $this->deleteJson(route('samples.relations.detach', [1]))->assertOk();
    }

    #[Test]
    public function relationsHasMany(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleHasManyController::class,
                function (RelationsRegisterer $relations) {
                    $relations->hasMany('relations');
                }
            );
        $this->getJson(route('samples.relations.view', [1]))->assertOk();
        $this->postJson(route('samples.relations.update', [1]), [])->assertOk();
    }

    #[Test]
    public function relationsHasOne(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleHasOneController::class,
                function (RelationsRegisterer $relations) {
                    $relations->hasOne('relation');
                }
            );
        $this->getJson(route('samples.relation.view', [1]))->assertOk();
        $this->postJson(route('samples.relation.update', [1, 2]))->assertOk();
    }

    #[Test]
    public function relationsMorphedByMany(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleMorphedByManyController::class,
                function (RelationsRegisterer $relations) {
                    $relations->morphedByMany('relations');
                }
            );
        $this->getJson(route('samples.relations.view', [1]))->assertOk();
        $this->postJson(route('samples.relations.update', [1]), [])->assertOk();
        $this->patchJson(route('samples.relations.attach', [1]), [])->assertOk();
        $this->deleteJson(route('samples.relations.detach', [1]))->assertOk();
    }

    #[Test]
    public function relationsMorphTo(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleMorphToController::class,
                function (RelationsRegisterer $relations) {
                    $relations->morphTo('relation');
                }
            );
        $this->getJson(route('samples.relation.view', [1]))->assertOk();
        $this->postJson(route('samples.relation.update', [1, 3]))->assertOk();
    }

    #[Test]
    public function relationsMorphToMany(): void
    {
        $this->service
            ->name('samples')
            ->relations(
                SampleMorphToManyController::class,
                function (RelationsRegisterer $relations) {
                    $relations->morphToMany('relations');
                }
            );
        $this->getJson(route('samples.relations.view', [1]))->assertOk();
        $this->postJson(route('samples.relations.update', [1]), [])->assertOk();
        $this->patchJson(route('samples.relations.attach', [1]), [])->assertOk();
        $this->deleteJson(route('samples.relations.detach', [1]))->assertOk();
    }
}
