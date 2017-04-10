$(".button-collapse").sideNav();

// $(".button-collapse").click(func tion(){
// 	$(".button-collapse").sideNav("show");
// });

$(window).resize(function (e) {
	if( $(this).width()<992){
		$(".navbar-fixed").css({"z-index":999});
		$(".side-nav").css({"z-index":998});
			
	}else{
		$(".navbar-fixed").css({"z-index":998});
		$(".side-nav").css({"z-index":1});
	}
});

setTimeout(function(){
	$(".c-icon").fadeTo( 1000, 1 );
},1);
setTimeout(function(){
	$(".mainPanel").children(".dimmer").removeClass( "active" );
},2);
