<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class PolicyTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function policy(): void
    {
        $file = app_path('Policies/Blog/PostPolicy.php');
        File::delete($file);
        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:policy blog posts');

        self::assertFileExists($file);

        $policy_file = '<?php

    namespace App\Policies\Blog;

    use App\Models\Blog\Post;
    use App\Models\Core\User;
    use App\Policies\Contracts\ValravnPolicy;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Collection;

    class PostPolicy extends ValravnPolicy {

        /**
         * Set the related model class
         *
         * @return string
         */
        protected function getModel(): string {
            return Post::class;
        }

    }
';

        self::assertEquals(
            $policy_file,
            file_get_contents($file)
        );
    }
}
