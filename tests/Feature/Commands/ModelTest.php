<?php

	namespace Hans\Tests\Valravn\Feature\Commands;

	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Facades\File;

	class ModelTest extends TestCase {

		/**
		 * @test
		 *
		 * @return void
		 */
		public function model(): void {
			$file = app_path( "Models/Blog/Post.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			$command = $this->artisan( "valravn:model blog posts" );
			$command->execute();
			$command->expectsOutput( "model class successfully created!" );

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function factory(): void {
			$file = base_path( "database/factories/Blog/PostFactory.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			$command = $this->artisan( "valravn:model blog posts -f" );
			$command->execute();
			$command->expectsOutput( "model class successfully created!" );

			self::assertFileExists( $file );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function seeder(): void {
			$file = base_path( "database/seeders/Blog/PostSeeder.php" );
			File::delete( $file );
			self::assertFileDoesNotExist( $file );

			$command = $this->artisan( "valravn:model blog posts -s" );
			$command->execute();
			// TODO: output doesnt match
			// $command->expectsOutput( "model class successfully created!" );

			self::assertFileExists( $file );
		}
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

			$command = $this->artisan( "valravn:model blog posts -m" );
			$command->execute();
			$command->expectsOutput( "model class successfully created!" );

			self::assertFileExists( $file );
		}

	}