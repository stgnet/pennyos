<?php
	function fail($message)
	{
		mail("scott@stg.net","RESULT FAIL: $message",print_r($GLOBALS,true));
		die($message);
	}
	
	header('Content-type: text/plain');

	if ($_SERVER['REQUEST_METHOD']=="GET")
	{
		fail('Wrong usage');
	}

	//if (empty($_SERVER['jobid'])) fail('missing jobid');
	if (empty($_POST['jobid'])) fail('missing jobid'.print_r($_SERVER,true));
	if (empty($_POST['agentid'])) fail('missing agentid');
	if (empty($_POST['status'])) fail('missing status');
	$jobid=$_POST['jobid'];
	$agentid=$_POST['agentid'];
	$status=$_POST['status'];
	$ip=$_SERVER['REMOTE_ADDR'];

	// check that job assignment matches
	$original_path=$jobid;
	$assigned_path=$jobid.'-'.$agentid.'-'.$ip;
	if (!is_dir($assigned_path)) fail('invalid jobid');

	$result_file=$assigned_path.'/result.tgz';


	// double check that we had successful upload first
	if (empty($_FILES)) fail('unable to find uploaded file');
	if (empty($_FILES['userfile'])) fail('uploaded file with wrong tag - use userfile');
	$upload=$_FILES['userfile'];
	if ($upload['error']==UPLOAD_ERR_INI_SIZE) fail('Upload exceeds max filesize');
	if ($upload['error']!= 0) fail('Upload error occurred: '.print_r($upload,true));
	if (empty($upload['name']) || empty($upload['tmp_name']) || empty($upload['size'])) fail('Missing details in upload:'.print_r($upload,true));

	if (!move_uploaded_file($upload['tmp_name'],$result_file))
		fail('Failure to move uploaded file');

	// rename the directory to mark status
	if ($status!='ok')
		$status='fail';

	$completed_path=$original_path.'-'.$status;
	$result=rename($assigned_path,$completed_path);
	if (!$result) fail('rename $assigned_path $completed_path failed');

	$completed_url='http://'.$_SERVER['HTTP_HOST'].'/'.$completed_path;

	// do we have a post script?
	$script=dirname($completed_path).'/post-'.$status.'.sh';
	if (is_file($script))
	{
		chdir($completed_path);
		passthru('sh ../post-'.$status.'.sh '.$completed_url.' 2>&1 |Mail -s "RESULT: '.$completed_path.' from '.$ip.'" scott@stg.net');
		exit;
	} 

	$proj_path=dirname($script);
	$info=stat($script);
	mail("scott@stg.net","RESULT $completed_path no $script",`ls -l $script`);

	exit;
