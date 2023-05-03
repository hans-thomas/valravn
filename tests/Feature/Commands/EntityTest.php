<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class EntityTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function entity(): void {
			$exception = app_path( "Exceptions/Blog/Post/PostException.php" );
			$errorCode = app_path( "Exceptions/Blog/Post/PostErrorCode.php" );

			$model      = app_path( "Models/Blog/Post.php" );
			$factory    = base_path( "database/factories/Blog/PostFactory.php" );
			$seeder     = base_path( "database/seeders/Blog/PostSeeder.php" );
			$datePrefix = now()->format( 'Y_m_d_His' );
			$migration  = base_path( "database/migrations/Blog/{$datePrefix}_create_posts_table.php" );

			$crud       = app_path( "Http/Controllers/V1/Blog/Post/PostCrudController.php" );
			$relations  = app_path( "Http/Controllers/V1/Blog/Post/PostRelationsController.php" );
			$actions    = app_path( "Http/Controllers/V1/Blog/Post/PostActionsController.php" );
			$store      = app_path( "Http/Requests/V1/Blog/Post/PostStoreRequest.php" );
			$update     = app_path( "Http/Requests/V1/Blog/Post/PostUpdateRequest.php" );
			$resource   = app_path( "Http/Resources/V1/Blog/Post/PostResource.php" );
			$collection = app_path( "Http/Resources/V1/Blog/Post/PostCollection.php" );

			$policy = app_path( "Policies/Blog/PostPolicy.php" );

			$contract   = app_path( "Repositories/Contracts/Blog/IPostRepository.php" );
			$repository = app_path( "Repositories/Blog/PostRepository.php" );

			$crudService      = app_path( "Services/Blog/Post/PostCrudService.php" );
			$relationsService = app_path( "Services/Blog/Post/PostRelationsService.php" );
			$actionsService   = app_path( "Services/Blog/Post/PostActionsService.php" );

			File::delete( [
				$exception,
				$errorCode,

				$model,
				$factory,
				$seeder,
				$migration,

				$crud,
				$relations,
				$actions,
				$store,
				$update,
				$resource,
				$collection,

				$policy,

				$contract,
				$repository,

				$crudService,
				$relationsService,
				$actionsService,
			] );

			self::assertFileDoesNotExist( $exception );
			self::assertFileDoesNotExist( $errorCode );

			self::assertFileDoesNotExist( $model );
			self::assertFileDoesNotExist( $factory );
			self::assertFileDoesNotExist( $seeder );
			self::assertFileDoesNotExist( $migration );

			self::assertFileDoesNotExist( $crud );
			self::assertFileDoesNotExist( $relations );
			self::assertFileDoesNotExist( $actions );
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );
			self::assertFileDoesNotExist( $resource );
			self::assertFileDoesNotExist( $collection );

			self::assertFileDoesNotExist( $policy );

			self::assertFileDoesNotExist( $contract );
			self::assertFileDoesNotExist( $repository );

			self::assertFileDoesNotExist( $crudService );
			self::assertFileDoesNotExist( $relationsService );
			self::assertFileDoesNotExist( $actionsService );

			Artisan::call( "valravn:entity blog posts" );

			self::assertFileExists( $exception );
			self::assertFileExists( $errorCode );

			self::assertFileExists( $model );
			self::assertFileExists( $factory );
			self::assertFileExists( $seeder );
			self::assertFileExists( $migration );

			self::assertFileExists( $crud );
			self::assertFileExists( $relations );
			self::assertFileExists( $actions );
			self::assertFileExists( $store );
			self::assertFileExists( $update );
			self::assertFileExists( $resource );
			self::assertFileExists( $collection );

			self::assertFileExists( $policy );

			self::assertFileExists( $contract );
			self::assertFileExists( $repository );

			self::assertFileExists( $crudService );
			self::assertFileExists( $relationsService );
			self::assertFileExists( $actionsService );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function version(): void {
			$exception = app_path( "Exceptions/Blog/Post/PostException.php" );
			$errorCode = app_path( "Exceptions/Blog/Post/PostErrorCode.php" );

			$model      = app_path( "Models/Blog/Post.php" );
			$factory    = base_path( "database/factories/Blog/PostFactory.php" );
			$seeder     = base_path( "database/seeders/Blog/PostSeeder.php" );
			$datePrefix = now()->format( 'Y_m_d_His' );
			$migration  = base_path( "database/migrations/Blog/{$datePrefix}_create_posts_table.php" );

			$crud       = app_path( "Http/Controllers/V2/Blog/Post/PostCrudController.php" );
			$relations  = app_path( "Http/Controllers/V2/Blog/Post/PostRelationsController.php" );
			$actions    = app_path( "Http/Controllers/V2/Blog/Post/PostActionsController.php" );
			$store      = app_path( "Http/Requests/V2/Blog/Post/PostStoreRequest.php" );
			$update     = app_path( "Http/Requests/V2/Blog/Post/PostUpdateRequest.php" );
			$resource   = app_path( "Http/Resources/V2/Blog/Post/PostResource.php" );
			$collection = app_path( "Http/Resources/V2/Blog/Post/PostCollection.php" );

			$policy = app_path( "Policies/Blog/PostPolicy.php" );

			$contract   = app_path( "Repositories/Contracts/Blog/IPostRepository.php" );
			$repository = app_path( "Repositories/Blog/PostRepository.php" );

			$crudService      = app_path( "Services/Blog/Post/PostCrudService.php" );
			$relationsService = app_path( "Services/Blog/Post/PostRelationsService.php" );
			$actionsService   = app_path( "Services/Blog/Post/PostActionsService.php" );

			File::delete( [
				$exception,
				$errorCode,

				$model,
				$factory,
				$seeder,
				$migration,

				$crud,
				$relations,
				$actions,
				$store,
				$update,
				$resource,
				$collection,

				$policy,

				$contract,
				$repository,

				$crudService,
				$relationsService,
				$actionsService,
			] );

			self::assertFileDoesNotExist( $exception );
			self::assertFileDoesNotExist( $errorCode );

			self::assertFileDoesNotExist( $model );
			self::assertFileDoesNotExist( $factory );
			self::assertFileDoesNotExist( $seeder );
			self::assertFileDoesNotExist( $migration );

			self::assertFileDoesNotExist( $crud );
			self::assertFileDoesNotExist( $relations );
			self::assertFileDoesNotExist( $actions );
			self::assertFileDoesNotExist( $store );
			self::assertFileDoesNotExist( $update );
			self::assertFileDoesNotExist( $resource );
			self::assertFileDoesNotExist( $collection );

			self::assertFileDoesNotExist( $policy );

			self::assertFileDoesNotExist( $contract );
			self::assertFileDoesNotExist( $repository );

			self::assertFileDoesNotExist( $crudService );
			self::assertFileDoesNotExist( $relationsService );
			self::assertFileDoesNotExist( $actionsService );

			Artisan::call( "valravn:entity blog posts --v 2" );

			self::assertFileExists( $exception );
			self::assertFileExists( $errorCode );

			self::assertFileExists( $model );
			self::assertFileExists( $factory );
			self::assertFileExists( $seeder );
			self::assertFileExists( $migration );

			self::assertFileExists( $crud );
			self::assertFileExists( $relations );
			self::assertFileExists( $actions );
			self::assertFileExists( $store );
			self::assertFileExists( $update );
			self::assertFileExists( $resource );
			self::assertFileExists( $collection );

			self::assertFileExists( $policy );

			self::assertFileExists( $contract );
			self::assertFileExists( $repository );

			self::assertFileExists( $crudService );
			self::assertFileExists( $relationsService );
			self::assertFileExists( $actionsService );

		}


	}