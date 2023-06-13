<?php

	namespace Hans\Valravn\Tests\Feature\Services;

	use Hans\Valravn\Tests\Core\Factories\CategoryFactory;
	use Hans\Valravn\Tests\Core\Factories\CommentFactory;
	use Hans\Valravn\Tests\Core\Factories\PostFactory;
	use Hans\Valravn\Tests\Core\Models\Comment;
	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Tests\Core\Resources\Category\CategoryResource;
	use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
	use Hans\Valravn\Tests\Core\Resources\Comment\CommentResource;
	use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
	use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
	use Hans\Valravn\Tests\Instances\Http\Queries\CommentsQuery;
	use Hans\Valravn\Tests\Instances\Http\Queries\FirstCategoryQuery;
	use Hans\Valravn\Tests\Instances\Http\Queries\FirstCommentQuery;
	use Hans\Valravn\Tests\Instances\Services\QueryingServiceProxy;
	use Hans\Valravn\Tests\TestCase;
	use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\ValravnResourceCollection;
	use Illuminate\Support\Collection;

	class QueryingServiceTest extends TestCase {

		private Collection $posts;
		private QueryingServiceProxy $serviceCollection;
		private ValravnResourceCollection $collection;
		private QueryingServiceProxy $serviceResource;
		private ValravnJsonResource $resource;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->posts             = PostFactory::new()
			                                      ->count( 3 )
			                                      ->has( CommentFactory::new()->count( 5 ) )
			                                      ->has( CategoryFactory::new()->count( 5 ) )
			                                      ->create();
			$this->collection        = PostCollection::make( $this->posts );
			$this->serviceCollection = app( QueryingServiceProxy::class, [ 'resource' => $this->collection ] );
			$this->resource          = PostResource::make( $this->posts->first() );
			$this->serviceResource   = app( QueryingServiceProxy::class, [ 'resource' => $this->resource ] );
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function registerQueriesUsingQueryString(): void {
			$this->serviceCollection->registerQueriesUsingQueryString( "with_all_comments=&with_first_comment=&with_first_category=&something_false=" );
			self::assertEquals(
				[
					CommentsQuery::class, // CollectionQuery
					FirstCommentQuery::class, // ResourceQuery
					FirstCategoryQuery::class, // ResourceQuery
				],
				$this->collection->getRequestedQueries()
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function applyRequestedQueries(): void {
			$this->serviceResource->registerQueriesUsingQueryString(
				"with_all_comments=&with_first_comment=&with_first_category="
			)
			                      ->applyRequestedQueries( $this->posts->first() );
			self::assertEquals(
				FirstCommentQuery::class,
				get_class( $this->serviceResource->_getExecutedQueries()[ 0 ] )
			);
			self::assertEquals(
				FirstCategoryQuery::class,
				get_class( $this->serviceResource->_getExecutedQueries()[ 1 ] )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function applyRequestedCollectionQueries(): void {
			$this->serviceCollection->registerQueriesUsingQueryString(
				"with_all_comments=&with_first_comment=&with_first_category="
			)
			                        ->applyRequestedCollectionQueries();
			self::assertEquals(
				CommentsQuery::class,
				get_class( $this->serviceCollection->_getExecutedQueries()[ 0 ] )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function mergeQueriedDataInto(): void {
			$data = [];
			$this->serviceResource->registerQueriesUsingQueryString(
				"with_all_comments=&with_first_comment=&with_first_category="
			)
			                      ->applyRequestedQueries( $this->posts->first() )
			                      ->mergeQueriedDataInto( $data );
			self::assertEquals(
				[
					'first_comment'  => CommentResource::make( $this->posts->first()->comments()->limit( 1 )->first() ),
					'first_category' => CategoryResource::make(
						$this->posts->first()
						            ->categories()
						            ->limit( 1 )
						            ->first()
					)
				],
				$data
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function getQueriedData(): void {
			$data = $this->serviceResource->registerQueriesUsingQueryString(
				"with_all_comments=&with_first_comment=&with_first_category="
			)
			                              ->applyRequestedQueries( $this->posts->first() )
			                              ->getQueriedData();
			self::assertEquals(
				[
					'first_comment'  => CommentResource::make( $this->posts->first()->comments()->limit( 1 )->first() ),
					'first_category' => CategoryResource::make(
						$this->posts->first()
						            ->categories()
						            ->limit( 1 )
						            ->first()
					)
				],
				$data
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function mergeCollectionQueriedData(): void {
			$this->serviceCollection->registerQueriesUsingQueryString(
				"with_all_comments=&with_first_comment=&with_first_category="
			)
			                        ->applyRequestedCollectionQueries()
			                        ->mergeCollectionQueriedData();
			$ids = $this->posts->map( fn( $value ) => [ 'id' => $value->id ] )->flatten();
			self::assertEquals(
				[
					'all_comments' => CommentCollection::make(
						Comment::query()
						       ->whereIn( ( new Post )->getForeignKey(), $ids )
						       ->get()
					),
				],
				$this->collection->additional
			);
		}

	}