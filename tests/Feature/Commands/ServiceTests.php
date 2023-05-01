<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
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

			$command = $this->artisan( "valravn:service blog posts" );
			$command->execute();
			$command->expectsOutput( "service classes successfully created!" );

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

			$command = $this->artisan( "valravn:service blog posts -r" );
			$command->execute();
			$command->expectsOutput( "service classes successfully created!" );

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

			$command = $this->artisan( "valravn:service blog posts -a" );
			$command->execute();
			$command->expectsOutput( "service classes successfully created!" );

			self::assertFileExists( $actions );
		}

	}