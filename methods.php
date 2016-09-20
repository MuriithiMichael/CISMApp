<?php


class ebank {
	var $mysqli;


	function getConnection($mysqli){
		$this->mysqli=$mysqli;
	}
	function __contruct(){
		

	}
//account registration
	function registerAccount($accountNo,$idNo,$password,$name){
		
		// $registerQuery=$this->mysqli->query("INSERT INTO `accounts` (`accountNo`,`idNo`,`DOB`,`password`)
		// 				VALUES('$accountNo','$idNo','$dob','$password');");	
		$registerQuery=$this->mysqli->query("INSERT INTO `user` (`Name`, `PhoneNumber`, `IdNo`, `Password`, `Status`, `Date`) VALUES ( '$name', '$accountNo', '$idNo', '$password', 'I', now())");

		if($registerQuery){

			$result=1;
		}
		else{
			$result=0;
		}
			
		return $result;
	}



//get username 
	function getUserName($accNo){

		$userNameQuery=$this->mysqli->query("SELECT * FROM `user` WHERE `PhoneNumber`='$accNo'");
	
			if($userNameQuery){

				if($row=$userNameQuery->fetch_assoc()) {

					$username= $row['Name'];
					
					
				}
				return $username;
				
			}else{
				return -1;
			}
			
	}
	//get userid 
	function getUserId($accNo){

		$userNameQuery=$this->mysqli->query("SELECT * FROM `user` WHERE `PhoneNumber`='$accNo'");
	
			if($userNameQuery){

				if($row=$userNameQuery->fetch_assoc()) {

					$username= $row['id'];
					
					
				}
				return $username;
				
			}else{
				return -1;
			}
			
	}
	//check account status
	function checkAccountStatus($accNo){

		// $accountQuery=$this->mysqli->query("SELECT * FROM `accounts` WHERE `accountNo`='$accNo'");
		$accountQuery=$this->mysqli->query("SELECT * FROM `user` WHERE `PhoneNumber`='$accNo'");
			$rows = $accountQuery->num_rows;
			if($rows>0){

				$accountStatusQuery=$this->mysqli->query("SELECT * FROM `user` WHERE `PhoneNumber`='$accNo' AND `Status`='A'");
				$row = $accountStatusQuery->num_rows;
				if($row>0)	{
					return 1;
				}else{
					return 0;
				}
				}else{
				return -1;
			}
			
	}

	//get all products
	function getProducts(){
		$productArray = array();
		$productQuery=$this->mysqli->query("SELECT * FROM `product`");
		$x=0;
			if($productQuery){

				while($row=$productQuery->fetch_assoc()) {

					$type = $row['Type'];
					$productArray[$x] = $type;
					$x++;
				}
				return $productArray;
				
			}else{
				return -1;
			}
			
	}
	//get product price
	function getProductPrice($product){
			$productPriceQuery=$this->mysqli->query("SELECT * FROM `product` WHERE `Type`='$product'");
	
			if($productPriceQuery){

				if($row=$productPriceQuery->fetch_assoc()) {

					$price= $row['Price'];
				}
				return $price;
				
			}else{
				return -1;
			}
	}
	//get product id
	function getProductId($product){
			$productIdQuery=$this->mysqli->query("SELECT * FROM `product` WHERE `Type`='$product'");
	
			if($productIdQuery){

				if($row=$productIdQuery->fetch_assoc()) {

					$id= $row['id'];
				}
				return $id;
				
			}else{
				return -1;
			}
	}
	//get service id
	function getServiceId($service){
			$serviceIdQuery=$this->mysqli->query("SELECT * FROM `service` WHERE `type`='$service'");
	
			if($serviceIdQuery){

				if($row=$serviceIdQuery->fetch_assoc()) {

					$id= $row['id'];
				}
				return $id;
				
			}else{
				return -1;
			}
	}
	//get subscribed service id
	function getSubscribedServiceId($service){
			$serviceIdQuery=$this->mysqli->query("SELECT * FROM `subscribed_services` WHERE `service_no`='$service'");
	
			if($serviceIdQuery){

				if($row=$serviceIdQuery->fetch_assoc()) {

					$id= $row['id'];
				}
				return $id;
				
			}else{
				return -1;
			}
	}

