<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class ServiceTests extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function crud(): void {
			$crud = app_path( "Services/Blog/Post/PostCrudService.php" );

			File::delete( $crud );
			self::assertFileDoesNotExist( $crud );

			Artisan::call( "valravn:service blog posts" );

			self::assertFileExists( $crud );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function relations(): void {
			$relations = app_path( "Services/Blog/Post/PostRelationsService.php" );

			File::delete( $relations );
			self::assertFileDoesNotExist( $relations );

			Artisan::call( "valravn:service blog posts -r" );

			self::assertFileExists( $relations );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function actions(): void {
			$actions = app_path( "Services/Blog/Post/PostActionsService.php" );

			File::delete( $actions );
			self::assertFileDoesNotExist( $actions );

			Artisan::call( "valravn:service blog posts -a" );

			self::assertFileExists( $actions );
		}

	}