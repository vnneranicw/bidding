$(document).ready(function(){
    //bind reload event
    window.setInterval(function(){
        console.log("hi");
    }, 1000);
    
    loadAllItem();
    
    function loadAllItem(){
        $("#itemArea .item").each(function(){
            var itemObj = $(this);
            $.ajax({
                type: "POST",
                url: "function/loadFunction.php",
                data: {'function_name':'timer_value','token':$(itemObj).attr("data-token")},
                dataType: "json",
                cache: false,
                success: function(data)
                {
                    console.log(data);
                }
            });
            
            
            
        });
    }
    
    
    
    
    $("#itemArea .item .item_btn_bid").on("click",function() {
        //alert("you click on me ! my token is " + $(this).parent().attr("data-token"));
        var obj = $(this);
        $(obj).prop("disabled",true);
        setTimeout(function(){ $(obj).prop("disabled", false); }, 2000);//set timer to enable bid button in case no connection to ajax service

        $.ajax({
            type: "POST",
            url: "function/bid.php",
            data: {'token':$(this).parent().attr("data-token")},
            dataType: "json",
            //contentType: "application/json; charset=utf-8",
            cache: false,
            success: function(data)
            {
                $(obj).prop("disabled", false);//reenable bid button immediately after success request from server
                console.log(data);
                
                if(data[0].ack=="1"){
                    //bid success do nothing, ajax refresh will reload latest bidder
                    $(obj).parent().find(".item_bid_status").each(function(){
                        var succObj = $(this);
                        $(succObj).html("Success");
                        $(succObj).show("slow");
                        setTimeout(function(){ $(succObj).hide("slow"); },3000);
                    });

                } else {
                    if(data[0].error.length > 0){
                        alertDialog("Bidding error",data[0].error);
                    } else {
                        alertDialog("Bidding error","Error in bidding, could not retrieve error message at the moment.");
                    }
                }
            }
        });

    });
});