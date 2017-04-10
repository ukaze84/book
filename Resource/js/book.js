var storage = {};
var cart = {
	stack:[],
	prefix: "book/cart/",

	init:function(){
        for(i = 0; i < window.sessionStorage.length; i++){
            var wsValue = window.sessionStorage.key(i);
            if(wsValue.substr(0, this.prefix.length) == this.prefix){
                this.stack.push(window.sessionStorage[wsValue]);
            }
        }
    },

	add:function(input){
		input = input+"";
		if( this.stack.indexOf(input) == -1 ){
			window.sessionStorage.setItem(this.prefix + this.stack.length.toString(), input);
			this.stack.push(input);
		}
	},

	getStack:function(){
        return this.stack;
    },

    remove:function(input){
		input = input+"";
    	index = this.stack.indexOf(input);
    	if( index != -1 ){
    		ws = window.sessionStorage;
	        for(var i = 0; i < ws.length; i++){
	            var wsValue = ws.key(i);
	            if( wsValue == this.prefix+index){
	                console.log("remove "+wsValue + " : "+ws.getItem(wsValue));
	                ws.removeItem(wsValue);
					this.refresh();
	                return; 
	            }
	        }
    	}
    },
    refresh:function(){
    	index = 0;
		ws = window.sessionStorage;
		this.stack = [];
        for(var i = 0; i < ws.length; i++){
            console.log('checking '+this.prefix+i +" - "+ ws.key(i));
            temp = ws.getItem( ws.key(i) ) + "";
            if( this.stack.indexOf(temp) == -1 ){
	            ws.setItem(this.prefix+i,temp);
	            this.stack.push(temp);
	            console.log('moving '+this.prefix+i +" - "+ ws.key(i));
            } else {
	            console.log('removing '+this.prefix+i +" - "+ ws.key(i));
            	ws.removeItem(ws.key(i))
            }
        }
    },

	reset:function(){
        var number = 0;
        do{
            number = this.recursive(number);
        } while (number != this.stack.length - 1);
        this.stack = null;
    },

	recursive:function(number){
        for(var i = 0; i < window.sessionStorage.length; i++){
            var wsValue = window.sessionStorage.key(i);
            //assign a variable wsvalue equal to the session keys.
            if(wsValue.substr(0, this.prefix.length) == this.prefix){
                //if    
                window.sessionStorage.removeItem(wsValue);
                return number + 1; 
            }
        }
    },
};

var $bookData = {books:[]};
var $books = new Vue({
	el:"#books",
	data:$bookData,
});
var $bookCartData = {books:[],count:0,cost:0};
var $bookCart = new Vue({
	el:"#bookCartbooks",
	data:$bookCartData,
	computed: {
		cost: function(){
			console.log( "computing ");
			return this.books.reduce(function(prev, product){
				return sum + product.price; 
			},0);
	  	}
	}
});

$books.$watch('books', function (val) {
	checkBooksisInStack();
	eventListenerRegister();
});
$bookCart.$watch('books', function (val) {
	// checkBooksisInStack();
	// eventListenerRegister();
});
function setClass( cla ){
	$('button').removeClass("active");
	$('button[value='+cla+']').addClass("active");
	$('input[name=class]').val(cla);
	$('input[name=class]').trigger('change');
}

function getData($depart, $grade, $class){
 	if( $depart == "" || $grade == "" || $class == "") return;
 	$.ajax({
 	   	url:"/book/depart",
 	    type:"POST",
 	    data:{
 	      depart: 	$depart,
 	      grade: 	$grade,
 	      class: 	$class
 	    },
 	    success:function(data){
		    console.log(data);
		    // reset data
		    $books.$set('books', data.results);
		},
		error:function(data){
		    // console.log("ajax failed", data);
		}
 	});
}
function markInCart( dom ){
	$(dom).addClass('disabled')
		.children('i')
		.removeClass('ion-ios-cart')
		.addClass('ion-checkmark');

}
function unmarkInCart( dom ){
	$(dom).removeClass('disabled')
		.children('i')
		.removeClass('ion-checkmark')
		.addClass('ion-ios-cart');
}
function checkBooksisInStack(){
	$(".addCart").each(function(index,dom){
		id = $(dom).attr("data-id")+"";
		if( cart.stack.indexOf(id) != -1 ){
			markInCart( dom );
			console.log( id + " marked" )
		} else {
			unmarkInCart( dom );
			console.log( id + " UNmarked" )
		}
	});
}
function eventListenerRegister(){
	console.log("register");
	$(".addCart").click(function(){
		console.log("click");
		cart.add( $(this).attr("data-id")+"" );
		markInCart( $(this) );
	});
}
function remove(id){
	swal({
		title: "再次確認",
		text: "確定要移除這本書嗎？",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "移除",
		cancelButtonText: "保留",
		closeOnConfirm: false,
		closeOnCancel: false
	}, function(isConfirm){
		if (isConfirm) {
			swal({
				title: "完成動作",
				text: "書本已被移除",
				type: "success",
				timer: 1500,
				showConfirmButton: false
			});
			setTimeout(function(){
				$(".cartRow[data-id="+id+"]").transition('scale');
			},2000);
			refreshCartInfo(id+"");
			cart.remove(id);
			checkBooksisInStack();
		} else {
			swal({
				title: "取消動作",
				text: "將不做任何變更",
				type: "success",
				timer: 1500,
				showConfirmButton: false
			});
		}
	});
}
function refreshCartInfo(find){
	$bookCart.$set('books.count', $bookCart.$get('books.count')-1);
	cost = $bookCart.$get('books.cost');
	for( i = 0; i<20; i++){
		id = $bookCart.$get("books.items["+i+"].bid");
		if( find == id ){
			$bookCart.$set('books.cost', cost - $bookCart.$get("books.items["+i+"].price"));
		}
	}
}
cart.init();
$('select.dropdown').dropdown();
$("select,input").on("change",function(){
	getData(
		$("select[name=depart]").val(),
		$("select[name=grade]").val(),
		$('input[name=class]').val()
	);
});

