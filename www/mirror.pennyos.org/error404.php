<?php
	$path=urldecode($_SERVER['REQUEST_URI']);

	//if (substr($path,0,9)!="/PennyOS/")
	{
		mail("scott@stg.net","ERROR 404 mirror.pennyos.org - invalid path $path",print_r($_SERVER,true));
		//header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
		echo "<h1>404 File not found</h1>";
		exit;
	}

	$url="http://mirror.centos.org/centos/".substr($path,9);
	$path=substr($path,1);
	
	$contents=file_get_contents($url);
	if (!$contents)
	{
		mail("scott@stg.net","ERROR 404 mirror.pennyos.org - empty url $url",print_r($_SERVER,true));
		header("Status: 404 Not Found");
		echo "<h1>404 File not found</h1>";
		exit;
	}

	//chdir("/var/www/mirror.pennyos.org");
	$dir=dirname($path);
	if (!is_dir($dir))
		mkdir($dir,0755,true);

	if (!is_dir($dir))
	{
		mail("scott@stg.net","ERROR 404 mirror.pennyos.org - got file but failed to mkdir $dir",print_r($_SERVER,true));
		header("Status: 404 Not Found");
		echo "<h1>404 File not found</h1>";
		exit;
	}

	file_put_contents($path,$contents);
	if (!is_file($path))
	{
		mail("scott@stg.net","ERROR 404 mirror.pennyos.org - got file but failed to save $path",print_r($_SERVER,true));
		header("Status: 404 Not Found");
		echo "<h1>404 File not found</h1>";
		exit;
	}
	
	mail("scott@stg.net","ERROR 404 mirror.pennyos.org - got file $path",print_r($_SERVER,true));
	header('Location: '.$_SERVER['REQUEST_URI']);
	exit;
		header("Status: 404 Not Found");
		echo "<h1>404 File not found</h1>";

