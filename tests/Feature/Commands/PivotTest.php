<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class PivotTest extends TestCase
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

        $pivot = database_path("migrations/Blog/{$this->datePrefix}_create_category_post_table.php");
        File::delete($pivot);
    }

    /**
     * @test
     *
     * @return void
     */
    public function pivot(): void
    {
        $this->withoutMockingConsoleOutput();

        $pivot = database_path("migrations/Blog/{$this->datePrefix}_create_category_post_table.php");
        File::delete($pivot);

        self::assertFileDoesNotExist($pivot);

        Artisan::call('valravn:pivot blog posts core category');

        self::assertFileExists($pivot);

        $content = "<?php

    use App\Models\Blog\Post;
    use App\Models\Core\Category;
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
            Schema::create( 'category_post', function( Blueprint \$table ) {
                \$table->foreignIdFor( Post::class )->constrained()->cascadeOnDelete();
                \$table->foreignIdFor( Category::class )->constrained()->cascadeOnDelete();

                \$table->primary( [ Post::foreignKey(), Category::foreignKey() ] );
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::dropIfExists( 'category_post' );
        }
    };
";

        self::assertEquals($content, file_get_contents($pivot));
        self::assertStringContainsString('pivot migration class successfully created!', Artisan::output());

        Artisan::call('valravn:pivot blog posts core category');
        self::assertStringContainsString('pivot migration class exists!', Artisan::output());
    }
}
