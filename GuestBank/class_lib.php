	<?php
	class Client{
		var $fname;
		var $lname;
		var $mname;
		var $accountnum;
		var $username;
		var $password;
	
		function Client($fname, $lname, $mname, $accountnum, $username, $password){
			$this->set_name($fname);
			$this->set_mname($mname);
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
		function set_mname($mname){
			$this->mname=$mname;
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
		function get_mname(){
			return $this->mname;
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
		var $branchcode;
	
		function Admin($username, $password, $empid, $mgrflag, $branchcode){
			$this->set_username($username);
			$this->set_password($password);
			$this->set_mgrflag($mgrflag);
			$this->set_empid($empid);
			$this->set_branchcode($branchcode);
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
		
		function set_branchcode($branchcode){
			$this->branchcode=$branchcode;
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
		function get_branchcode(){
			return $this->branchcode;
		}
	}
	
	class Transaction{
	
		var $accountnum;
		var $otheraccountnum;
		var $transactiontype;
		var $tranasactiondate;
		var $transactcost;
		var $transactionop;
		var $transactnum;
		var $classify;
	
		function Transaction($accountnum, $otheraccountnum, $transactiontype, $transactiondate, $transactcost, $transactionop, $transactnum, $classify){

		$this->accountnum = $accountnum;
		$this->otheraccountnum = $otheraccountnum;
		$this->transactiontype = $transactiontype;
		$this->transactiondate = $transactiondate;
		$this->transactcost= $transactcost;
		$this->transactionop = $transactionop;
		$this->transactnum = $transactnum;
		$this->classify = $classify;				 
		
		}	
	}
	
	class TransactionModel{
	
		var $transactnum;
		var $accountnum;
		var $otheraccountnum;
		var $transactiontype;
		var $transactmedium;
		var $transactionop;
		var $transactdate;
		var $transactcost;
		var $branchcode;
	
		function TransactionModel($transactnum,$accountnum,$otheraccountnum,$transactiontype,$transactmedium,$transactionop, $transactdate,$transactcost,$branchcode){
		
		$this->transactnum = $transactnum;
		$this->accountnum = $accountnum;
		$this->otheraccountnum = $otheraccountnum;
		$this->transactiontype = $transactiontype;
		$this->transactmedium = $transactmedium;
		$this->transactionop = $transactionop;
		$this->transactdate = $transactdate;
		$this->transactcost = $transactcost;
		$this->branchcode = $branchcode;
	
		}
	
	}
	

?>