

# General Spider
It's a general spider for crawling all web pages in a single domain.

## Example
Here is an example to crawling all pages in the single domain, and fetch all keywords.

You can write your own spider now, it has only two steps:

### 1. Write your own data handler as a delegate
> [delegates/MyDelegate.class.php](https://github.com/moky/Spider/blob/master/php/delegates/KSDelegate.class.php "MyDelegate.class.php")

```php
require_once('LinkCollector.class.php');

class MyDelegate implements ISpiderDelegate {
    //
    //  general spider interface
    //
    public function process($html, $url) {
        // 1. process html data
        // TODO: do what you want to do with the HTML data
        
        // 2. return new links
        // here you can use your own collector to get links from the HTML data or somewhere
        $collector = new LinkCollector($html, $url);
        return $collector->links();
    }
}
```

### 2. Use the general spider to crawl website for your data handler
> [spider.php](https://github.com/moky/Spider/blob/master/php/spider.php "spider.php")

```php
require_once('classes/Spider.class.php');
require_once('delegates/MyDelegate.class.php');

// 1. create a general spider
$spider = new Spider($domain);

// 2. set your delegate to process data
$spider->delegate = new MyDelegate();

// 3. start crawling from the entrance URL
$spider->start($entrance);
```

Any suggestion will be appreciated.
-- <albert.moky@gmail.com>
