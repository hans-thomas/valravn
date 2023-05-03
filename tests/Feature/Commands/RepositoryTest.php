<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

	class RepositoryTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function repository(): void {
			$contract   = app_path( "Repositories/Contracts/Blog/IPostRepository.php" );
			$repository = app_path( "Repositories/Blog/PostRepository.php" );

			File::delete( [ $contract, $repository ] );
			self::assertFileDoesNotExist( $contract );
			self::assertFileDoesNotExist( $repository );

			Artisan::call( "valravn:repository blog posts" );

			self::assertFileExists( $contract );
			self::assertFileExists( $repository );
		}

	}