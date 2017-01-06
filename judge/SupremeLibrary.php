<?php

/*
Supreme Library v1.1
*/

class SupremeClass {

	public function pushLog($log) {

		$this->lst_log = $log;
		
		if(isset($this->log))
			$this->log .= '<br>'.$log;
		else
			$this->log = $log;

	}

	public function compile() {

		if(!isset($this->lim))
			$this->lim = 'judge/box.out';

		if(!file_exists($this->lim))
			shell_exec('gcc judge/box64.c -o '.$this->lim.' -std=c99');

		$log_chk = shell_exec('g++ -std=c++11 -O2 -o judge/check_'.$this->grader_id.'.out '.$this->chk.' 2>&1');

		$this->pushLog($log_chk);

		$log = shell_exec('g++ -std=c++11 -O2 -o '.$this->exct.' '.$this->code.' -static 2>&1');

		$this->pushLog($log);
		$this->cmp_msg = $log;

		return file_exists($this->exct);
	}

	public function execute() {

		$log = shell_exec('./'.$this->lim.' -a 2 -f -i '.$this->in.' -o '.$this->ans.' -m '.$this->lim_mem.' -t '.$this->lim_time.' -T '.$this->exct.' -M judge/exct_info_'.$this->grader_id.' 2>&1');

		$this->pushLog($log);

		$exct_info = '';
		$file = fopen('judge/exct_info_'.$this->grader_id, "r");
		while(!feof($file)) {
			$exct_info .= fgets($file).' ';
		}
		fclose($file);

		if(strstr($log, 'OK')) //'F' = 'finished', 'M' = 'over limited memory', 'T' = 'time out', 'E' = 'something is error'
		{

			$tmp = strstr($exct_info, 'mem:');
			$this->use_mem = '';
			for($i = 4; $tmp[$i] != ' '; $i++)
			{
				$this->use_mem .= $tmp[$i];
			}
			$this->use_mem = round(intval($this->use_mem)/1024);

			$tmp = strstr($exct_info, 'time:');
			$this->use_time = '';
			for($i = 5; $tmp[$i] != ' '; $i++)
			{
				$this->use_time .= $tmp[$i];
			}
			$this->use_time = doubleval($this->use_time);

			return 'F';
		}
		else if(strstr($exct_info, 'status:TO'))
		{
			return 'T';
		}
		else if(strstr($exct_info, 'status:SG'))
		{
			return 'M';
		}

		return 'E';

	}

	public function check() {

		$apfile = fopen($this->ans, "a+");
		fwrite($apfile, "\n");
		fclose($apfile);

		$file = fopen('judge/input_file_'.$this->grader_id, 'w+');
		fwrite($file, $this->key.' ');
		fwrite($file, $this->ans);
		fclose($file);

		$log = shell_exec('./judge/check_'.$this->grader_id.'.out < judge/input_file_'.$this->grader_id.' 2>&1');

		$this->pushLog($log);

		if(substr($log, 0, 2) == '$#')
			return intval($log[2]); //'1' is correct answer, '0' is incorrect answer

		return -1; //something is error

	}		
}