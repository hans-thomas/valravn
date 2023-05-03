<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class PolicyTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function policy(): void {
			$file = app_path( "Policies/Blog/PostPolicy.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:policy blog posts" );

			self::assertFileExists( $file );
		}

	}