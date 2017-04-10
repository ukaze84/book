<?php


	Models::Load( "Book/GetBookData" );

	Page::Title("Book");
	Page::Init(function(){
		Assets::Import("jquery");
		Assets::Import("materialize");
		Assets::Import("semantic");
		Assets::Style("/Resource/css/style.css");
		Assets::Style("/Resource/css/book.css");
		// Assets::Style("/Resource/semantic/components/container.min.css");
		// Assets::Style("/Resource/semantic/components/table.min.css");
		// Assets::Style("/Resource/semantic/components/segment.min.css");
		// Assets::Style("/Resource/semantic/components/modal.min.css");
		// Assets::Script("/Resource/semantic/components/modal.min.js");
		// Assets::Style("/Resource/semantic/components/transition.min.css");
		// Assets::Script("/Resource/semantic/components/transition.min.js");
		// Assets::Style("/Resource/semantic/components/dropdown.min.css");
		// Assets::Script("/Resource/semantic/components/dropdown.min.js");
		// Assets::Style("/Resource/semantic/components/button.min.css");
		// Assets::Style("/Resource/semantic/components/divider.min.css");
		// Assets::Style("/Resource/semantic/components/header.min.css");
		// Assets::Style("/Resource/semantic/components/image.min.css");
		Assets::Style("/Resource/sweetalert/sweetalert.css");
		Assets::Script("/Resource/sweetalert/sweetalert.min.js");
		Assets::Style("/Resource/ionicons/ionicons.min.css");
		Assets::Script("/Resource/js/vue.min.js");
	
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
			
			Page::HTML("Book/Bar");
			Page::HTML("Book/Book");
			Page::HTML("Book/Shopcart");
				
		Page::TagOff( "main" );
	
		Assets::Script("/Resource/js/book.js");
	Page::TagOff("body");