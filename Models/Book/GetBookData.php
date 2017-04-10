<?php
class Book{

	public static function GetBook($in_dept, $in_grade, $in_class){

		//TODO: following data will be merge into database
		$depart = array( 51 => "資工" );
		$grade  = array( "", "一", "二", "三", "四", "五", "六", "七" );
		$class  = array( "", "甲", "乙" );
		
		
		if( !is_numeric($in_dept) || !is_numeric($in_grade) || !is_numeric($in_class) ){
				Util::Status400();	
		}

		$filter =$depart[$in_dept].$grade[$in_grade];
		$filter2=$depart[$in_dept].$grade[$in_grade].$class[$in_class];
		$SQL = 
			"	SELECT 	B.`b_name`, B.`b_author`,
						B.`b_pic`,  B.`b_ver`, B.`b_price`, B.`bid`,
						C.`CName`,  C.`CProf`, C.`Dept`
				FROM		`Book` AS B
				INNER JOIN 	`fju_1042` AS C
				ON 			C.`CID` = B.`b_cid`
				WHERE  		C.`Dept`= '{$filter}' OR
					   		C.`Dept`= '{$filter2}';
			";
		$Data = DB::Query( $SQL );
		$Results = array();
		while($Fetch = $Data->fetch(PDO::FETCH_ASSOC)) {
			$Results [] = array(
				"bid"   => $Fetch["bid"],
				"image" => $Fetch["b_pic"],
				"name"	=> $Fetch["b_name"],
				"author"=> $Fetch["b_author"],
				"vers"  => $Fetch["b_ver"],
				"price" => $Fetch["b_price"],

				"course"=> $Fetch["CName"],
				"prof"	=> $Fetch["CProf"]
			);
		}
		return $Results?$Results:false;
	}
		/*
		$Book.=
			"
			<table class=\"ui fixed table\">
		  	<tbody>
		    	<tr>
		       	<td><h2 class=\"ui image header\"><img src=\"{$Fetch["b_pic"]}\" class=\" ui small rounded image\"></h2></td>
		    	 	<td><h3>{$Fetch["b_name"]}</br></br>{$Fetch["b_author"]}</br></br>{$Fetch["b_ver"]}.ver</h3></td>
		        <td><h3>{$Fetch["CName"]}</br></br>{$Fetch["CProf"]}&nbsp;教授</h3></td>
		        <td><h3>NTD{$Fetch["b_price"]}</h3></td>
				   	<td>
		    	 	    <button class=\"circular ui brown icon button \" value=\"{$Fetch["bid"]}\" onclick=\"BuyCar.setStack({$Fetch["bid"]}); $(this).addClass('disabled').children('i').removeClass('big add to cart icon').addClass('big checkmark icon');\">
		      	      <i class=\"big add to cart icon\"onclick=\"\"></i>
		            </button>
		        </td>
				 	</tr>
		    </tbody>
			</table>
		";
		*/


		public static function GetList($books){
			$sum = 0;
			$list = "";
			for( $i = 0; $i < count($books); $i++){
				if( !is_numeric($books[$i]) ) Util::Status400();
				$list .= ( $list == "" )? ($books[$i]) : (",".$books[$i]);
			}
			$SQL = 
			"	SELECT 	B.`b_name`, B.`b_author`,
						B.`b_pic`,  B.`b_ver`, B.`b_price`, B.`bid`,
						C.`CName`,  C.`CProf`, C.`Dept`
				FROM 	`Book` AS B
				INNER JOIN 
						`fju_1042` AS C
				ON 		C.`CID` = B.`b_cid`
				WHERE  	B.`bid` in ($list);
			";
			if( $list !== "" ){
				$Data = DB::Query( $SQL );
				while($Fetch = $Data->fetch(PDO::FETCH_ASSOC)){
					$Results [] = array(
						"bid"   => $Fetch["bid"],
						"image" => $Fetch["b_pic"],
						"name"	=> $Fetch["b_name"],
						"author"=> $Fetch["b_author"],
						"vers"  => $Fetch["b_ver"],
						"price" => $Fetch["b_price"],

						"course"=> $Fetch["CName"],
						"prof"	=> $Fetch["CProf"]
					);
					$sum += $Fetch["b_price"];
				}
			}
			$Results = $Results?array(
				"items" => $Results,
				"count" => count($Results),
				"cost"  => $sum
			):array(
				"items" => array(),
				"count" => 0,
				"cost"  => 0
			);
			return $Results;
		}

				// while($Fetch = $Result->fetch(PDO::FETCH_ASSOC)){
				// 	$List.=
				// 	"
				// 	<table class=\"ui fixed table\">
				// 		<tbody>
				// 			<tr>
				// 				<td>
				// 					<h2 class=\"ui image header\">
				// 						<img src=\"{$Fetch["b_pic"]}\" class=\"ui small rounded image\">
				// 					</h2>
				// 				</td>              		           			             
				// 				<td>
				// 					<h3>
				// 						{$Fetch["CName"]}</br></br>
				// 						{$Fetch["b_name"]}</br></br>
				// 						NTD{$Fetch["b_price"]}
				// 					</h3>
				// 				</td>  
				// 				<td></td>   
				// 				<td>
				// 					<h4 class=\"ui icon header\">
				// 						<button class=\"circular ui red button\">
				// 							<i class=\"remove icon\"></i>
				// 						</button>
				// 					</h4>
				// 				</td>
				// 			</tr>
				// 		</tbody>
				// 	</table>
				// 	";
				// }
			// }
		// 	$List = $List?$List:false;
		// 	return $List;
		// }
}
