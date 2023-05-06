<?php

	namespace Hans\Tests\Valravn\Feature\Services;

	use Hans\Tests\Valravn\Core\Factories\CategoryFactory;
	use Hans\Tests\Valravn\Core\Factories\CommentFactory;
	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Core\Resources\Post\PostResource;
	use Hans\Tests\Valravn\Instances\Http\Includes\CategoriesIncludes;
	use Hans\Tests\Valravn\Instances\Http\Includes\CommentsIncludes;
	use Hans\Tests\Valravn\TestCase;
	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Services\Includes\Actions\LimitAction;
	use Hans\Valravn\Services\Includes\IncludingService;
	use Illuminate\Support\Collection;

	class IncludingServiceTest extends TestCase {

		private Collection $posts;
		private IncludingService $service;
		private BaseJsonResource $resource;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->posts    = PostFactory::new()
			                             ->count( 3 )
			                             ->has( CommentFactory::new()->count( 5 ) )
			                             ->has( CategoryFactory::new()->count( 5 ) )
			                             ->create();
			$this->resource = PostResource::make( $this->posts->first() );
			$this->service  = app( IncludingService::class, [ 'resource' => $this->resource ] );
		}


		/**
		 * @test
		 *
		 * @return void
		 */
		public function getRequestedIncludes(): void {
			$this->service->registerIncludesUsingQueryString( "categories.posts,comments.post.comments" );
			self::assertEquals(
				[
					CategoriesIncludes::class => [],
					CommentsIncludes::class   => []
				],
				$this->resource->getRequestedIncludes()
			);

		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function getNestedEagerLoads(): void {
			$this->service->registerIncludesUsingQueryString( "categories.posts,comments.post.comments" );
			self::assertEquals(
				[
					'categories' => 'posts',
					'comments'   => 'post.comments'
				],
				$this->resource->getNestedEagerLoads()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function parseInclude(): void {
			$parse = $this->service->parseInclude( "comments:limit(1).post:select(id).comments" );
			self::assertEquals(
				[
					'relation' => 'comments',
					'actions'  => [
						LimitAction::class => [ 1 ]
					],
					'nested'   => 'post:select(id).comments'
				],
				$parse
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function applyRequestedIncludes(): void {
			$model  = $this->posts->first();
			$data   = $this->service->registerIncludesUsingQueryString( "categories.posts,comments.post.comments" )
			                        ->applyRequestedIncludes( $model )
			                        ->getIncludedData();
			$output = null;
			foreach ( $data as $item ) {
				$output[] = $item->toResponse( request() )->getData( true );
			}
			self::assertEquals(
				[
					[
						'data' => $model->categories->map(
							fn( $category ) => [
								'type'  => 'categories',
								'id'    => $category->id,
								'name'  => $category->name,
								'posts' => $category->posts->map(
									fn( $post ) => [
										'type'    => 'posts',
										'id'      => $post->id,
										'title'   => $post->title,
										'content' => $post->content,
										'pivot'   => [],
									]
								)->toArray(),
								'pivot' => []
							]
						)->toArray(),
						'type' => 'categories'
					],
					[
						'data' => $model->comments->map(
							fn( $comment ) => [
								'type'    => 'comments',
								'id'      => $comment->id,
								'content' => $comment->content,
								'post'    => [
									'type'     => 'posts',
									'id'       => ( $post = $comment->post )->id,
									'title'    => $post->title,
									'content'  => $post->content,
									'comments' => $post->comments->map(
										fn( $comment ) => [
											'type'    => 'comments',
											'id'      => $comment->id,
											'content' => $comment->content,
										]
									)->toArray()
								]
							]
						)->toArray(),
						'type' => 'comments'
					]
				],
				$output
			);
		}

	}