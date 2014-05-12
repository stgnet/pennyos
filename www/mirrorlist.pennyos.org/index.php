<?php
	header('Content-type: text/plain');

	unset $_SERVER['PATH'];
	unset $_SERVER['SERVER_SIGNATURE'];
	unset $_SERVER['SERVER_SOFTWARE'];

	mail("scott@stg.net","mirrorlist.pennyos.org",print_r($_GET,true).print_r($_SERVER,true));

	if (empty($_GET['release'])) die("# ERROR: no release\n");
	if (empty($_GET['arch'])) die("# ERROR: no arch\n");
	if (empty($_GET['repo'])) die("# ERROR: no repo\n");

	$release=$_GET['release'];
	$arch=$_GET['arch'];
	$repo=$_GET['repo'];


	$path="PennyOS/$release/$repo/$arch";

	if (!is_dir("../mirror.pennyos.org/$path"))
	{
		echo "# ERROR: invalid request or no mirrors found\n";
		exit;
	}

	echo "http://mirror.pennyos.org/PennyOS/$release/$repo/$arch/\n";

