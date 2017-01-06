<?php

include('mysql.php');
include('coverist.php');
include('SupremeLibrary.php');

mysql_query('TRUNCATE TABLE `grading`');
$grader_id = 4;

// PREVENT GRADING BUGS
$queues = Database::getAllThat('queues', '`grader_id` is NOT NULL');
foreach ($queues as $queue) {
	$sql = "UPDATE `queues` SET `grader_id` = NULL AND `user_id` = NULL WHERE `id` = ".$queue->id;
	mysql_query($sql);
}

while(true)
{
	$use_queues = Database::getAllThat('queues', '`grader_id` is NOT NULL'); 
	$use = [];
	foreach ($use_queues as $queue) {
		$use[$queue->user_id] = 1;
	}

	$queues = Database::getAllThat('queues', '`grader_id` is NULL');
	$idx = -1;
	foreach ($queues as $key => $queue) {
		$submit = new Database;
		$submit->TABLE = 'submits';
		$submit->id = $queue->submit_id;
		$submit->select();
		if(!isset($use[$submit->user_id]))
		{
			$idx = $key;
			$tmp_queue = new Database;
			$tmp_queue->TABLE = 'queues';
			$tmp_queue->id = $queue->id;
			$tmp_queue->select();
			$tmp_queue->grader_id = $grader_id;
			$tmp_queue->user_id = $submit->user_id;
			$tmp_queue->update();
			break;
		}
	}

	if($idx != -1)
	{

		$grade = new Database;
		$grade->TABLE = 'grading';
		$grade->submit_id = $queues[$idx]->submit_id;
		$grade->insert();

		$submit = new Database;
		$submit->TABLE = 'submits';
		$submit->id = $queues[$idx]->submit_id;
		
		if($submit->select())
		{
			$task = new Database;
			$task->TABLE = 'tasks';
			$task->id = $submit->task_id;
			$task->select();

			$myJudge = new SupremeClass;
			$myJudge->code = 'judge/codes/'.$submit->id.'.cpp';
			$myJudge->grader_id = $grader_id;

			$myJudge->exct = 'judge/a_'.$grader_id.'.out'; 
			$myJudge->ans = 'judge/ans_'.$grader_id.'.sol'; 

			if($task->general_check)
				$myJudge->chk = 'judge/checkcodes/0.cpp';
			else
				$myJudge->chk = 'judge/checkcodes/'.$task->id.'.cpp';

			$myJudge->lim = 'judge/box.out';
			$myJudge->lim_mem = $task->memory * 1024;
			$myJudge->lim_time = $task->time;

			if($myJudge->compile())
			{
				$submit->time = 0;
				$submit->memory = 0;
				$pass = 1;

				// grading testcase
				$submit->result = '';
				for($i = 1; $i <= $task->testcase; $i++)
				{

					$myJudge->in = 'judge/testcases/'.$task->id.'/'.$i.'.in'; 
					$myJudge->key = 'judge/testcases/'.$task->id.'/'.$i.'.sol'; 
					
					$tmp = $myJudge->execute();
					
					if($tmp == 'F')
					{
						$check = $myJudge->check();
						$submit->result .= ($check == 1) ? ('P') : ('-');
					}
					else if($tmp == 'T')
					{
						$submit->result .= 'T';
						$submit->time = $myJudge->lim_time;
					}
					else
					{
						$submit->result .= 'X';
					}

					if($submit->result[$i-1] != 'P') $pass = 0;

					$submit->time = ($submit->time > $myJudge->use_time) ? ($submit->time) : ($myJudge->use_time);
					$submit->memory = ($submit->memory > $myJudge->use_mem) ? ($submit->memory) : ($myJudge->use_mem);
				}

				if($pass == 1 and $task->visible == 1)
				{
					$pass = new Database;
					$pass->TABLE = 'pass';
					$pass->task_id = $task->id;
					$pass->user_id = $submit->user_id;
					if($pass->select())
					{
						$pass->submit_data = json_decode($pass->submit_data);
						array_push($pass->submit_data, $submit->id);
						$pass->submit_data = json_encode($pass->submit_data);
						$pass->update();
					}
					else
					{
						$pass->submit_data = array();
						array_push($pass->submit_data, $submit->id);
						$pass->submit_data = json_encode($pass->submit_data);
						$pass->insert();
					}
				}

				// grading pretestcase
				$submit->result .= '+';

				for($i = 1; $i <= $task->pretestcase; $i++)
				{
					$myJudge->in = 'judge/testcases/'.$task->id.'/p'.$i.'.in'; 
					$myJudge->key = 'judge/testcases/'.$task->id.'/p'.$i.'.sol'; 

					$tmp = $myJudge->execute();
					if($tmp == 'F')
					{
						$check = $myJudge->check();
						$submit->result .= ($check == 1) ? ('P') : ('-');
					}
					else if($tmp == 'T')
						$submit->result .= 'T';
					else
						$submit->result .= 'X';
				}

				unlink($myJudge->exct);
			}

			$submit->compile_result = mysql_escape_string($myJudge->cmp_msg);
			$submit->update();

		}

		$grade->delete();
		$queue = new Database;
		$queue->TABLE = 'queues';
		$queue->id = $queues[$idx]->id;
		$queue->select();
		$queue->delete();
	}

	sleep(1);
}

?>