	//get all subscribed services
	function getSubscribedServices($userid){
		$serviceArray = array();
		$serviceQuery=$this->mysqli->query("SELECT * FROM `subscribed_services` WHERE `user_id`='$userid'");
		$x=0;
			if($serviceQuery){

				while($row=$serviceQuery->fetch_assoc()) {

					$type = $row['service_no'];
					$serviceArray[$x] = $type;
					$x++;
				}
				return $serviceArray;
				
			}else{
				return -1;
			}
			
	}
	//get all details for subscribed services
	function getSubscribedServiceDetails($userid){
		$serviceArray = array();
		$serviceQuery=$this->mysqli->query("SELECT ss.`service_no`,ss.`open_date`,s.`type` FROM `subscribed_services` ss, `service` s WHERE ss.`service_type`=s.`id` AND ss.`user_id`='$userid' AND ss.`status`='A'");
		$x=0;
			if($serviceQuery){

				while($row=$serviceQuery->fetch_assoc()) {

					$serviceno = $row['service_no'];
					$type = $row['type'];
					$date = $row['open_date'];
					$value = $serviceno." (".$type.") opened on ".$date;
					$serviceArray[$x] = $value;
					$x++;
				}
				return $serviceArray;
				
			}else{
				return -1;
			}
			
	}
	//get service request details
	function getServiceRequestDetails($userid){
		$retrievalArray = array();
		$x = 0;
		$retrievalQuery = $this->mysqli->query("SELECT s.`type`, sr.`date` FROM `service_request` sr, `service` s WHERE sr.`service_id`=s.`id` AND sr.`status`='R' AND sr.`user_id`='$userid'");
		if($retrievalQuery){
			while($rows = $retrievalQuery->fetch_assoc()){
				$serviceType = $rows['type'];
				$date = $rows['date'];
				$stringResponse = $serviceType." requested on ".$date;
				$retrievalArray[$x]=$stringResponse;
				$x++;
			}
			return $retrievalArray;

		}else{
			return -1;
		}
	}
	//get all services
	function getServices(){
		$serviceArray = array();
		$serviceQuery=$this->mysqli->query("SELECT * FROM `service` ORDER BY `id` DESC");
		$x=0;
			if($serviceQuery){

				while($row=$serviceQuery->fetch_assoc()) {

					$type = $row['type'];
					$serviceArray[$x] = $type;
					$x++;
				}
				return $serviceArray;
				
			}else{
				return -1;
			}
			
	}
	//function for inserting records for requested services
	function saveRequestedService($userid, $serviceid){
		$saveRequestQuery = $this->mysqli->query("INSERT INTO `service_request` (`service_id`,`user_id`,`status`,`date`) VALUES ('$userid','$serviceid','R',now())");
		if($saveRequestQuery){
			$result = 1;
		}else{
			$result = 0;
		}
		return $result;
	}

	//function for inserting records for retrieval requests
	function saveRetrievalRequest($userid, $subscribedserviceid){
		$retrievalStatus = $this->mysqli->query("SELECT * FROM `retrieval_request` WHERE `subscribed_service_id`='$subscribedserviceid' AND `status`='R'");
		if($retrievalStatus){
			$row = $retrievalStatus->num_rows;
			if($row>0){
				return -1;
			}else{
				$saveRequestQuery = $this->mysqli->query("INSERT INTO `retrieval_request` (`subscribed_service_id`,`user_id`,`status`,`date`) VALUES ('$subscribedserviceid','$userid','R',now())");
		if($saveRequestQuery){
			$result = 1;
		}else{
			$result = 0;
		}
			}
		}else{
			
		}
		return $result;
	}

	//function for inserting records for requested products
	function saveRequestedProduct($userid, $productId, $quantity,$amount){
		$saveRequestQuery = $this->mysqli->query("INSERT INTO `purchased_products` (`user_id`,`product_id`,`status`,`date`,`quantity`,`amount`) VALUES ('$userid','$productId','R',now(),'$quantity','$amount')");
		if($saveRequestQuery){
			$result = 1;
		}else{
			$result = 0;
		}
		return $result;
	}
	function getRequestedProducts($userid,$status){
		$requestArray = array();
		$x = 0;
		if($status == "R"){
			$operation = "requested";

		}else if($status == "P"){
			$operation = "purchased";
		}else{
			$operation = "";
		}
		$requestedProducts = $this->mysqli->query("SELECT * FROM `purchased_products` pp, `product` p WHERE p.`id`=pp.`id` and pp.`user_id`='$userid' and pp.`status`='$status'");
		if($requestedProducts){
			while($row = $requestedProducts->fetch_assoc()){
				$type = $row['Type'];
				$quantity = $row['quantity'];
				$amount = $row['amount'];
				$date = $row['date'];
				$value = $quantity." ".$type." that amounts to ".$amount." ".$operation." on ".$date;
				$requestArray[$x] = $value;
				$x++;
			}
			return $requestArray;
		}else{
			return -1;
		}
	}

