<?php
	require 'header.php';

	require 'projects.php';

	echo '<div class="container-fluid">';

	if (!empty($_GET['p']))
	{
		$proj=$_GET['p'];
		if (empty($projects[$proj]))
		{
			echo '<h3>No such project exists</h3>';
		}
		else
		{
			echo '<h2>Project: '.$projects[$proj]['name'].'</h2>';
			echo '<div class="row">';
			foreach ($projects[$proj]['steps'] as $job => $step)
			{
				$label='label label-info';
				switch ($step['status'])
				{
				case 'waiting': $label='btn btn-default'; break;
				case 'running': $label='btn btn-warning'; break;
				case 'ok': $label='btn btn-success'; break;
				case 'fail': $label='btn btn-danger'; break;
				}

				echo '<div class="col-md-4">';
				echo '<a href="#" type="button" class="'.$label.'">'.htmlentities($step['name']).'</a>';
				//echo '<p>'.htmlentities($step['description']).'</p>';
				//echo '<p>'.$job.' - Status: '.$step['status'].'</p>';
				echo '</div>';
			}
			echo '</div>';
		}
	}
	else
	{
		echo '<div class="row">';
		foreach ($projects as $proj => $project)
		{
			echo '<div class="col-md-4">';
			echo '<div class="well">';
			echo '<div class="btn-group">';
			echo '<h3>'.htmlentities($project['name']).'&nbsp;&nbsp;';
//			echo '<a href="/?p='.$proj.'" class="btn btn-default" type="button">View</a>';
			echo '</h3>';
			echo '</div>';
			echo '<p>'.htmlentities($project['description']).'</p>';
	
			$total=$project['counts']['total'];
			$ok=(integer)(100*$project['counts']['ok']/$total);
			if ($project['counts']['ok'] && !$ok) $ok=1;
			$fail=(integer)(100*$project['counts']['fail']/$total);
			if ($project['counts']['fail'] && !$fail) $fail=1;
			$running=(integer)(100*$project['counts']['running']/$total);
			if ($project['counts']['running'] && !$running) $running=1;
			echo '<div class="progress">';
			echo '<div class="progress-bar progress-bar-success" style="width: '.$ok.'%">';
//				echo '<span class="sr-only">'.$ok.'% ok</span>';
echo $project['counts']['ok'];
			echo '</div>';
			echo '<div class="progress-bar progress-bar-warning" style="width: '.$running.'%">';
//				echo '<span class="sr-only">'.$running.'% running</span>';
echo $project['counts']['running'];
			echo '</div>';
			echo '<div class="progress-bar progress-bar-danger" style="width: '.$fail.'%">';
//				echo '<span class="sr-only">'.$fail.'% failed</span>';
echo $project['counts']['fail'];
			echo '</div>';
			echo $project['counts']['waiting'].' jobs waiting';
			echo '</div>';
			
			echo '</div>';
			echo '</div>'."\n";
		}
		echo '</div>';
	}

	echo '</div>'."\n";

	require 'footer.php';
