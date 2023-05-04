<?php

	namespace Hans\Tests\Valravn\Feature\Http\Resources;

	use Hans\Tests\Valravn\Core\Factories\CommentFactory;
	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Core\Models\Comment;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Core\Resources\Post\PostCollection;
	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Support\Collection;

	class JsonResourceCollectionQueryTest extends TestCase {
		private Collection $posts;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->posts = PostFactory::new()->count( 3 )->has( CommentFactory::new()->count( 5 ) )->create();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function collection(): void {
			$resource = PostCollection::make( $this->posts )->withAllCommentsQuery();
			$comments = $this->posts->map( fn( Post $post ) => $post->comments )->flatten();

			self::assertEquals(
				[
					'data'         => $this->posts->map(
						fn( Post $post ) => [
							'type'    => 'posts',
							'id'      => $post->id,
							'title'   => $post->title,
							'content' => $post->content,
						]
					)->toArray(),
					'type'         => 'posts',
					'all-comments' =>
						$comments->map(
							fn( Comment $comment ) => [
								'type'    => 'comments',
								'id'      => $comment->id,
								'content' => $comment->content,
							]
						)
						         ->toArray()
				],
				$this->resourceToJson( $resource )
			);
		}

	}