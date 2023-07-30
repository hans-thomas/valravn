<?php

namespace Hans\Valravn\Tests\Core\Resources\Post;

    use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
    use Hans\Valravn\Tests\Instances\Http\Includes\CategoriesIncludes;
    use Hans\Valravn\Tests\Instances\Http\Includes\CommentsIncludes;
    use Hans\Valravn\Tests\Instances\Http\Queries\FirstCategoryQuery;
    use Hans\Valravn\Tests\Instances\Http\Queries\FirstCommentQuery;
    use Illuminate\Database\Eloquent\Model;

    class PostResource extends ValravnJsonResource
    {
        /**
         * List of available queries of this resource.
         *
         * @return array
         */
        public function getAvailableQueries(): array
        {
            return [
                'with_first_comment'  => FirstCommentQuery::class,
                'with_first_category' => FirstCategoryQuery::class,
            ];
        }

        /**
         * List of available includes of this resource.
         *
         * @return array
         */
        public function getAvailableIncludes(): array
        {
            return [
                'comments'   => CommentsIncludes::class,
                'categories' => CategoriesIncludes::class,
            ];
        }

        /**
         * @param Model $model
         *
         * @return array|null
         */
        public function extract(Model $model): ?array
        {
            return [
                'id'      => $model->id,
                'title'   => $model->title,
                'content' => $model->content,
            ];
        }

        /**
         * @return string
         */
        public function type(): string
        {
            return 'posts';
        }

        public function withFirstCommentQuery(): self
        {
            $this->registerQuery(FirstCommentQuery::class);

            return $this;
        }

        public function withCommentsIncludes(): self
        {
            $this->registerInclude(CommentsIncludes::class);

            return $this;
        }
    }
