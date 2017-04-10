var BuyCar = function(){
    this.stack = [];//set stack
    this.priv = "BC_"; // 資料庫標頭


    this.setStack = function(input){
        window.sessionStorage.setItem("BC_" + this.stack.length.toString(), input);
        //create a pair of session key and session value ex: 'BC_0','BC_1' 
        this.stack.push(input);
        //push current data into stack
    }

    this.getStack = function(){
        return this.stack;
        //return all the value in the stack
    }


    //init setting
    this.initStack = function(){
        for(i = 0; i < window.sessionStorage.length; i++){
            var wsValue = window.sessionStorage.key(i);

            if(wsValue.substr(0, this.priv.length) == this.priv){
                this.stack.push(window.sessionStorage[wsValue]);
            }
        }
    }

    this.finishStack = function(){
        var number = 0;
        do{
            number = this.recursive(number);
        }while(number != this.stack.length - 1);
            this.stack = null;
    }


    this.recursive = function(number){
        for(var i = 0; i < window.sessionStorage.length; i++){
            var wsValue = window.sessionStorage.key(i);
            //assign a variable wsvalue equal to the session keys.
            if(wsValue.substr(0, this.priv.length) == this.priv){
                //if    
                window.sessionStorage.removeItem(wsValue);
                return number + 1; 
            }
        }
    }  


    this.sentStack = function(){
    // ajax request to process.php
        $.ajax({
            url:"/getbookdata",
            type:"POST",
            data:{
              list:$(this).getStack()
            },
            success:function(data){
                console.log(data);
                $("#list").html(data.results);
            },
            error:function(data){
                console.log("ajax failed", data);
            }
        });
    }
}