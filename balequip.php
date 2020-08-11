<?php

class Balequip {

var $mysqli;

	//connection to database
	function getConnection($mysqli){
		$this->mysqli=$mysqli;
	}

	//register customer
	function registerCustomer($name,$address,$phoneno){

		$registerQuery=$this->mysqli->query("INSERT INTO `customer` (`name`,`address`,`phoneNo`)
						VALUES('$name','$address','$phoneno');");	

		if($registerQuery){

			$result=1;
		}
		else{
			$result=0;
		}
			
		return $result;
	}


	//check if customer exist
	function isCustomerExist($phoneno){

		$customerQuery= $this->mysqli->query("SELECT * FROM `customer` WHERE `phoneNo`='$phoneno'");

		if($customerQuery){
			$rows=$customerQuery->num_rows;

			if($rows>0){
				$row=$customerQuery->fetch_assoc();
				$result=$row['name'];

			}else{
				$result=0;
			}
		}else{
			$result=-1;
		}
		return $result;
	}

	//add Order
	function addOrder($itemId,$quantity,$amount,$phoneno,$token){
		$date= date('dd MM yyyy hh:mm:ss');
		$addOrderQuery=$this->mysqli->query("INSERT INTO `orders` (`ItemId`,`quantity`,`amount`,`phoneNo`,`date`,`token`)
						VALUES('$itemId','$quantity','$amount','$phoneno','$date','$token');");	

		if($addOrderQuery){

			$result=1;
		}
		else{
			$result=0;
		}
			
		return $result;
	}
	function getCustormerOrderNo($phoneno,$token){
		$orderNoQuery= $this->mysqli->query("SELECT `OrderId` FROM `orders` WHERE `phoneNo`='$phoneno' AND `token`='$token'");

		if($orderNoQuery){
			while ( $row=$orderNoQuery->fetch_assoc()) {
				$result=$row['OrderId'];
			}
				
							
		}else{
			$result=0;
		}
		return $result;
	}

	


	//rate product
	function rateProduct($itemId,$phoneno,$rating){

		$rateItemQuery=$this->mysqli->query("INSERT INTO `ratings` (`itemID`,`phoneNo`,`rating`)
						VALUES('$itemId','$phoneno','$rating');");	

		if($rateItemQuery){

			$result=1;
		}
		else{
			$result=0;
		}
			
		return $result;
	}

	//get Item price
	function getItemPrice($itemId){

		$priceQuery= $this->mysqli->query("SELECT `price` FROM `item` WHERE `itemId`='$itemId'");

		if($priceQuery){
			while ( $row=$priceQuery->fetch_assoc()) {
				$result=$row['price'];
			}
				
							
		}else{
			$result=0;
		}
		return $result;
	}
	//get item name
	function getItemName($itemId){

		$nameQuery= $this->mysqli->query("SELECT `itemName` FROM `item` WHERE `itemId`='$itemId'");

		if($nameQuery){
			while ( $row=$nameQuery->fetch_assoc()) {
				$result=$row['itemName'];
			}
				
							
		}else{
			$result=0;
		}
		return $result;
	}

	//get item Description
	function getItemDescription($itemId){

		$nameQuery= $this->mysqli->query("SELECT `description` FROM `item` WHERE `itemId`='$itemId'");

		if($nameQuery){
			while ( $row=$nameQuery->fetch_assoc()) {
				$result=$row['description'];
			}
				
							
		}else{
			$result=0;
		}
		return $result;
	}

	

	//sends SMS to Customer regarding order;
	function sendSMS($recipient,$message){

		// Be sure to include the file you've just downloaded
				require_once('AfricasTalkingGateway.php');

				// Specify your login credentials
				$username   = "Magerer";
				$apikey     = "0b13ac28e8605404409ca269146af76a6ebb7fdc9fe65711c8a0c049ae18b7b9";

				// Specify the numbers that you want to send to in a comma-separated list
				// Please ensure you include the country code (+254 for Kenya in this case)
				$recipients = $recipient;

				// And of course we want our recipients to know what we really do
				$message    = $message;

				// Create a new instance of our awesome gateway class
				$gateway    = new AfricasTalkingGateway($username, $apikey);

				// Any gateway error will be captured by our custom Exception class below, 
				// so wrap the call in a try-catch block

				try 
				{ 
				  // Thats it, hit send and we'll take care of the rest. 
				  $results = $gateway->sendMessage($recipients, $message);
							
				  foreach($results as $result) {
				    // // status is either "Success" or "error message"
				    // echo " Number: " .$result->number;
				    // echo " Status: " .$result->status;
				    // echo " MessageId: " .$result->messageId;
				    // echo " Cost: "   .$result->cost."\n";
				  }
				}
				catch ( AfricasTalkingGatewayException $e )
				{
				  echo "Encountered an error while sending: ".$e->getMessage();
				}
	}
}






















?>