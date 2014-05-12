<?php
	// detailed list of projects and steps
	// scan and update cache

	// scan directory for any new or updated projects
	$p=dir('.');
	while ($proj=$p->read())
	{
		if ($proj[0]!='p') continue;
		if (!is_dir($proj)) continue;
		$json_file=$proj.'/project.json';
		if (!is_file($json_file)) continue;

		echo $proj.' mtime='.filemtime($proj);
		echo ' ctime='.filectime($proj);
		echo ' atime='.fileatime($proj);
		echo "\n";
	}
	$p->close();

