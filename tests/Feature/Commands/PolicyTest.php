<?php

namespace Hans\Valravn\Tests\Feature\Commands;

use Hans\Valravn\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PolicyTest extends TestCase
{
    #[Test]
    public function policy(): void
    {
        $file = app_path('Policies/Blog/PostPolicy.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:policy blog posts')
            ->expectsOutput('Policy class created.')
            ->doesntExpectOutput('Policy class exists or could not be created.')
            ->assertSuccessful();

        self::assertFileExists($file);

        $policyStub = $this->getStub('policies/policy.stub');
        $policyStub = str_replace('{{POLICY::NAMESPACE}}', 'Blog', $policyStub);
        $policyStub = str_replace('{{POLICY::MODEL}}', 'Post', $policyStub);

        self::assertEquals(
            $policyStub,
            file_get_contents($file)
        );
    }

    #[Test]
    public function policyExists(): void
    {
        $file = app_path('Policies/Blog/PostPolicy.php');

        self::assertFileDoesNotExist($file);

        $this->artisan('valravn:policy blog posts')
            ->expectsOutput('Policy class created.')
            ->doesntExpectOutput('Policy class exists or could not be created.')
            ->assertSuccessful();

        $this->artisan('valravn:policy blog posts')
            ->doesntExpectOutput('Policy class created.')
            ->expectsOutput('Policy class exists or could not be created.')
            ->assertSuccessful();

        self::assertFileExists($file);
    }
}
