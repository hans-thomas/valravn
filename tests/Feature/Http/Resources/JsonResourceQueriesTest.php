<?php

	namespace Hans\Tests\Valravn\Feature\Http\Resources;

	use Hans\Tests\Valravn\Core\Factories\CommentFactory;
	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Core\Resources\Post\PostResource;
	use Hans\Tests\Valravn\TestCase;

	class JsonResourceQueriesTest extends TestCase {

		private Post $post;

		/**
		 * @return void
		 */
		protected function setUp(): void {
			parent::setUp();
			$this->post = PostFactory::new()->has( CommentFactory::new()->count( 5 ) )->create();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function queries(): void {
			$resource = PostResource::make( $this->post )->withFirstCommentQuery();
			self::assertEquals(
				[
					'data' => [
						'type'          => 'posts',
						'id'            => $this->post->id,
						'title'         => $this->post->title,
						'content'       => $this->post->content,
						'first_comment' => [
							'type'    => 'comments',
							'id'      => ( $comment = $this->post->comments()->limit( 1 )->first() )->id,
							'content' => $comment->content,
						]
					],
					'type' => 'posts',
				],
				$this->resourceToJson( $resource )
			);
		}

	}