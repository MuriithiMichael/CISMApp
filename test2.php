if(empty($input[3])){
						$response="CON  Choose Option:\n".
							"1:Request New Services\n".
							"2:Request Retrieval\n".
							"3:View Retrievals\n".
							"4:View Subscribed Services\n".
							"5.View Service Requests";
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