

# General Spider
It's a general spider for crawling all web pages in a single domain.

## Example
An example to crawling all pages in the single domain, and fetch all keywords.

It has only two steps:
### 1. delegate
create 'delegates/MyDelegate.class.php':
```php
	class MyDelegate implements ISpiderDelegate {
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
```
### 2. spider
create 'spider.php' and use your delegate:
```php
	require_once('classes/Spider.class.php');
	require_once('delegates/MyDelegate.class.php');
	
	// 1. create a general spider
	$spider = new Spider($domain);
	
	// 2. set your delegate
	$spider->delegate = new MyDelegate();
	
	// 3. start crawling
	$spider->start($entrance);
```

Any suggestion will be appreciated.
-- <albert.moky@gmail.com>
