<?php
	require '.passwd.php';

	if ($_SERVER['REQUEST_METHOD']=="GET")
	{
		die('Wrong usage');
	}

	file_put_contents("/tmp/test",print_r($GLOBALS,true));
	exit(0);

	// double check that we had successful upload first
	if (empty($_FILES)) die('unable to find uploaded file');
	if (empty($_FILES['userfile'])) die('uploaded file with wrong tag - use userfile');
	$upload=$_FILES['userfile'];
	if (empty($upload['name']) || empty($upload['tmp_name']) || empty($upload['size'])) die('Unepected error');
	if ($upload['error']!= 0) die('Upload error occurred: '.print_r($upload,true));

	$last_project_number=`ls -1 |grep ^p |cut -c 2- |sort -n |tail -1`;
	$project_dir='p'.($last_project_number+1);
	if (is_dir($project_dir)) die('emergawdz');
	if (!mkdir($project_dir)) die('Unable to mkdir '.$project_dir);
	if (!is_dir($project_dir)) die('lolcatz');

	$project_url='http://'.$_SERVER['HTTP_HOST'].'/'.$project_dir;

	// create an initial description in case uploaded project doesn't change it
	$info=array('name' => $upload['name'],'description' => 'new project');
	file_put_contents($project_dir."/project.json",json_encode($info));
	
	chdir($project_dir);
	if (!move_uploaded_file($upload['tmp_name'],$upload['name']))
		die('Failure to move uploaded file');

	header('Content-type: text/plain');

	echo 'Created project '.$project_dir.' and uploaded '.$upload['name']."\n";

	if ($upload['type']=='application/octet-stream')
	{
		// try to guess file type
		$extension=pathinfo($upload['name'],PATHINFO_EXTENSION);
		switch ($extension)
		{
		case 'sh':
			$upload['type']='application/x-shellscript';
			break;
		case 'tgz':
			$upload['type']='application/x-compressed-tar';
			break;
		default:
			// try reading file to get a clue
			$fp=fopen($upload['name'],'r');
			$first3bytes=fread($fp,3);
			if ($first3bytes=='#!/')
			{
				$upload['type']='application/x-shellscript';
			}
			break;
		}
	}

	switch ($upload['type'])
	{
	case 'application/x-shellscript':
		echo 'Executing script '.$upload['name']."\n";
		passthru('sh '.$upload['name'].' '.$project_url);
		break;
	case 'application/x-compressed-tar':
		echo 'Extracting archive '.$upload['name']."\n";
		passthru('tar xvfz '.$upload['name']);
		if (file_exists('project_launch.sh'))
		{
			echo 'Executing launch script'."\n";
			passthru("sh project_launch.sh $project_url");
		}
		else
		{
			echo 'Don\'t know how to launch project!'."\n";
			exit;
		}
		break;
	default: 
		echo 'Unable to start unknown file type '.$upload['type']."\n";
		exit;
	}

	echo '### PROJECT ADDED ###'."\n";
	exit;
