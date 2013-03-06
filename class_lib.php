	<?php
	class Client{
		var $fname;
		var $lname;
		var $accountnum;
		var $username;
		var $password;
	
		function Client($fname, $lname, $accountnum, $username, $password){
			$this->set_name($fname);
			$this->set_lname($lname);
			$this->set_accountnum($accountnum);
			$this->set_username($username);
			$this->set_password($password);
		}	
		//setter
	
		function set_name($fname){
			$this->fname=$fname;
		}
		function set_lname($lname){
			$this->lname=$lname;
		}
		function set_accountnum($accountnum){
			$this->accountnum=$accountnum;
		}
		function set_username($username){
			$this->username=$username;
		}
		function set_password($password){
			$this->password=$password;
		}
		//getter
		function get_fname(){
			return $this->fname;
		}
		function get_lname(){
			return $this->lname;
		}
		function get_accountnum(){
			return $this->accountnum;
		}
		function get_username(){
			return $this->username;
		}
		function get_password(){
			return $this->password;
		}
	}
	class Admin{
		var $username;
		var $password;
		var $empid;
		var $mgrflag;
	
		function Admin($username, $password, $empid, $mgrflag){
			$this->set_username($username);
			$this->set_password($password);
			$this->set_mgrflag($mgrflag);
			$this->set_empid($empid);
		}	
		//setter

		function set_username($username){
			$this->username=$username;
		}
		function set_password($password){
			$this->password=$password;
		}
		
		function set_empid($empid){
			$this->empid=$empid;
		}
		
		function set_mgrflag($mgrflag){
			$this->mgrflag=$mgrflag;
		}
		
		//getter
		function get_username(){
			return $this->username;
		}
		function get_password(){
			return $this->password;
		}
		function get_empid(){
			return $this->empid;
		}
		function get_mgrflag(){
			return $this->mgrflag;
		}
	}
	?>