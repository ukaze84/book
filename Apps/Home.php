<?php


	Page::Title("Campus");
	Page::Init(function(){

		Assets::Import("jquery");
		Assets::Import("materialize");
		Assets::Style("/Resource/css/home.css");
		Assets::Style("/Resource/semantic/components/search.min.css");
		Assets::Style("/Resource/css/style.css");
		Assets::Script("/Resource/semantic/components/search.min.js");
		Assets::Script("/Resource/semantic/components/api.min.js");
		Assets::Script("/Resource/js/typed.min.js");
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
			Page::HTML( "Nav/Navbar" );
		}
		Page::HTML( "Home/Home" );
		Assets::Import("search");
		Assets::Script("/Resource/js/home.js");


	Page::TagOff("body");