<?php

 include "conn.php";
 include "methods.php";
 $eb= new ebank();

 //setup connection to server
 $eb->getConnection($mysqli);
 
// Reads the variables sent via POST from our gateway
//$sessionId   = $_POST["sessionId"];
//$serviceCode = $_POST["serviceCode"];
//$phoneNumber = $_POST["phoneNumber"];

if(isset($_GET["text"])){


$text  = $_GET["text"];

}else{

$text="";
}
$input=array();
$input=explode("*",$text);
$level=count($input);
$phoneNumber = "23456";
$optionval = 0;
$accStatus = $eb->checkAccountStatus($phoneNumber);
$userid = $eb -> getUserId($phoneNumber);


if ( $text == "" ) {
     
     $accStatus = $eb->checkAccountStatus($phoneNumber);
     echo $accStatus;
     if($accStatus==-1){
     	$response  = "CON You dont have a registered account.\n";
     	$response .="1.Register";
     }else if($accStatus==0){
     	$response  = "END Account Inactive. Contact Customer Care at 0722451036.\n";
     }else if($accStatus==1){
     	$username = $eb->getUserName($phoneNumber);
     	$response  = "CON Welcome ".$username." to Safebox Mobile Service \n";
     $response .= " 1. Proceed to Login";
     
     }
	 
     // This is the first request. Note how we start the response with CON
     // $response  = "CON Welcome to HEB Mobile Banking \n";
     // $response .= "1. Register\n";
     // $response .= "2. Login";
     
     //Check in the Database if this PIN exists this should be against the teacher's phone number
     //return the classes assigned to this teacher/tutor

 
}else{
	if($accStatus==-1){
     	switch ($input[0]) {
		case '1':
			if(empty($input[1])){
				$accountNoAvalability= $eb->accountExist($phoneNumber);

				if($accountNoAvalability==0){
					$response="CON Enter Your Name:";
				}else{
					$response="END Account Number already exists!!";
				}	
			}else if(empty($input[2])){
					$response="CON Enter ID Number:";
			}else if(empty($input[3])){
				 $idNo=$input[2];
				$idNoAvalability= $eb->idNoExist($idNo);

				if($idNoAvalability==0){
					$response="CON Password:";
				}else{
					$response="END ID Number already exists!!";
				}
			}else if(empty($input[4])){
				$response="CON Re-type Password:";
			}else if(empty($input[5])){
				if($input[3]==$input[4]){
					$name=$input[1];
					$idNo=$input[2];
					$password=$input[3];

					$result=$eb->registerAccount($phoneNumber,$idNo,$password,$name);

						if($result==1){

							$response="END Account created successfully!!";
						}else{
							$response="END Failed to Create Account!!";
						}
			}else  {
				$response="END Password Mismatch!!";
					
				}
				
			}
			
			break;
			default:
			$response = "END Thank you for using eBank";
			break;
		}
     }else if($accStatus==0){
     	$response  = "END Wait for action 2";
     }else if($accStatus==1){
     	switch ($input[0]) {
		case '1':
			if(empty($input[1])){
				$response="CON Enter Password:";
			}else if(empty($input[2])){
				$password=$input[1];
				$loginSuccess=$eb->login($phoneNumber,$password);
				if($loginSuccess==1){
				$response="CON  Choose Option:\n".
							"1:My Services\n".
							"2:Products\n".
							"3:Account \n".
							"4:Change Password\n";
						}else {
							$response="END Failed to login!!";
						}
			}else {
				switch($input[2]){
					case '1':
					if(empty($input[3])){
						$response="CON  Choose Option:\n".
							"1:View Services\n".
							"2:Request New Service\n".
							"3:Request Retrieval\n".
							"4:View Retrievals\n";
					}if(empty($input[4])){
						switch($input[3]){
							case '1':
							break;
							case '2':
							break;
							case '3':
							break;
							case '4':
							break;
							default:
							$response = "END Invalid Option!!";

						}
					}
					break;
					///Product Logic
					case '2':
					if(empty($input[3])){
						$response="CON  Choose Option:\n".
							"1:Request New Product\n".
							"2:View Requested Products\n".
							"3:Statement on Purchased Products\n";
					}else if(empty($input[4])){
							switch ($input[3]) {
								case '1':
								$response = "CON Choose Option:\n";
									$array = $eb->getProducts();
     								$x = count($array);
     								for($val = 0; $val < $x; $val++){
     									$option = $val+1;
     									$response .= $option.".".$array[$val]."\n";
     								}
									break;
								case '2':
									$requestedProducts = array();
									$requestedProducts = $eb->getRequestedProducts($userid,"R");
									$count = count($requestedProducts);
									$response = "CON Requests made but not approved:\n";
									for($x = 0; $x < $count; $x++){
										$val = $x + 1;
										$response .= $val.". ".$requestedProducts[$x]."\n";
									}
									break;
								case '3':
									$requestedProducts = array();
									$requestedProducts = $eb->getRequestedProducts($userid,"P");
									$count = count($requestedProducts);
									$response = "CON Requests made but not approved:\n";
									for($x = 0; $x < $count; $x++){
										$val = $x + 1;
										$response .= $val.". ".$requestedProducts[$x]."\n";
									}
									break;
								default:
									$response = "END Invalid Option!!!";
									break;
							}
						}else if(empty($input[5])){
							$response = "Enter the quantity: (NB:numbers only)";
						}else if(empty($input[6])){
							$productmenu = $input[3];
							$selectedproduct = $input[4];
							switch ($input[3]) {
								case '1':
								$quantity = $input[5];
								$array = $eb->getProducts();
						        $selectedProductType = $array[$selectedproduct];
						        $productPrice = $eb->getProductPrice($selectedProductType);
						        $amount = $productPrice * $quantity;
								$response = "CON Are you sure you want to request the purchase of ".$quantity." ".$selectedProductType." that amounts to KSH.".$amount.".00? \n";
								$response .= "1. Yes\n2. No";
     								
									break;
								case '2':
									
									break;
								case '3':
									# code to display purchased products
									break;
								default:
									//$response = "END Invalid Option!!!";
									break;
							}
						}else if(empty($input[7])){
							switch ($input[6]) {
     									case '1':
     									$selectedproduct = $input[4];
										$quantity = $input[5];
     									$array = $eb->getProducts();
						        		$selectedProductType = $array[$selectedproduct];
						        		$productPrice = $eb->getProductPrice($selectedProductType);
						        		$productId = $eb->getProductId($selectedProductType);
						        		$userid = $eb->getUserId($phoneNumber);
						        		$amount = $productPrice * $quantity;
						        		$insertResponse = $eb->saveRequestedProduct($userid, $productId,$quantity,$amount);
						        		if($insertResponse == 1){
						        			$response = "Request sent Successfully you will receive a confirmation message shortly and our sales team will contact you before end of the day!";
						        		}else if($insertResponse == 0){
						        			$response = "Request Failed!!!";
						        		}
     									
     									break;
     									case '2':
     									$response = "END Transaction could not be completed!!";
     									break;
     									default:
     									$response = "END Invalid Option!!!";
     									break;
     								
							}
						}
					break;
					//Account Logic
					case '3':
					$response = "END I selected Accounts";
					break;
					default:
					$response = "END Invalid A/C number or password!!";
				}
			}
			break;
			default:
			$response = "END Thank you for using SafeBox Mobile Services";
			break;
		}
     }
	
}
// Print the response onto the page so that our gateway can read it
header('Content-type: text/plain');
echo $response;
// DONE!!!


?>