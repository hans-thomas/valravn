<?php

    namespace Hans\Valravn\Http\Resources\Extentions\Crawler;

    use Hans\Valravn\Http\Resources\Contracts\ValravnJsonResource;
    use Illuminate\Database\Eloquent\Model;

    class CrawlerResource extends ValravnJsonResource {

        /**
         * Extract attributes of the given resource
         * if null returned, the parent::toArray method called by default
         *
         * @param Model|array $model
         *
         * @return array|null
         */
        public function extract( Model|array $model ): ?array {
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
