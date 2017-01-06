<?php

// Coverist ONE · The php semi-framework powered by PanTA.

define("USER_TABLE","users"); //table that contains user info

class Database{

	function select()
	{
		$new_info = NULL;
		$first = true;
		foreach ($this as $key => $value) {
			if($key == "TABLE")
				continue;
			else if($first)
			{
				$first = !$first;
				$new_info.="`".$key."` = '".$value."'";
			}
			else
			{
				$new_info.="AND `".$key."` = '".$value."'";
			}
		}
		$sql = "SELECT * FROM `".$this->TABLE."` WHERE ".$new_info;
		$result = mysql_query($sql);
		$tmp = mysql_fetch_object($result);
		if(isset($tmp->id))
		{
			foreach ($tmp as $key => $value) {
				$this->$key = $value;
			}
			return true;
		}	
		else
			return false;
	}

	function insert()
	{
		$field = "";
		$values = "";
		$first = true;
		foreach ($this as $key => $value)
		{
			if($key == "TABLE")
				continue;
			else if($first)
			{
				$first = !$first;
				$field.="`".$key."`";
				$values.="'".$value."'";
			}
			else
			{
				$field.=",`".$key."`";
				$values.=",'".$value."'";
			}
		}
		$sql = "INSERT INTO `".$this->TABLE."` (".$field.") VALUES (".$values.")";
		mysql_query($sql);
		$this->id = mysql_insert_id();
	}

	function delete()
	{
		$sql = "DELETE FROM `".$this->TABLE."` WHERE `id` = ".$this->id;
		mysql_query($sql);
	}

	function update()
	{
		$new_info = NULL;
		$first = true;
		foreach ($this as $key => $value) {
			if($key == "TABLE")
				continue;
			else if($first)
			{
				$first = !$first;
				$new_info.="`".$key."` = '".$value."'";
			}
			else
			{
				$new_info.=", `".$key."` = '".$value."'";
			}
		}
		$sql = "UPDATE `".$this->TABLE."` SET ".$new_info." WHERE `id` = ".$this->id;
		mysql_query($sql);
	}

	public static function getAllThat($from ,$where = 1) //this function returns array
	{
		$sql = "SELECT * FROM `".$from."` WHERE ".$where;
		$result = mysql_query($sql);
		$index = 0;
		$data = false;
		while($tmp = mysql_fetch_object($result))
		{
			$data[$index++] = $tmp;
		}
		return $data;
	}	
}

class User{

	public static function isLogin()
	{
		if(isset($_COOKIE["isLogin"]))
			return true;
		else
			return false;
	}

	public static function login($username, $password)
	{
		$sql = "SELECT * FROM `".USER_TABLE."` WHERE `username` = '".mysql_real_escape_string($username)."' AND `password` = '".mysql_real_escape_string($password)."'";
		$result = mysql_query($sql);
		$user = mysql_fetch_object($result);
		if(isset($user->id))
		{
			setcookie("user_id",$user->id,time()+7200);
			setcookie("isLogin",true,time()+7200);
			return true;
		}
		else
			return false;
	}

	public static function logout()
	{
		if(User::isLogin())
		{
			foreach ($_COOKIE as $key => $value) {
				unset($_COOKIE[$key]);
			}
			return true;
		}
		else
			return false;
	}

	function getUser()
	{
		if(User::isLogin())
		{
			$user = new Database;
			$user->TABLE = USER_TABLE;
			$user->id = $_COOKIE["user_id"];
			$user->select();
			foreach ($user as $key => $value) {
				$this->$key = $value;
			}
			return true;
		}
		else
			return false;
	}
}
?>