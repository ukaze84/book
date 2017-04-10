<?php
	// echo "<pre>";
	// var_dump($_SERVER);exit;
	Route::Add("/", function(){
		
		Apps::run( "Home" );
		
	}, "Route" );

	
	Route::Add("/api/search/suggest", function(){
	
		Apps::run("API/Search/Suggest");	
	
	}, "Route" );

    Route::Add("/api/search/all",function(){
    
        Apps::run("API/Search/All");
	
	}, "Route" );
 	Route::Add("/Course(/([A-Z0-9]+))?", function(){
		$_GET["CID"] = Route::GetPath()[1];
		Apps::run( "Course/GetCourseData" );
	}, "Route" );
	
 // 	Route::Add("/book(/([A-Z0-9]+))?", function(){
	// 	$_GET["CID"] =  Route::GetPath()[0];
	// 	Apps::run( "Course/GetCourseData" );

	// }, "Route" );
	
	// Route::Add("/Course(/([0-9]+))?(/([A-Z0-9]+))?", function(){
	// 	print_r($_GET);
	// 	Apps::run( "Course/GetCourseData" );


	// }, "Route" ); 	
	Route::Add("/book", function(){
	
		Apps::run("Book");	
	
	}, "Route" );	

	Route::Add("/book/details", function(){
	
		Apps::run("Details");	
	
	}, "Route" );

 	

	Route::Add("/book/depart", function(){
	
		Models::Load( "Book/GetBookData" );

		if( $GetBook = Book::GetBook($_POST["depart"],$_POST["grade"],$_POST["class"]) ){
			Util::StatusTrue( $GetBook );
		} else {
			Util::StatusFailCode( 500 , "Unknow Error" );
		}
		
	}, "Route" );
 		


	Route::Add("/book/books", function(){
	
		Models::Load( "Book/GetBookData" );
		
		if( $GetBook = Book::GetList($_POST["books"]) ){
				Util::StatusTrue( $GetBook );
		} else {
				Util::StatusFailCode( 500 , "Unknow Error" );
		}

		
	}, "Route" );


	Route::Submit( "Route" );
	echo "No Match";
