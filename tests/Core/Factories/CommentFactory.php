<?php

	namespace Hans\Tests\Valravn\Core\Factories;

	use Hans\Tests\Valravn\Core\Models\Comment;
	use Illuminate\Database\Eloquent\Factories\Factory;

	class CommentFactory extends Factory {
		protected $model = Comment::class;

		/**
		 * @return array
		 */
		public function definition() {
			return [
				'content' => $this->faker->paragraph(),
			];
		}
	}