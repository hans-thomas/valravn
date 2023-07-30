<?php

namespace Hans\Valravn\Tests\Core\Factories;

    use Hans\Valravn\Tests\Core\Models\Comment;
    use Illuminate\Database\Eloquent\Factories\Factory;

    class CommentFactory extends Factory
    {
        protected $model = Comment::class;

        /**
         * @return array
         */
        public function definition()
        {
            return [
                'content' => $this->faker->paragraph(),
            ];
        }
    }
