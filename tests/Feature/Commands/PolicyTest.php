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

        self::assertFileDoesNotExist($file);

        Artisan::call('valravn:policy blog posts');

        self::assertFileExists($file);

        $policyStub = $this->getStub('policies/policy.stub');
        $policyStub = str_replace('{{POLICY::NAMESPACE}}','Blog', $policyStub);
        $policyStub = str_replace('{{POLICY::MODEL}}','Post', $policyStub);

        self::assertEquals(
            $policyStub,
            file_get_contents($file)
        );
    }
}
