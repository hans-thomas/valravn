<?php

namespace Hans\Valravn\DTOs;

    use Hans\Valravn\DTOs\Contracts\Dto;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Collection;

    class ManyToManyDto extends Dto
    {
        /**
         * Process the received data.
         *
         * @param array $data
         *
         * @return Collection
         */
        protected function parse(array $data): Collection
        {
            $output = [];
            if (!isset($data['related'])) {
                return collect();
            }
            $data['related'] = $data['related'] instanceof Collection ? $data['related']->toArray() : $data['related'];
            foreach (array_reverse($data['related']) as $item) {
                if (
                    in_array($item['id'], $output) or
                    in_array(
                        $item['id'],
                        array_keys(Arr::where($output, fn ($value, $key) => is_array($value)))
                    )
                ) {
                    continue;
                }
                if (isset($item['pivot'])) {
                    $output[$item['id']] = $item['pivot'];
                } else {
                    $output[] = $item['id'];
                }
            }

            return collect($output)->reverse();
        }

        /**
         * Add given values to the data.
         *
         * @param array $values
         * @param bool  $force
         *
         * @return Collection
         */
        public function withValues(array $values, bool $force = false): Collection
        {
            $output = collect();
            foreach ($this->data as $key => $value) {
                if (is_array($value)) {
                    $output->put(
                        $key,
                        $force ?
                            array_merge($this->data[$key], $values) :
                            array_merge($values, $this->data[$key])
                    );
                }
                if (is_int($value)) {
                    $output->put($value, $values);
                }
            }

            return $this->data = $output;
        }
    }
