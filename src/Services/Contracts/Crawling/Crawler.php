<?php

	namespace Hans\Valravn\Services\Contracts\Crawling;

	use Hans\Valravn\Http\Resources\Contracts\BaseJsonResource;
	use Hans\Valravn\Http\Resources\Contracts\BaseResourceCollection;
	use Hans\Valravn\Http\Resources\Extentions\Crawler\CrawlerCollection;
	use GuzzleHttp\Exception\RequestException;
	use Psr\Http\Message\UriInterface;
	use Spatie\Crawler\CrawlObservers\CrawlObserver;

	abstract class Crawler extends CrawlObserver {

		/**
		 * Store Crawled content
		 *
		 * @var array
		 */
		protected array $content = [];

		/**
		 * Additional data that showed in crawler resource class
		 *
		 * @var array
		 */
		protected array $additional = [];

		/**
		 * Called when the crawler had a problem crawling the given url.
		 *
		 * @param UriInterface      $url
		 * @param RequestException  $requestException
		 * @param UriInterface|null $foundOnUrl
		 */
		public function crawlFailed( UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null ): void {
			throw $requestException;
		}

		/**
		 * Return crawled content
		 *
		 * @return array
		 */
		public function getContent(): array {
			return $this->content;
		}

		/**
		 * Make a response using crawled data
		 *
		 * @return BaseResourceCollection|BaseJsonResource
		 */
		public function toResponse(): BaseResourceCollection|BaseJsonResource {
			return CrawlerCollection::make( $this->getContent() )->addAdditional( $this->additional );
		}

		/**
		 * Add given data to the additional
		 *
		 * @param array $data
		 *
		 * @return $this
		 */public function additional( array $data ): self {
			$this->additional = $data;

			return $this;
		}

		/**
		 * The domain that crawler runs on
		 *
		 * @return string
		 */
		abstract public function getDomain(): string;

	}