$('.remove').click(function(){
	cart.remove( $(this).attr("data-bid") );
});

$('#shopcart').click(function(){
	$('.ui.large.modal').modal({
		// onVisible:function(){
		// 	console.log("A" + $('#bookCartbooks> .description').height());
		// 	$('#bookCartbooks> .description').height ($('#bookCartbooks').height()*0.75);
		// 	console.log($('#bookCartbooks> .description').height());

		// }
	}).modal('show');


	$.ajax({
 	   	url:"/book/books",
 	    type:"POST",
 	    data:{
 	    	books:cart.stack
 	    },
 	    success:function(data){
		    // console.log(data);
		    // reset data
		    $(".cartRow").removeClass("transition hidden");
		    $bookCart.$set('books', data.results);
		},
		error:function(data){
		    // console.log("ajax failed", data);
		}
 	});
	// $request=BuyCar.getStack();
	// $.ajax({
	// 		url:"/getbooklist",
	// 		type:"POST",
	// 		data:{
	// 		request:$request
	// 	},
	// 	success:function(data){
	// 		console.log(data);
	// 		$(".description").html(data.results);  
	// 	},
	// 	error:function(data){
	// 		console.log("ajax failed", data);
	// 	}
	// });
});

$('#checkout').click(function(){
	//TODO : Send data
	// 	    BuyCar.sentStack($(this).value);
	//	    BuyCar.finishStack();
});



// 	    //start sessionStorage
// 	    $('#cashier').on('click',function(){
// 	    	BuyCar.sentStack($(this).value);
// 	    	BuyCar.finishStack();
// 	    });

// 	    $('.remove icon').on('click',function(){
// 	    	BuyCar.recursive();

// 	    });
// 	    setInterval(function(){
// 		$.each( $(".circular.ui.brown.icon.button"),function( i,d ){
// 			if( BuyCar.getStack().indexOf(d.value) > -1 ){
// 				$(d).addClass('disabled').children("i").removeClass('big add to cart icon').addClass('big checkmark icon');
// 			}
// 		})
// 		console.log("AAAA")		
// 	    },1000);


//   });


//  function Send( dep, gra, cla ){
//  	$.ajax({
//  	   	url:"/getbookdata",
//  	    type:"POST",
//  	    data:{
//  	      depart: dep,
//  	      grade:  gra,
//  	      class:  cla
//  	    },
//  	    success:function(data){
// 		    console.log(data);
// //		    $("#list").html(data.results);  
// 		},
// 		error:function(data){
// 		    console.log("ajax failed", data);
// 		}
//  	});
//  }



	        // var cart = $('.fixed-action-btn horizontal');
	        // var imgtodrag = $(this).parent('.item').find("img").eq(0);
	        // if (imgtodrag) {
	        //     var imgclone = imgtodrag.clone()
	        //         .offset({
	        //         top: imgtodrag.offset().top,
	        //         left: imgtodrag.offset().left
	        //     })
	        //         .css({
	        //         'opacity': '0.5',
	        //             'position': 'absolute',
	        //             'height': '150px',
	        //             'width': '150px',
	        //             'z-index': '100'
	        //     })
	        //         .appendTo($('body'))
	        //         .animate({
	        //         'top': cart.offset().top + 10,
	        //             'left': cart.offset().left + 10,
	        //             'width': 75,
	        //             'height': 75
	        //     }, 1000, 'easeInOutExpo');
	            
	        //     setTimeout(function () {
	        //         cart.effect("shake", {
	        //             times: 2
	        //         }, 200);
	        //     }, 1500);

	        //     imgclone.animate({
	        //         'width': 0,
	        //         'height': 0
	        //     }, function () {
	        //         $(this).detach()
	        //     });
	        // }
