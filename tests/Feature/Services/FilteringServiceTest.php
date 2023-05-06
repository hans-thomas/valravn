<?php

	namespace Hans\Tests\Valravn\Feature\Services;

	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Services\Filtering\FilteringService;

	class FilteringServiceTest extends TestCase {

		private FilteringService $service;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->service = app( FilteringService::class );
			PostFactory::new()->count( 5 )->create();
		}


		/**
		 * @test
		 *
		 * @return void
		 */
		public function apply(): void {
			request()->merge( [
				'like_filter' => [
					'title' => 'G-Eazy'
				]
			] );
			$builder = $this->service->apply( Post::query() );
			self::assertStringContainsString(
				'"title" LIKE ?',
				$builder->toSql()
			);
		}

	}