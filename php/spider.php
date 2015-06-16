<?php
	
	function __autoload($class) {
		require_once('classes/libs/' . $class . '.class.php');
	}
	
	require_once('classes/Spider.class.php');
	require_once('delegates/KSDelegate.class.php');
	
	// usage
	if ($argc <= 1 || $argv[1] == '-h' || $argv[1] == '--help') {
		include('help.php');
		exit;
	}
	
	// domain
	$domain = $argv[1];
	
	// entrance
	$entrance = $argc > 2 ? $argv[2] : 'http://www.' . $domain;
	
	// output dir
	$output_dir = getcwd() . '/output/' . $domain . '/';
	
	echo ">> tracking keywords in domain: $domain, start from: $entrance, save results in dir:$output_dir\n";
	
	//
	// main
	//
	
	$spider = new Spider($domain);
	
	// use 'Keywords Spider Delegate'
	$spider->delegate = new KSDelegate($output_dir);
	
	$spider->start($entrance);
	
	
	
	echo ">> mission accomplished!\n";
	
