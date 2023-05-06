<?php

	namespace Hans\Tests\Valravn\Feature\Models;

	use Hans\Tests\Valravn\Core\Factories\PostFactory;
	use Hans\Tests\Valravn\Instances\Models\AliasForModelAttributesModel;
	use Hans\Tests\Valravn\TestCase;
	use Illuminate\Database\Eloquent\Model;

	class BaseModelTest extends TestCase {

		private Model $model;

		protected function setUp(): void {
			parent::setUp();
			$post        = PostFactory::new()->create();
			$this->model = AliasForModelAttributesModel::query()->make( [
				'content' => fake()->paragraph()
			] );
			$this->model->post()->associate( $post );
			$this->model->save();
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function aliasForModelAttributesOnGettingAlias(): void {
			self::assertEquals(
				$this->model->post_id,
				$this->model->its_post_id
			);
		}

		/**
		 * @test
		 *
		 * @return void
		 */
		public function aliasForModelAttributesOnUpdatingAlias(): void {
			$post = PostFactory::new()->create();
			$this->model->update( [
				'its_post_id' => $post->id,
			] );

			self::assertEquals(
				$this->model->post_id,
				$post->id
			);
		}

	}