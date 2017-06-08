<?php
	
	require_once('libs/Log.class.php');
	require_once('libs/Object.class.php');
	require_once('libs/URL.class.php');
	require_once('libs/HTTP.class.php');
	
	
	/**
	 *
	 *  Delegate to process each page for the general spider
	 *
	 */
	interface ISpiderDelegate {
		
		//
		//  process HTML data from the URL, and return all new links in it
		//
		public function process($html, $url);
		
	}
	
	
	/**
	 *
	 *  General Spider to crawling all pages in the single domain
	 *
	 */
	class Spider extends Object {
		
		protected $domain = 'beva.com';
		
		public $interval = 1000; /* microseconds */
		
		public $delegate = null;
		
		private $url_pool = [];
		private $url_index = 0;
		
		public function __construct($domain) {
			parent::__construct();
			
			$this->domain = $domain;
		}
		
		//
		//  add new URL for next crawling task
		//
		protected function addURL($url) {
			// checking domain
			$host = (new URL($url))->host;
			$domain = $this->domain;
			if (stripos($host, $domain) === false) {
				//Log::info("only accessing domain: $domain, ignore url: $url");
				return false;
			}
			
			// checking reduplicated URL
			if (in_array($url, $this->url_pool)) {
				//Log::info("reduplicated url: $url");
				return false;
			}
			
			// add new URL in the domain
			array_push($this->url_pool, $url);
			return true;
		}
		
		//
		//  get next URL for crawling
		//
		protected function nextURL() {
			if ($this->url_index >= count($this->url_pool)) {
				return null;
			}
			$url = $this->url_pool[$this->url_index];
			$this->url_index += 1;
			return $url;
		}
		
		//
		//  start crawling from entrance URL
		//
		public function start($entrance) {
			if ($this->delegate == null) {
				Log::error("delegate not set yet!");
				return;
			}
			
			$this->url_pool = [$entrance];
			
			Log::info("mission start: $entrance");
			
			for (; $url = $this->nextURL(); usleep($this->interval)) {
				Log::info('==== Requesting (' . $this->url_index . '/' . count($this->url_pool) . '): ' . $url . ' ...');
				$html = HTTP::requestHTML($url);
				
				// process html data to collect urls
				$urls = $this->delegate->process($html, $url);
				if (!$urls || count($urls) == 0) {
					Log::warning("no urls found in url: $url");
					continue;
				}
				
				// add new links to url pool
				foreach ($urls as $href) {
					$this->addURL($href);
				}
			}
			
			Log::info("mission accomplished!");
		}
		
	}
	
