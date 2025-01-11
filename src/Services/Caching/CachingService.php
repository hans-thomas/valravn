<?php

namespace Hans\Valravn\Services\Caching;

use BadMethodCallException;
use Hans\Valravn\Services\Contracts\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class CachingService
{
    /**
     * Start time of the algorithm.
     *
     * @var int
     */
    private const genesisTS = 919542600;

    /**
     * Service instance that results should cache.
     *
     * @var Service
     */
    private Service $service;

    /**
     * Default interval.
     *
     * @var int
     */
    private int $interval = 15;

    public function __construct(
        private readonly Request $request
    ) {
    }

    /**
     * Cache the given data.
     *
     * @param string $key
     * @param mixed  $data
     *
     * @return mixed
     */
    public function store(string $key, callable $data): mixed
    {
        return $this->remember('cache', [$key], $data);
    }

    /**
     * Cache the returned data from callback.
     *
     * @param string   $method
     * @param array    $params
     * @param callable $callback
     *
     * @return mixed
     */
    protected function remember(string $method, array $params, callable $callback): mixed
    {
        return Cache::remember(
            $this->makeCachingKey($method, $params),
            $this->calcTtlTime(),
            $callback
        );
    }

    /**
     * Create a key for caching data.
     *
     * @param string $method
     * @param array  $params
     *
     * @return string
     */
    protected function makeCachingKey(string $method, array $params): string
    {
        $keys = null;
        foreach ($params as $param) {
            if ($param instanceof Model) {
                $keys[] = get_class($param)."($param->id)";
            } elseif (is_object($param)) {
                $keys[] = get_class($param);
            } elseif (is_bool($param)) {
                $keys[] = $param ? 'true' : 'false';
            } else {
                $keys[] = $param;
            }
        }
        $key = implode(',', Arr::wrap($keys));
        $service_class = isset($this->service) ? get_class($this->service) : 'given-data:'.md5($key);

        return "$service_class:$method:($key)[{$this->request->getQueryString()}]";
    }

    /**
     * Calculate the time until next interval.
     *
     * @return int
     */
    protected function calcTtlTime(): int
    {
        $ttl = $this->getInterval() * 60;
        $m = (now()->getTimestamp() - self::genesisTS) / $ttl;

        return ((ceil($m) - $m) * $ttl) ?: $ttl;
    }

    /**
     * Return interval.
     *
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * Set interval in minute.
     *
     * @param int $minutes
     *
     * @return self
     */
    public function setInterval(int $minutes): self
    {
        $this->interval = $minutes;

        return $this;
    }

    /**
     * Set service instance to cache its result.
     *
     * @param Service $service
     *
     * @return $this
     */
    public function setService(Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function __call(string $method, array $params)
    {
        if (method_exists($this->service, $method)) {
            return $this->remember(
                $method,
                $params,
                fn () => $this->service->{$method}(...$params)
            );
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
