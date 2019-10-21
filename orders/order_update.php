<!doctype html>
<html>

<head>
<title>Order</title>
<meta charset="utf-8">
	<meta name="author" content="Chris">
	<meta name="description" content="Order Update">
	<meta name="keywords" content="Order, Order Update">
	<link rel="stylesheet" href="style.css">

	<script src="https://kit.fontawesome.com/335541e0f5.js" crossorigin="anonymous"></script>

	<script src="script.js"></script>
</head>


<?php
		include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
		
		$orderID = $_REQUEST['orderID'];
	?>
	
	
<body>

<article>


<form action=<?php echo "order_process.php?orderID='" . $orderID . "'"?> method="post">



<?php
		//include database connection
		//the connection variable is $conn
		include_once ($_SERVER['DOCUMENT_ROOT']."/db_conn.php");
	
		$orderID = $_REQUEST['orderID'];
		$string = "";
		$string2 = "";
		$string3 = "";
		
		$itemListArray = array();
		$quantityListArray = array();
		$remarksListArray = array();
	
	
		/////////////////////////
		//fetch the items names//
		/////////////////////////
		$orderQuery = "SELECT itemList FROM orderList WHERE orderID = '" . $orderID . "' AND orderStatus = 'Pending'";
		$orderResult = $conn->query($orderQuery);
		
		//fetch the items selected
		while($orderRow = $orderResult->fetch_assoc()){
			$string = implode("", $orderRow);
		}
		//split them into an array
		$itemListArray = explode("\n", $string);
		//trim all the whitespaces to make sure it matches the data in database
		$itemListArray = array_filter(array_map('trim', $itemListArray));
		
		
		////////////////////////////
		//fetch the items quantity//
		////////////////////////////
		$quantityQuery = "SELECT itemQuantity FROM orderList WHERE orderID = '" . $orderID . "' AND orderStatus = 'Pending'";
		$quantityResult = $conn->query($quantityQuery);
	
		while($quantityRow = $quantityResult->fetch_assoc()){
			$string2 = implode("", $quantityRow);
		}
		//split them into an array
		$quantityListArray = explode("\n", $string2);
		//trim all the whitespaces to make sure it matches the data in database
		$quantityListArray = array_filter(array_map('trim', $quantityListArray));
		
		
		///////////////////////////
		//fetch the items remarks//
		///////////////////////////
		$remarksQuery = "SELECT itemRemarks FROM orderList WHERE orderID = '" . $orderID . "' AND orderStatus = 'Pending'";
		$remarksResult = $conn->query($remarksQuery);
		
		while($remarksRow = $remarksResult->fetch_assoc()){
			$string3 = implode("", $remarksRow);
		}
		//split them into an array
		$remarksListArray = explode("\n", $string3);
		//trim all the whitespaces to make sure it matches the data in database
		$remarksListArray = array_filter(array_map('trim', $remarksListArray));
	
	
	
	
		$index = 1;
		$categoryQuery = "SELECT * FROM category";
		$categoryResult = $conn->query($categoryQuery);
		$rowIndex = 0;
		$rowIndex2 = 0;
		
		while ($categoryRow = $categoryResult->fetch_assoc())
		{
			$menuQuery = "SELECT * FROM menu WHERE categoryID = $index";
			$menuResult = $conn->query($menuQuery);
			
			
			// Echos table and values from database
			echo "<h2>" . $categoryRow["categoryName"] . "</h2>";
			echo "<table id='food_table'><tr><th>Item Name</th><th>Checkbox</th><th>Quantity</th><th>Remarks</th></tr>";
			if ($menuResult->num_rows > 0)
			{
				while($menuRow = $menuResult->fetch_assoc())
				{
					echo 
					"<tr class='list-items'><td>" . $menuRow['itemName'] . "</td>";
					
					if(in_array($menuRow['itemName'], $itemListArray)){
						echo"<td><input type='checkbox' name='checkbox1[]' value='" . $rowIndex2 . "' checked></td>";
						echo "<td><input type='text' id='text_order' name='quantity[]' value='" . $quantityListArray[$rowIndex] . "'></td>
							<td><input type='text' id='text_order' name='remarks[]' value='" . $remarksListArray[$rowIndex] . "'></td>
						</tr>";
						$rowIndex++;
					}
					else{
						echo"<td><input type='checkbox' name='checkbox1[]' value='" . $rowIndex2 . "'></td>";
						echo "<td><input type='text' id='text_order' name='quantity[]'></td>
							<td><input type='text' id='text_order' name='remarks[]'></td>
						</tr>";
					}
					$rowIndex2++;
				}
			}
			else
			{
				echo "0 results";
			}
			echo "</table>";
			$index++;
			mysqli_free_result($menuResult);
		}
		mysqli_free_result($categoryResult);
		
		// Close connection (although it is done automatically when script ends
		$conn->close();
	?>

			<div id="send-btn">
					<input type="submit" id="sendorder_btn" value="Send order" name="submit">
					
					</button>
			</div>

</form>
	
			

</article>
</body>

</html>