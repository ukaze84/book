<?php


	Models::Load( "Book/GetBookData" );

	Page::Title("Details");
	Page::Init(function(){
		Assets::Import("jquery");
		Assets::Import("materialize");
		Assets::Import("semantic");
		Assets::Style("/Resource/css/style.css");
		Assets::Style("/Resource/css/book.css");
		Assets::Style("/Resource/css/details.css");
		Assets::Script("/Resource/js/book.js");
	});



	Page::TagOn("body");
		if( @$_SESSION["Tenoz"]["Logon"] && $_SESSION["User"] ){
			Page::Item( 
				"Nav/NavbarLogon",
				array( 
					"User" => $_SESSION["User"]["Name"]
				)
			);
		} else {
			Page::HTML( "Book/Navbar" );
		}
		
		
		Page::TagOn( "main");
			
			
			Page::HTML("Book/Details");
				
		Page::TagOff( "main" );
	
	Page::TagOff("body");