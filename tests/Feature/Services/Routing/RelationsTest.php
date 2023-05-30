<?php

	namespace Hans\Tests\Valravn\Feature\Services\Routing;

	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleBelongsToController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleBelongsToManyController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleHasManyController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleHasOneController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleMorphedByManyController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleMorphToController;
	use Hans\Tests\Valravn\Instances\Http\Controllers\SampleMorphToManyController;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Services\Routing\RelationsRegisterer;
	use Hans\Valravn\Services\Routing\RoutingService;

	class RelationsTest extends TestCase {

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
		public function relationsBelongsTo(): void {
			$this->withoutExceptionHandling();
			$this->service
				->name( 'samples' )
				->relations(
					SampleBelongsToController::class,
					function( RelationsRegisterer $relations ) {
						$relations->belongsTo( 'relation' );
					}
				);

			$this->getJson( route( 'samples.relation.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relation.update', [ 1, 3 ] ) )->assertOk();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relationsBelongsToMany(): void {
			$this->service
				->name( 'samples' )
				->relations(
					SampleBelongsToManyController::class,
					function( RelationsRegisterer $relations ) {
						$relations->belongsToMany( 'relations' );
					}
				);
			$this->getJson( route( 'samples.relations.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relations.update', [ 1 ] ), [] )->assertOk();
			$this->patchJson( route( 'samples.relations.attach', [ 1 ] ), [] )->assertOk();
			$this->deleteJson( route( 'samples.relations.detach', [ 1 ] ) )->assertOk();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relationsHasMany(): void {
			$this->service
				->name( 'samples' )
				->relations(
					SampleHasManyController::class,
					function( RelationsRegisterer $relations ) {
						$relations->hasMany( 'relations' );
					}
				);
			$this->getJson( route( 'samples.relations.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relations.update', [ 1 ] ), [] )->assertOk();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relationsHasOne(): void {
			$this->service
				->name( 'samples' )
				->relations(
					SampleHasOneController::class,
					function( RelationsRegisterer $relations ) {
						$relations->hasOne( 'relation' );
					}
				);
			$this->getJson( route( 'samples.relation.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relation.update', [ 1, 2 ] ) )->assertOk();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relationsMorphedByMany(): void {
			$this->service
				->name( 'samples' )
				->relations(
					SampleMorphedByManyController::class,
					function( RelationsRegisterer $relations ) {
						$relations->morphedByMany( 'relations' );
					}
				);
			$this->getJson( route( 'samples.relations.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relations.update', [ 1 ] ), [] )->assertOk();
			$this->patchJson( route( 'samples.relations.attach', [ 1 ] ), [] )->assertOk();
			$this->deleteJson( route( 'samples.relations.detach', [ 1 ] ) )->assertOk();
		}


		/**
		 * @test
		 *
		 * @return void
		 */
		public function relationsMorphTo(): void {
			$this->service
				->name( 'samples' )
				->relations(
					SampleMorphToController::class,
					function( RelationsRegisterer $relations ) {
						$relations->morphTo( 'relation' );
					}
				);
			$this->getJson( route( 'samples.relation.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relation.update', [ 1, 3 ] ) )->assertOk();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relationsMorphToMany(): void {
			$this->service
				->name( 'samples' )
				->relations(
					SampleMorphToManyController::class,
					function( RelationsRegisterer $relations ) {
						$relations->morphToMany( 'relations' );
					}
				);
			$this->getJson( route( 'samples.relations.view', [ 1 ] ) )->assertOk();
			$this->postJson( route( 'samples.relations.update', [ 1 ] ), [] )->assertOk();
			$this->patchJson( route( 'samples.relations.attach', [ 1 ] ), [] )->assertOk();
			$this->deleteJson( route( 'samples.relations.detach', [ 1 ] ) )->assertOk();
		}


	}