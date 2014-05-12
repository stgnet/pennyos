<?php
	// detailed list of projects and steps
	// scan and update cache

	$cache_file='projects.json';
	if (file_exists($cache_file))
		$projects=json_decode(file_get_contents($cache_file),true);
	else 
		$projects=array();

	$changed=false;

	// scan cached projects for deleted directories
	foreach ($projects as $pdir => $project)
	{
		if (!is_dir($pdir))
			unset($projects[$pdir]);
	}

	// scan directory for any new or updated projects
	$p=dir('.');
	while ($proj=$p->read())
	{
		if ($proj[0]!='p') continue;
		if (!is_dir($proj)) continue;
		$json_file=$proj.'/project.json';
		if (!is_file($json_file)) continue;

		$modified=filectime($proj);

		// does this exist in the array?
		if (array_key_exists($proj,$projects))
		{
			// has it changed?
			if (!empty($projects[$proj]['last']) &&
				$projects[$proj]['last'] == $modified)
			{
				// no, skip this for speed
				continue;
			}
			unset($projects[$proj]);
			$projects[$proj]=array('name'=>"Undefined");
		}
		else
		{
			$projects[$proj]=array('name'=>"Undefined");
		}

		$changed=true;

		$description=json_decode(file_get_contents($json_file),true);
		$projects[$proj]=array_merge($projects[$proj],$description);
		$projects[$proj]['last']=$modified;

		$counts=array('waiting'=>0,'running'=>0,'ok'=>0,'fail'=>0);

		// now scan the steps
		$s=dir($proj);
		while ($step=$s->read())
		{
			$path=$proj.'/'.$step;
			if ($step[0]!='s') continue;
			if (!is_dir($path)) continue;
			$json_file=$path.'/step.json';
			if (!is_file($json_file)) continue;

			// s1234 = available job
			// s1234-123456789abc = job assigned to mac
			// s1234-ok = job completed
			// s1234-fail = job failed
			$pos=strpos($step,'-');
			$dir=$step;
			$status='waiting';
			$assigned='';
			$age=time()-filectime($path);
			
			if ($pos)
			{
				$real_step=substr($step,0,$pos);
				$status=substr($step,$pos+1);
				$dir=$step;
				$step=$real_step;
			}
			if (strlen($status)>=12)
			{
				$assigned=$status;
				$status='running';

				if ($age>12*3600)
				{
					// reset step back to waiting
					$waiting_path=$proj.'/'.$step;
					rename($path,$waiting_path);
					$path=$waiting_path;
					$dir=$step;
					$status='waiting';
				}
			}
			$counts[$status]++;

			if (!array_key_exists('steps',$projects[$proj]))
				$projects[$proj]['steps']=array();
			if (!array_key_exists($step,$projects[$proj]['steps']))
				$projects[$proj]['steps'][$step]=array();

			$description=json_decode(file_get_contents($json_file),true);
			$projects[$proj]['steps'][$step]=array_merge($projects[$proj]['steps'][$step],$description);

			$projects[$proj]['steps'][$step]['dir']=$dir;
			$projects[$proj]['steps'][$step]['status']=$status;
			$projects[$proj]['steps'][$step]['assigned']=$assigned;
			$projects[$proj]['steps'][$step]['age']=$age;
		}
		$s->close();

		$total=0;
		foreach ($counts as $value)
			$total+=$value;
		$counts['total']=$total;

		$projects[$proj]['counts']=$counts;
	}
	$p->close();
	if ($changed)
		file_put_contents($cache_file,json_encode($projects));

