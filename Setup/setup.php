#!/usr/bin/php
<?php

/*
 * this script tries to create an easy to handle installation
 * it creates the directories, sets externals and commits
 */

function secho($output){
	echo $output.PHP_EOL;
}

function tryToCreateDirectory($path){
	if(true === is_dir($path)){
		secho($path.' already exists. nothing to do');
		return true;
	}
	return mkdir($path, 0755, true);
}

function debug(){
	foreach(func_get_args() as $current){
		print_r($current);
		var_dump($current);
	}
}

$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];

if(1 === $argc){
	echo 'please provide the path where you want the wolxXxMVC to be installed: ';
	$handler = fopen('php://stdin', 'r');
	$path = fread($handler, 1024);
	fclose($handler);
	$path = trim($path);
	$path = rtrim($path, '/ ');
}else{
	$path = $argv[1];
}



$neededDirectories = array(
	'application',
	'application/controllers/',
	'application/migrations/',
	'application/config/',
	'application/controllers/'
);

foreach($neededDirectories as $current){
	tryToCreateDirectory($current);
}


var_dump($_SERVER['argc'], $_SERVER['argv']);

secho('au revoir');

exit(1);