	//get retrieval request details
	function getRetrievalDetails($userid){
		$retrievalArray = array();
		$x = 0;
		$retrievalQuery = $this->mysqli->query("SELECT ss.`service_no`,s.`type`,r.`date` FROM `subscribed_services` ss, `retrieval_request` r, `service` s WHERE ss.`service_type`=s.`id` AND r.`subscribed_service_id`=ss.`id` and r.`status`='R' and r.`user_id`='$userid'");
		if($retrievalQuery){
			while($rows = $retrievalQuery->fetch_assoc()){
				$service = $rows['service_no'];
				$serviceType = $rows['type'];
				$date = $rows['date'];
				$stringResponse = "Retrieval of service ".$service."(".$serviceType.") requested on ".$date;
				$retrievalArray[$x]=$stringResponse;
				$x++;
			}
			return $retrievalArray;

		}else{
			return -1;
		}
	}

//login
	function login($accountNo,$password){

		$loginQuery=$this->mysqli->query("SELECT `PhoneNumber`,`Password` FROM `user` WHERE `PhoneNumber`='$accountNo' AND `Password`='$password'");
		$rows=$loginQuery->num_rows;

		if($rows>0){
			$result=1;
		}else{
			$result=0;
		}

		return $result;
	}

	//check if account exists
	function accountExist($accNo){
		// $accountQuery= $this->mysqli->query("SELECT * FROM `accounts` WHERE `accountNo`='$accNo' ");
		// $rows=$accountQuery->num_rows;
		$accountQuery= $this->mysqli->query("SELECT * FROM `user` WHERE `PhoneNumber`='$accNo' ");
		$rows=$accountQuery->num_rows;

		if($rows>0){
			$result=1;
		}else{
			$result=0;
		}

		return $result;

	}
	//check if account exists
	function idNoExist($idNo){
		// $accountQuery= $this->mysqli->query("SELECT * FROM `accounts` WHERE `accountNo`='$accNo' ");
		// $rows=$accountQuery->num_rows;
		$accountQuery= $this->mysqli->query("SELECT * FROM `user` WHERE `IdNo`='$idNo' ");
		$rows=$accountQuery->num_rows;

		if($rows>0){
			$result=1;
		}else{
			$result=0;
		}

		return $result;

	}

	//check balance
	function getBalance($accNo){

		$balanceQuery=$this->mysqli->query("SELECT Balance FROM `accounts` WHERE `accountNo`='$accNo'");
	
			if($balanceQuery){

				while($row=$balanceQuery->fetch_assoc()) {

					$balance= $row['Balance'];
					
					
				}
				return $balance;
				
			}else{
				return -1;
			}
			
	}

	//deposit amount
	function depositAmount($accNo,$amount){
		$depositQuery=$this->mysqli->query("INSERT INTO `transactions` (`accountNo`,`transCode`,`amount`)
						VALUES('$accNo','D','$amount');");	
	}
	//widthdraw Amount
	function widthdrawAmount($accNo,$amount){
		$depositQuery=$this->mysqli->query("INSERT INTO `transactions` (`accountNo`,`transCode`,`amount`)
						VALUES('$accNo','W','$amount');");	

	}
	//update balance
	function updateBalance($accNo,$amount,$code){

		if($code=='D'){
			$updateBalanceQuery=$this->mysqli->query("UPDATE `accounts` SET `Balance`=Balance+'$amount' WHERE `accountNo`='$accNo'");

				if($updateBalanceQuery){
			
						$this->depositAmount($accNo,$amount);
						$result=1;
				}else{

						$result=0;
				}

		}else if($code=='W'){
			$balanceResult=$this->getBalance($accNo);
			$amountDiff=$balanceResult-$amount;

			if($amountDiff>=0){

			$updateBalanceQuery=$this->mysqli->query("UPDATE `accounts` SET `Balance`=Balance-'$amount' WHERE `accountNo`='$accNo'");
			$this->widthdrawAmount($accNo,$amount);

			$result=1;

			}else {
				$result=2;
			}

		}

		return $result;
	}

	//change password
	function changePassword($newPassword,$accNo){

		$changePasswordQuery=$this->mysqli->query("UPDATE `accounts` SET `password`='$newPassword' WHERE `accountNo`='$accNo'");
			
			if($changePasswordQuery){
				
				$result=1;

			}else {
				$result=0;
			}

			return $result;

	}

	//mini statement
	function getMiniStatement($accNo){

		$response= "";


			$miniStatement=$this->mysqli->query("SELECT `transCode`,`amount` FROM `transactions` WHERE `accountNo`='$accNo' ORDER BY `transCode` DESC LIMIT 5");
	
			if($miniStatement){

				if($miniStatement->num_rows>0){

				while($row=$miniStatement->fetch_assoc()) {

					//$transactionsArray= array();
					$response.=$row['transCode']."  Ksh".$row['amount']."\n" ;
					
					
				}
				return $response;
				
			}else{
				return 0;
			}
		}else {

			return 2;
		}
		
	}
}




?>