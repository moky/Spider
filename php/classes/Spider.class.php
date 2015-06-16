<?php
	
	require_once('libs/Log.class.php');
	require_once('libs/URL.class.php');
	require_once('libs/HTTP.class.php');
	
	interface ISpiderDelegate {
		/**
		 *
		 *  process html data to get info, return all links
		 *
		 */
		public function process($html, $url);
	}
	
	class Spider {
		
		var $domain = 'beva.com';
		
		var $interval = 1000; /* microseconds */
		
		var $delegate = null;
		
		// protected:
		protected $url_pool = [];
		protected $url_index = 0;
		
		function __construct($domain) {
			$this->domain = $domain;
		}
		
		function addURL($url) {
			$host = (new URL($url))->host;
			$domain = $this->domain;
			if (stripos($host, $domain) === false) {
				//Log::info("only accessing domain: $domain, ignore url: $url");
				return false;
			}
			if (in_array($url, $this->url_pool)) {
				//Log::info("reduplicated url: $url");
				return false;
			}
			
			array_push($this->url_pool, $url);
			return true;
		}
		
		function nextURL() {
			if ($this->url_index >= count($this->url_pool)) {
				return null;
			}
			$url = $this->url_pool[$this->url_index];
			$this->url_index += 1;
			return $url;
		}
		
		//
		// main
		//
		function start($entrance) {
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
				
				// add to url pool
				foreach ($urls as $href) {
					$this->addURL($href);
				}
				
			}
			
			Log::info("mission accomplished!");
		}
		
	}
	
	
