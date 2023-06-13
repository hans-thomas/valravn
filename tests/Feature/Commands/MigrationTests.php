<?php

	namespace Hans\Valravn\Tests\Feature\Commands;

	use Hans\Valravn\Tests\TestCase;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\File;

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

			$actions_file = '<?php

    use App\Models\Blog\Post;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::create( Post::table(), function( Blueprint $table ) {
                $table->id();
                $table->timestamps();
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::dropIfExists( Post::table() );
        }
    };
';

			self::assertEquals(
				$actions_file,
				file_get_contents( $file )
			);
			// cuz in next test run the migration file name isn't the same
			File::delete( $file );
		}

	}