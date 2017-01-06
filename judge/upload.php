<?php

function failed(){
	echo 'No files';
	close();
	exit(0);
}

if ( !empty( $_FILES ) ) {

	$tempPath = $_FILES['file']['tmp_name'];
	$name = explode('.', $_FILES['file']['name']);
	if(count($name) != 2)
	{
		failed();
	}

	if($name[1] == 'pdf')
	{
		$uploadPath = 'docs/'.$_GET['task_id'].'.pdf';
	}
	else if(($name[1] == 'cpp' or $name[1] == 'c' or $name[1] == 'cxx') and $_GET['general_check'] == 0)
	{
		$uploadPath = 'checkcodes/'.$_GET['task_id'].'.cpp';
	}
	else if($name[1] == 'in' or $name[1] == 'sol')
	{
		$testcase = ($name[0][0] != 'p' and 1 <= intval($name[0]) and intval($name[0]) <= $_GET["testcase"]);
		$pretestcase = ($name[0][0] == 'p' and 1 <= intval(substr($name[0],1)) and intval(substr($name[0],1)) <= $_GET["pretestcase"]);
		if($testcase or $pretestcase)
		{
			mkdir('testcases/'.$_GET['task_id'],0777);
			$uploadPath = 'testcases/'.$_GET['task_id'].'/'.$_FILES['file']['name'];

			$apfile = fopen($tempPath, "a+");
			fwrite($apfile, "\n");
			fclose($apfile);
		}
		else
		{
			failed();
		}
	}
	else
	{
		failed();
	}

	move_uploaded_file( $tempPath, $uploadPath );

	$answer = array( 'answer' => 'File transfer completed' );
	$json = json_encode( $answer );

	echo $json;

} else {

	echo 'No files';

}

?>