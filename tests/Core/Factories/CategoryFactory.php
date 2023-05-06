<?php

	namespace Hans\Tests\Valravn\Core\Factories;

	use Hans\Tests\Valravn\Core\Models\Category;
	use Illuminate\Database\Eloquent\Factories\Factory;

	class CategoryFactory extends Factory {

		protected $model = Category::class;

		/**
		 * @return mixed[]
		 */
		public function definition() {
			return [
				'name' => $this->faker->name()
			];
		}
	}