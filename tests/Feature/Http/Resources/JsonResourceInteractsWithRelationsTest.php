<?php

	namespace Hans\Valravn\Tests\Feature\Http\Resources;

	use Hans\Valravn\Tests\Core\Factories\CommentFactory;
	use Hans\Valravn\Tests\Core\Factories\PostFactory;
	use Hans\Valravn\Tests\Core\Models\Post;
	use Hans\Valravn\Tests\Core\Resources\Category\CategoryCollection;
	use Hans\Valravn\Tests\Core\Resources\Comment\CommentCollection;
	use Hans\Valravn\Tests\Core\Resources\Post\PostResource;
	use Hans\Valravn\Tests\TestCase;

	class JsonResourceInteractsWithRelationsTest extends TestCase {

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
		public function resolveRelationsUsing(): void {
			$this->post->loadMissing( 'comments' );

			self::assertEquals(
				[
					'data' => [
						'id'       => $this->post->id,
						'title'    => $this->post->title,
						'content'  => $this->post->content,
						'comments' => CommentCollection::make( $this->post->comments )->toArray( request() ),
						'type'     => 'posts'
					],
					'type' => 'posts'
				],
				$this->resourceToJson( PostResource::make( $this->post ) )
			);

			self::assertEquals(
				[
					'data' => [
						'id'       => $this->post->id,
						'title'    => $this->post->title,
						'content'  => $this->post->content,
						'comments' => CategoryCollection::make( $this->post->comments )->toArray( request() ),
						'type'     => 'posts'
					],
					'type' => 'posts'
				],
				$this->resourceToJson(
					PostResource::make( $this->post )
					            ->resolveRelationsUsing(
						            [
							            'comments' => CategoryCollection::class
						            ]
					            )
				)
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function skipRelationsForModel(): void {
			$this->post->loadMissing( 'comments' );

			self::assertEquals(
				[
					'data' => [
						'id'       => $this->post->id,
						'title'    => $this->post->title,
						'content'  => $this->post->content,
						'comments' => CommentCollection::make( $this->post->comments )->toArray( request() ),
						'type'     => 'posts'
					],
					'type' => 'posts'
				],
				$this->resourceToJson( PostResource::make( $this->post ) )
			);

			self::assertEquals(
				[
					'data' => [
						'id'      => $this->post->id,
						'title'   => $this->post->title,
						'content' => $this->post->content,
						'type'    => 'posts'
					],
					'type' => 'posts'
				],
				$this->resourceToJson(
					PostResource::make( $this->post )
					            ->skipRelationsForModel(
						            [
							            Post::class => 'comments'
						            ]
					            )
				)
			);
		}

	}