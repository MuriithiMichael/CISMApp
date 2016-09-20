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
$accStatus;


if ( $text == "" ) {
     $accno = "578";
     $accStatus = $eb->checkAccountStatus($accno);
     echo $accStatus;
     if($accStatus==-1){
     	$response  = "CON You dont have a registered account.\n";
     	$response .="1.Register";
     }else if($accStatus==0){
     	$response  = "END Account Inactive. Contact Customer Care at 0722451036.\n";
     }else if($accStatus==1){
     	$username = $eb->getUserName($accno);
     	$response  = "CON Welcome ".$username." to HEB Mobile Banking \n";
     $response .= " 2. Proceed to Login";
     }
	 
     // This is the first request. Note how we start the response with CON
     // $response  = "CON Welcome to HEB Mobile Banking \n";
     // $response .= "1. Register\n";
     // $response .= "2. Login";
     
     //Check in the Database if this PIN exists this should be against the teacher's phone number
     //return the classes assigned to this teacher/tutor

 
}else{

	switch ($input[0]) {
		case '1':
			if(empty($input[1])){
				$response="CON Enter Your Account Number:";	
			}else if(empty($input[2])){

				$accountNo=$input[1];
				$accountNoAvalability= $eb->accountExist($accountNo);

				if($accountNoAvalability==0){
					$response="CON Enter ID No:";
				}else{
					$response="END Account Number already exists!!";
				}
				
			}else if(empty($input[3])){
				$response="CON Enter DOB:";
			}else if(empty($input[4])){
				$response="CON Password:";
			}else if(empty($input[5])){

				$response="CON Re-type Password:";
			}else if (empty($input[6])) {

				if($input[4]==$input[5]){

					$accountNo=$input[1];
					$idNo=$input[2];
					$dob=$input[3];
					$password=$input[4];

					$result=$eb->registerAccount($accountNo,$idNo,$dob,$password,$mysqli);

						if($result==1){

							$response="END Account created successfully!!";
						}else{
							$response="END Failed to Create Account!!";
						}
					//add record to db
					
				}else{
					$response="END Password Mismatch!!";
					
				}
				
			}
			
			break;
		case '2':
			if(empty($input[1])){
				$response="CON Enter Your Account Number:";
			}else if(empty($input[2])){
				$response="CON Enter Password:";
			}else if(empty($input[3])){

				$accountNo=$input[1];
				$password=$input[2];
				$loginSuccess=$eb->login($accountNo,$password);
				if($loginSuccess==1){
				$response="CON  Choose Option:\n".
							"1:Check Balance\n".
							"2:Deposit\n".
							"3:Widthdraw \n".
							"4:Change Password\n".
							"5:Mini Statement";
						}else {
							$response="END Invalid A/C number or password!!";
						}
			}elseif (!empty($input[3])) {
				switch ($input[3]) {
					case '1':
						$accountNo=$input[1];
						$balance=$eb->getBalance($accountNo);

						if($balance==-1){
							$response="END Invalid Balance";
						}else{
							$response="END Your Current Balance is: ksh ".$balance;
						}
						
						//get balance from db

						break;
					case '2':
						if(empty($input[4])){
							$response="CON Enter Account:";
						}elseif (empty($input[5])) {
							$response="CON Enter Amount:";
						}elseif (empty($input[6])) {
							//deposits amount to account and return balance
							$amount=$input[5];
							$Account=$input[4];

							$result=$eb->updateBalance($Account,$amount,'D');

							if($result==1){
								$balance=$eb->getBalance($Account);
								$response="END ".$amount." was  Deposited to your account. Your Current Balance is Ksh: ".$balance;
							}else {
								$response="END Deposit for Ksh: ".$amount." Failed" ;
							}
							
						}
						break;
					case '3':
						if(empty($input[4])){
							$response="CON Enter Account:";
						}elseif (empty($input[5])) {
							$response="CON Enter Amount:";
						}elseif (empty($input[6])) {
							//widthdraws amount to account and return balance
							$amount=$input[5];
							$Account=$input[4];

							$result=$eb->updateBalance($Account,$amount,'W');

							if($result==1){
								$balance=$eb->getBalance($Account);
								$response="END ".$amount." was  widthdrawn from your account. Your Current Balance is Ksh: ".$balance;
							}else {
								$response="END You have Insufficient Balance !" ;
							}
							

						}
						break;
					case '4':
						if(empty($input[4])){

							$response="CON Old Password:";

						}else if (empty($input[5])) {
							$oldPassword=$input[2];
							$oldPasswordTyped=$input[4];

							//compare password login with and typed Old password
							if($oldPassword==$oldPasswordTyped){
								$response="CON New Password:";
							}else {
								$response="END Old Password Mismatch!!";
							}
							
						}else if (empty($input[6])) {
							$response="CON Re-type new Password:";
						}else if(empty($input[7])){
							$newPassword=$input[5];
							$newPasswordRetype=$input[6];

							if($newPassword==$newPasswordRetype){
								//update the new password
								$accountNo=$input[1];
								$result=$eb->changePassword($newPassword,$accountNo);
								if($result==1){
									$response="END Password Changed Successfully:";
								}else {
									$response="END Failed to Change Password!!!:";
								}
								
							}else{
								$response="END Password Mismatch!!!!!!:";

							}
						}
						break;
					case '5':
						//retrives a mini statement
							$accountNo=$input[1];
							$result=$eb->getMiniStatement($accountNo);

							if($result==2){
								$response="END Your do not have any transactions";

							}else if($result==1){
									$response="END Unable to retrive your mini statement";
							}else{
									$response="END Mini Statement: \n".$result;
									
							}
							
						break;
					
					default:
							$response="END Invalid Choice";
						break;
				}
			}
			break;
		default:
			$response = "END Thank you for using eBank";
			break;
	}
	
}
// Print the response onto the page so that our gateway can read it
header('Content-type: text/plain');
echo $response;
// DONE!!!


?>