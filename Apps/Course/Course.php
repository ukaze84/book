	 	<?php
		$Course = $GLOBALS["Course"];
		if( $_POST["Link"] ){
        	$_POST["Advance"] = Apps::run("Course/AdvanceData");
		}
		Page::Title( $Course->CName." - Tenoz Campus" );
		Page::Init(function(){

			Assets::Import("jquery");
			Assets::Import("materialize");
			Assets::Style("/Resource/css/style.css");
			Assets::Style("/Resource/semantic/components/header.min.css");
			Assets::Style("/Resource/semantic/components/dimmer.min.css");
			Assets::Script("/Resource/semantic/components/dimmer.min.js");
			Assets::Script("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js");
			// Asset::Import("semantic");
			// Asset::Import("ionicons");
			// Asset::Import("animate");
			// Asset::Style("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css");
			// Asset::Script("https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js");
			// if( $_POST["Link"] ){
			// 	Asset::Script("https://www.gstatic.com/charts/loader.js");
			// 	Chart();
			// }
		});


		Page::TagOn("body");
			
			Page::TagOn("header");
				if( $_SESSION["Tenoz"]["Logon"] && $_SESSION["User"] ){
					Page::Item( 
						"Nav/NavbarLogon",
						array( 
							"User" => $_SESSION["User"]["Name"]
						)
					);
				} else {
					Page::HTML( "Nav/Navbar" );
				}
				Page::HTML( "Sidebar" );
			Page::TagOff("header");
			
			// Apps::Run( "Menubar/CourseSidebar" );
			// Apps::Run( "Menubar/CourseMenubar" );

			Page::TagOn( "main" );
				Page::Item("Course/Course" ,array(
					"CourseName" 	=> $Course->CName,
					"CourseID" 		=> $Course->CID,
					"Professor" 	=> $Course->CProfessor,
					//"Expert" 		=> $Course->ProfExpert,
					"Section" 		=> $Course->CSection,
					"Department"	=> $Course->CUnit,
					"Term"			=> $Course->CTerm,
					"Language"		=> $Course->CLanguage,
					"SelectType"	=> $Course->CSelectType,
					"Points"		=> $Course->CPoint,
					"CourseTime"	=> $Course->CourseTime,
					"InDepart"		=> $Course->InDepart,
				 	"OutDepart"		=> $Course->OutDepart,
					"Attributes"	=> $Course->Attributes,
					"ProfBack"		=> $_POST["Advance"]["ProfBack"]?$_POST["Advance"]["ProfBack"]:"æŽˆèª²è€å¸«",
					"Script"		=> drawChart()
				));

			Page::TagOff("main");

			//View::Load( "Course/TimeTable" )->Item(
			// 		TimeTableMarked($Course)
			//);

			// View::Load( "Course/CourseRules" )->Item(array_merge(
			// 	$Course->CourseAllow,
			// 	array(
			// 		"InDepart"		=> $Course->InDepart,
			// 		"OutDepart"		=> $Course->OutDepart,
			// 		"Attributes"	=> $Course->Attributes,
			// 		"Priority"		=> $Course->Priority,
			// 		"NoWithdraw"	=> $Course->NoWithdraw,
			// 		"Over4"			=> $Course->Over4,
			// 	))
			// );
 			
 			// if( $_POST["Advance"] ){
			


 			// 	$Progress = "";
 			// 	$Pack = $_POST["Advance"]["Progress"];
				// 	$aMonth = "";
 			// 	foreach ($Pack as $key => $date) {
				// 	if(@$pre = $_POST["Advance"]["Progress"][$key-1])		// if new month
				// 	if(explode("/",$date[1])[0] !== @explode("/",$pre[1])[0]){
 			// 			$Progress["TimeTable"] .= CourseProgress($aMonth);	// unpack
 			// 			$aMonth = "";
				// 	}
				// 	$aMonth[] = $date;
				// 	if(($key == count($Pack)-1)){
 			// 			$Progress["TimeTable"] .= CourseProgress($aMonth);
				// 	}
 			// 	}
 			// 	View::Load( "Course/CourseProgress" )->Item($Progress);


				// View::Load( "Course/CourseChart" )->Item($Method);
 				
 			// 	$_POST["Advance"]["Book"] = $_POST["Advance"]["Book"][0];
				// View::Load( "Course/CourseRulesAdvance" )->Item(
				// 		$_POST["Advance"]
				// );

 			// }

		// $Course->dump();

		// Page::HTML( "Menubar/SidebarClose" );
		Assets::Import("course");
		Page::TagOff( "body" );








		function drawChart(){
			$Return = "<script>";
			$Charts = "";
			foreach ($_POST["Advance"]["Method"] as $data) {
				if( $data[1] ){
					$Label["Met"][] = "'{$data[0]}'";
					$Data ["Met"][]  = $data[1];
				}
			}
			foreach ($_POST["Advance"]["Eva"] as $data) {
                 if( $data[1] ){
                     $Label["Eva"][] = "'{$data[0]}'";
                     $Data ["Eva"][]  = $data[1];
                 }
			}
			$LabelM = implode( ",", $Label["Met"] );
			$DataM  = implode( ",", $Data["Met"] );

			$LabelE = implode( ",", $Label["Eva"] );
			$DataE  = implode( ",", $Data["Eva"] );
			$Return .= "
				var label1 = [{$LabelM}], label2 = [{$LabelE}];
				var data1  = [{$DataM}], data2=[{$DataE}];
			";
			foreach ($_POST["Advance"]["Eva"] as $data) {
				if( $data[1] ){
					$Charts["EvaChart"].="['{$data[0]}',{$data[1]}],";
				}
			}
			$Return .= "
				setTimeout(function() {
						var c1 = $(\"#chart1\");
						var chart = new Chart(c1, {
					    	type: 'doughnut',
						    data: {
								labels : label1,
								datasets:[{
									data:data1,
		        					backgroundColor: [
							            \"#FF6384\",
							            \"#4BC0C0\",
							            \"#FFCE56\",
							            \"#E7E9ED\",
						            	\"#36A2EB\",
										\"#AAFC33\"
						        	],
								}],
							},
						    //options: options
						});
						var c2 = $(\"#chart2\");
		                var chart2 = new Chart(c2, {
		                    type: 'doughnut',
		                    data: {
		                        labels : label2,
		                        datasets:[{
		                            data:data2,
		                            backgroundColor: [
		                                \"#FF6384\",
		                                \"#4BC0C0\",
		                                \"#FFCE56\",
		                                \"#E7E9ED\",
		                                \"#36A2EB\",
		                                \"#AAFC33\"
		                            ],
		                        }],
		                    },
		                    //options: options
		                });

				},1);	 //end timeout 

			";
			$Return .= "</script>";
			return $Return;
		}




		function TimeTableMarked( $Course ){
			$Selected = "<i class='large green icon ion-checkmark-round'></i>";
			for($i = 1; $i <= 3; $i++){
				if( sizeof($Course->{"time$i"}) > 1 ){
					$TimeStart 	= $Course->{"time$i"}[0];
					$TimeEnd 	= $Course->{"time$i"}[1];
					if( $TimeStart == "DN" && $TimeEnd == "DN" ){
							$TimeTableMarked["tt".$Course->{"week$i"}."DN"] = $Selected;
							$TimeTableMarked["Att".$Course->{"week$i"}."DN"] = "class='active'";
							$TimeStart 	= 1;
							$TimeEnd 	= 0;
					} else if( $TimeStart == "DN" ){
							$TimeTableMarked["tt".$Course->{"week$i"}."DN"] = $Selected;
							$TimeTableMarked["Att".$Course->{"week$i"}."DN"] = "class='active'";
							$TimeStart = "D5";
					} else if ( $TimeEnd == "DN" ) {
							$TimeTableMarked["tt".$Course->{"week$i"}."DN"] = $Selected;
							$TimeTableMarked["Att".$Course->{"week$i"}."DN"] = "class='active'";
							$TimeEnd = "D4";
					
					} else if ( $TimeStart <= "D4" && $TimeEnd >= "D5" ) {
							$TimeTableMarked["tt".$Course->{"week$i"}."DN"] = $Selected;
							$TimeTableMarked["Att".$Course->{"week$i"}."DN"] = "class='active'";
					}

					for( $j = $TimeStart; $j <= $TimeEnd; $j++ ){
						$TimeTableMarked["tt".$Course->{"week$i"}.$j] = $Selected;
						$TimeTableMarked["Att".$Course->{"week$i"}.$j] = "class='active'";
					}
				}
			}
			return $TimeTableMarked;
		}

		function CourseProgress( $Pack ){
			$Month = explode("/", $Pack[0][1])[0];
			$HTML.=
			"	<div class='ui vertical segment'>
					<div class='ui horizontal segments' style='border:0;box-shadow:none'>
						<div class='ui center aligned basic segment' style='min-width:100px!important;max-width:100px!important'>
							<div class='ui big teal label'>
								{$Month}<br>æœˆ
							</div>
						</div>
						<div class='ui basic segment'>
							<div class='ui list'>
			";
			foreach ($Pack as $key => $date) {
				$HTML.=
				"				<div class='item' style='margin-top:20px;margin-bottom:20px;'>
			    					<div class='ui teal header'>
			    						{$date[1]}
			    					</div>
			    					<div class='ui header'>
			    						{$date[2]} 
			    					</div>
			    					{$date[3]} 
			  					</div>
				";
			}
			$HTML.=
			"				</div>
						</div>
					</div>
				</div>
			";
			return $HTML;
			return "<HR>".print_r($Pack);
		}