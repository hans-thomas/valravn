<?php

    namespace Hans\Valravn\Http\Resources\Extentions\Crawler;

    use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
    use Illuminate\Database\Eloquent\Model;

    class CrawlerCollection extends BaseResourceCollection {

        /**
         * Extract attributes of the given resource
         * if null returned, the parent::toArray method called by default
         *
         * @param Model|array $model
         *
         * @return array|null
         */
        public function extract( Model|array $model ): ?array {
            return null;
        }

        /**
         * Specify the type of your resource
         *
         * @return string
         */
        public function type(): string {
            return 'crawler';
        }
    }
