<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MigrationTests extends TestCase
{
    protected string $datePrefix;

    protected function setUp(): void
    {
        parent::setUp();

        $this->datePrefix = now()->format('Y_m_d_His');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $file = base_path("database/migrations/Blog/{$this->datePrefix}_create_posts_table.php");
        File::delete($file);
    }

    /**
     * @test
     *
     * @return void
     */
    public function migration(): void
    {
        $this->withoutMockingConsoleOutput();
        $file = base_path("database/migrations/Blog/{$this->datePrefix}_create_posts_table.php");
        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:migration blog posts');

        self::assertFileExists($file);

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

        self::assertEquals($actions_file, file_get_contents($file));
        self::assertStringContainsString('migration class successfully created!', Artisan::output());

        Artisan::call('valravn:migration blog posts');
        self::assertStringContainsString('migration class exists!', Artisan::output());
    }
}
