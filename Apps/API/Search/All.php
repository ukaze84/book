<?php

	Models::Load("API/Search/Course");
	$Search = htmlentities(strip_tags($_POST["search"]));
	if( mb_strlen( trim($_POST["search"]), "UTF-8" ) <= 0 ){
		Util::Status403("Missing Content");
	}


	
	if( strpos( $Search, " " ) !== -1 ){
		$Search = preg_split("/[\s]+/", $Search);
		$Search = implode("%", $Search);
	}


	Util::StatusTrue(
		Search::GetAllResult( $Search )
	);