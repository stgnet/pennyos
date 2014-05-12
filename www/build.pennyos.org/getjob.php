<?php

	if (empty($_SERVER['HTTP_X_AGENT_ID']))
	{
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$agentid=$_SERVER['HTTP_X_AGENT_ID'];
	$ip=$_SERVER['REMOTE_ADDR'];

	$content='';
	$jobid='';

	require 'projects.php';
	foreach ($projects as $pdir => $project)
	{
		foreach ($project['steps'] as $sdir => $step)
		{
			if ($step['status'] == "waiting")
			{
				// rename it to assign it
				$original_path=$pdir.'/'.$sdir;
				$assigned_path=$pdir.'/'.$sdir.'-'.$agentid.'-'.$ip;
				if (!file_exists($original_path.'/step_launch.sh')) continue;
				if (!is_dir($original_path)) continue;
				rename($original_path,$assigned_path);
				if (!is_dir($assigned_path)) continue;
				$content=file_get_contents($assigned_path.'/step_launch.sh');
				$jobid=$original_path;
				break;
			}
		}
		if ($content) break;
	}

	
	if (!$content || !$jobid)
	{
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$md5=md5($content);

	header("X-Content-MD5: $md5");
	header("X-Job-ID: $jobid");
	header('Content-type: text/plain');
	echo $content;

