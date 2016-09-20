<?php

 include "conn.php";
 include "methods.php";
 $eb= new ebank();

 //setup connection to server
 $eb->getConnection($mysqli);
 
// Reads the variables sent via POST from our gateway
//$sessionId   = $_POST["sessionId"];
//$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];

if(isset($_POST["text"])){


$text  = $_POST["text"];

}else{

$text="";
}
$input=array();
$input=explode("*",$text);
$level=count($input);
//$phoneNumber = "0722232429";
$optionval = 0;
$accStatus = $eb->checkAccountStatus($phoneNumber);
if($accStatus==1 ){
	$userid = $eb -> getUserId($phoneNumber);
}else{
	$userid = 0;
}


if ( $text == "" ) {
     
     $accStatus = $eb->checkAccountStatus($phoneNumber);
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
					//Service Logic
					case '1':
					if(empty($input[3])){
						$response="CON  Choose Option:\n".
							"1:Request Retrieval\n".
							"2:Request New Service\n".
							"3:View Retrieval Requests\n".
							"4:View Subscribed Services\n".
							"5.View Service Requests";
					}else if(empty($input[4])){
						switch ($input[3]) {
							//Request Retrieval
							case '1':
								$array = $eb->getSubscribedServices($userid);
     								$x = count($array);
     								if($x<=0){
									$response ="END Subscribe to a service!!! If you have subscribed to a service, contact customer care at 0722451036 to ensure the service request has been approved and activated\n";
     								}else{
     									$response ="CON Select Service\n";
     								for($val = 0; $val < $x; $val++){
     									$option = $val+1;
     									$response .= $option.".".$array[$val]."\n";
     								}	
     								}
     								
								break;
							//Request New Service
							case '2':
								$array = $eb->getServices();
     								$x = count($array);
									$response ="CON Select Service Type\n";
     								for($val = 0; $val < $x; $val++){
     									$option = $val+1;
     									$response .= $option.".".$array[$val]."\n";
     								}	
     								
								break;
							//View Retrievals
							case '3':
							$array = $eb->getRetrievalDetails($userid);
     						$x = count($array);
							//echo $userid."count:".$x;
							$response ="END A list of all Retrievals Requests:\n";
     						for($val = 0; $val < $x; $val++){
     								$option = $val+1;
     								$response .= $option.".".$array[$val]."\n";
     							}	
								break;
							//View Subscribed Services
							case '4':
							$array = $eb->getSubscribedServiceDetails($userid);
     						$x = count($array);
							//echo $userid."count:".$x;
							$response ="END A list of all Subscribed Services:\n";
     						for($val = 0; $val < $x; $val++){
     								$option = $val+1;
     								$response .= $option.".".$array[$val]."\n";
     							}	
								break;
							//View Service Requests
							case '5':
							$array = $eb->getServiceRequestDetails($userid);
     						$x = count($array);
							//echo $userid."count:".$x;
							$response ="END A list of all new Service Requests:\n";
     						for($val = 0; $val < $x; $val++){
     								$option = $val+1;
     								$response .= $option.".".$array[$val]."\n";
     							}	
								break;
							
							default:
								$response="END Invalid option!!!!";
								break;
						}

					}else if(empty($input[5])){
						switch($input[3]){
							//Retrieval Request
							case '1':
							$option = $input[4];
							$val = $option-1;
							$array = $eb->getSubscribedServices($userid);
							$service = $array[$val];
							$response = "CON Are you sure you want to request the retrieval of contents in service ".$service."? \n1.Yes\n2.No";
								break;
							//Request New Service
							case '2':
							$option = $input[4];
							$val = $option-1;
							$array = $eb->getServices();
							$serviceContract = $array[$val];
							$response = " CON Are you sure you want to request a new ".$serviceContract."?\n1.Yes\n2.No";
								break;
							//View Retrievals
							case '3':
								break;
							//View Subscribed Services
							case '4':
								break;
							//View Service Requests
							case '5':
								break;
							
							default:
								$response="END Invalid option!!!!";
								break;
						}

					}else if(empty($input[6])){
						switch($input[3]){
							//Retrieval Request
							case '1':
							$approvalChoice = $input[5];
							if($approvalChoice==1){
								$option = $input[4];
								$val = $option-1;
								$array = $eb->getSubscribedServices($userid);
								$service = $array[$val];
								$subscribedServiceId = $eb->getSubscribedServiceId($service);
								$saveRequest = $eb->saveRetrievalRequest($userid, $subscribedServiceId);
								if($saveRequest == -1){
									$response = "END Retrieval request has already been made!!!";
								}else if($saveRequest == 1){
								$response = "END Retrieval Request Successfull! Our Sales team will contact you in 2 hours to confirm the location where your box will be delivered!";
								}else {
									$response = "END Retrieval request failed!!!";
								}
							}else{
								$response = "END Retrieval request has been cancelled!!!";
							}
								break;
							//Request New Service
							case '2':
								$option = $input[4];
							$val = $option-1;
							$array = $eb->getServices();
							$serviceContract = $array[$val];
							$serviceid = $eb->getServiceId($serviceContract);
							$saveNewServiceRequest = $eb->saveRequestedService($userid, $serviceid);
							if($saveNewServiceRequest == 1){
								$response = "END Request Sent Successfully!!!";
							}else{
								$response = "END Failed to send Request!!";
							}
								break;
							//View Retrievals
							case '3':
								# code...
								break;
							//View Subscribed Services
							case '4':
								# code...
								break;
							//View Service Requests
							case '5':
								# code...
								break;
							
							default:
								$response="END Invalid option!!!!";
								break;
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
									$response = "END Requests made but not approved:\n";
									for($x = 0; $x < $count; $x++){
										$val = $x + 1;
										$response .= $val.". ".$requestedProducts[$x]."\n";
									}
									break;
								case '3':
									$requestedProducts = array();
									$requestedProducts = $eb->getRequestedProducts($userid,"P");
									$count = count($requestedProducts);
									$response = "END Requests made but not approved:\n";
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
							$response = "CON Enter the quantity: (NB:numbers only)";
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
						        			$response = "END Request sent Successfully you will receive a confirmation message shortly and our sales team will contact you before end of the day!";
						        		}else if($insertResponse == 0){
						        			$response = "END Request Failed!!!";
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