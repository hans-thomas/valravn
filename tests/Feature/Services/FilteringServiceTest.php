<?php

namespace Hans\Valravn\Tests\Feature\Services;

    use Hans\Valravn\Services\Filtering\FilteringService;
    use Hans\Valravn\Tests\Core\Factories\PostFactory;
    use Hans\Valravn\Tests\Core\Models\Post;
    use Hans\Valravn\Tests\TestCase;

    class FilteringServiceTest extends TestCase
    {
        private FilteringService $service;

        /**
         * @return void
         */
        protected function setUp(): void
        {
            parent::setUp();
            $this->service = app(FilteringService::class);
            PostFactory::new()->count(5)->create();
        }

        /**
         * @test
         *
         * @return void
         */
        public function apply(): void
        {
            request()->merge([
                'like_filter' => [
                    'title' => 'G-Eazy',
                ],
            ]);
            $builder = $this->service->apply(Post::query());
            self::assertStringContainsString(
                '"title" LIKE ?',
                $builder->toSql()
            );
        }
    }
