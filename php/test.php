<?php
	
	function __autoload($class) {
		require_once('classes/libs/' . $class . '.class.php');
	}
	
	
	function test_url() {
		
		$str = 'http://user:pass@host:80/dirs/filename.ext?a=1&b=2&c=3#fragment';
		
		$url = new URL($str);
		echo "$str -> \n$url\n";
	}
	
	function test_dictionary() {
		
		$dict = new Dictionary();
		
		$dict->a = 1;
		$dict->b = 2;
		$dict->c = 3;
		
		echo "dict: $dict\n";
		
		$dict['c'] = null;
		
		$dict[18] = 28;
		$dict[28] = 38;
		
		echo "dict: $dict\n";
		
		foreach($dict as $key => $value) {
			echo "$key => $value\n";
		}
		
		echo "bb: \n";
		echo $dict->b . "\n";
		echo $dict['b'] . "\n";
		
		echo "cc: \n";
		echo $dict->c . "\n";
		echo $dict['c'] . "\n";
		
		echo "8: \n";
		echo $dict[8] . "\n";
		
		echo "18: \n";
		echo $dict[18] . "\n";
		
		echo 'count: ' . count($dict);
		
		echo "\n";
	}
	
	function test_dos() {
		
		DOS::mkdir('a/b');
	}
	
	function test_string() {
		$str = new String();
		echo "str: $str\n";
	}
	
	function test_http() {
		
		$url = 'http://baby.sina.com.cn/health/15/1105/2015-05-11/1009/0750296055.shtml';
//		$content = HTTP::request($url);
//		
//		$html = new HTML($content);
//		$charset = $html->charset();
//		echo "charset: $charset\n";
//		
//		$charset = $html->standard_charset($charset);
//		echo "charset: $charset\n";
		
		$html = HTTP::requestHTML($url);
		echo "html: $html\n";
	}
	
	function test_html() {
		
		$url = 'http://baby.sina.com.cn/health/15/1105/2015-05-11/1009/0750296055.shtml';
		$html = HTTP::requestHTML($url);
		
		$html = new HTML($html);
		
		$array = null;
		$meta = $html->fetch_element_with_attribute('meta', 'name', 'keywords');
		if ($meta) {
			$content = $meta->fetch_attribute('content');
			if ($content) {
				$array = explode(',', $content);
			}
		}
		echo 'keywords: ';
		var_dump($array);
		
	}
	
//	test_url();
	test_dictionary();
//	test_dos();
//	test_string();
	
	
//	test_http();
//	test_html();
	
	
