<?php

	namespace Hans\Valravn\Tests\Feature\Http\Resources;

	use Hans\Valravn\Tests\Core\Factories\CommentFactory;
	use Hans\Valravn\Tests\Core\Factories\PostFactory;
	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Tests\Core\Resources\Post\PostCollection;
	use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
	use Hans\Valravn\Tests\TestCase;

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

		/**
		 * @test
		 *
		 * @return void
		 */
		public function queriesThroughApi(): void {
			$content = $this->get( "/queries/posts/{$this->post->id}?with_first_comment" )
			                ->json();
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
				$content
			);
		}

	}