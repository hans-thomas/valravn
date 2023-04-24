<?php

    namespace Hans\Valravn\Http\Resources\Extentions\Crawler;

    use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
    use Illuminate\Database\Eloquent\Model;

    class CrawlerResource extends BaseJsonResource {

        /**
         * Extract attributes of the given resource
         * if null returned, the parent::toArray method called by default
         *
         * @param Model|array $model
         *
         * @return array|null
         */
        public function extract( Model|array $model ): ?array {
            // TODO: Error possibility around Model $model parameter
            $data = optional( $model );

            return [
                'image'     => $data[ 'image' ],
                'image_alt' => $data[ 'image_alt' ],
                'tags'      => $data[ 'tags' ],
                'caption'   => $data[ 'caption' ],
            ];
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
