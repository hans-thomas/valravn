<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Str;

	class MigrationTests extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function migration(): void {
			$datePrefix = now()->format( 'Y_m_d_His' );
			$file       = base_path( "database/migrations/Blog/{$datePrefix}_create_posts_table.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			Artisan::call( "valravn:migration blog posts" );

			self::assertFileExists( $file );
		}

	}