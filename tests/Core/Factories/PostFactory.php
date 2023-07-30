<?php

namespace Hans\Valravn\Tests\Core\Factories;

    use Hans\Valravn\Tests\Core\Models\Post;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class PostFactory extends Factory
    {
        protected $model = Post::class;

        /**
         * @return array
         */
        public function definition()
        {
            return [
                'title'   => $this->faker->sentence(),
                'content' => $this->faker->paragraph(),
            ];
        }
    }
