<?php

	namespace Hans\Tests\Valravn\Feature\Http\Resources;

	use Hans\Tests\Valravn\Core\Factories\CommentFactory;
	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Core\Models\Comment;
	use Hans\Tests\Valravn\Core\Models\Post;
	use Hans\Tests\Valravn\Core\Resources\Post\PostResource;
	use Hans\Tests\Valravn\TestCase;

	class JsonResourceIncludesTest extends TestCase {

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
		public function includes(): void {
			$resource = PostResource::make( $this->post )->withCommentsIncludes();
			self::assertEquals(
				[
					'data' => [
						'type'     => 'posts',
						'id'       => $this->post->id,
						'title'    => $this->post->title,
						'content'  => $this->post->content,
						'comments' => $this->post
							->comments
							->map(
								fn( Comment $value ) => [
									'type'    => 'comments',
									'id'      => $value->id,
									'content' => $value->content,
								]
							)
							->toArray()
					],
					'type' => 'posts'
				],
				$this->resourceToJson( $resource )
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function includesThroughApi(): void {
			$content = $this->get( "/posts/{$this->post->id}/includes?includes=comments" )
			                ->json();
			self::assertEquals(
				[
					'data' => [
						'type'     => 'posts',
						'id'       => $this->post->id,
						'title'    => $this->post->title,
						'content'  => $this->post->content,
						'comments' => $this->post
							->comments
							->map(
								fn( Comment $value ) => [
									'type'    => 'comments',
									'id'      => $value->id,
									'content' => $value->content,
								]
							)
							->toArray()
					],
					'type' => 'posts'
				],
				$content
			);
		}


	}