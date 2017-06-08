

# General Spider
It's a general spider for crawling all web pages in a single domain.

## Example
An example to crawling all pages in the single domain, and fetch all keywords.

It has only two steps:
### 1. delegate
create 'delegates/KSDelegate.class.php':
<pre><code>
	class KSDelegate implements ISpiderDelegate {
		//
		//  general spider interface
		//
		public function process($html, $url) {
			// 1. process html data
			// TODO: do what you want to do with the HTML data
			
			// 2. return new links
			return $links;
		}
	}
</code></pre>
### 2. spider
clreate 'spider.php':
<pre><code>
	require_once('classes/Spider.class.php');
	require_once('delegates/KSDelegate.class.php');
	
	// 1. create a general spider
	$spider = new Spider($domain);
	
	// 2. set delegate
	$spider->delegate = new KSDelegate();
	
	// 3. start crawling
	$spider->start($entrance);
</code></pre>

Any suggestion will be appreciated.
-- <albert.moky@gmail.com>

<!-- highlight codes begin -->
<link href="http://cdn.bootcss.com/highlight.js/8.0/styles/monokai_sublime.min.css" rel="stylesheet">  
<script src="http://cdn.bootcss.com/highlight.js/8.0/highlight.min.js"></script>
<script language="javascript">hljs.initHighlightingOnLoad();</script>
<!-- highlight codes end -->
