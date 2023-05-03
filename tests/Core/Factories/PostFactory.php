<?php

	namespace Hans\Tests\Valravn\Core\Factories;

	use Hans\Tests\Valravn\Core\Models\Post;
	use Illuminate\Database\Eloquent\Factories\Factory;

	class PostFactory extends Factory {
		protected $model = Post::class;

		/**
		 * @return array
		 */
		public function definition() {
			return [
				'title'   => $this->faker->sentence(),
				'content' => $this->faker->paragraph(),
			];
		}
	}