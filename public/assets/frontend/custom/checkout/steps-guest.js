$(document).ready(function () {
    var stepscount = 0;
    var counter;
    $(document).on("change",".btn-address",function(e){
        $("#address-error").hide();
        var address_id = this.value;
        $.ajax({
            url:"/products/check-availability",
            data:{'address_id':address_id},
            error:function(data, textStatus, xhr){

            },
            success:function(data, textStatus, xhr){
                if(xhr.status == 200){
                    var index = $(".btn-address").parents(".checkout-item").index();
                    if($(".btn-address").parents(".checkout-item").next().hasClass("completed")){
                        if(stepscount == 3){
                            var indexCount = $(".btn-address").index();
                            if(indexCount == 0){
                                $(".btn-address").parents(".checkout-item").addClass("completed").removeClass("active");
                                $(".btn-address").parents(".checkout-item").next().addClass("active").removeClass("completed");
                            }
                        }
                        else if(stepscount == 2){
                            var indexCount = $(".btn-address").index();
                            if(indexCount == 0){
                                $(".btn-address").parents(".checkout-item").addClass("completed").removeClass("active");
                                $(".btn-address").parents(".checkout-item").next().addClass("active").removeClass("completed");
                            }
                        }
                        else if(stepscount == 1){
                            var indexCount = $(".btn-address").index();
                            if(indexCount == 0){
                                $(".btn-address").parents(".checkout-item").addClass("completed").removeClass("active");
                                $(".btn-address").parents(".checkout-item").next().addClass("active").removeClass("completed");
                            }
                        }
                    }
                    else{
                        $('#add_new_address')[0].reset();
                        var index = $(".btn-address").parents(".checkout-item").index();
                        $(".btn-address").parents(".checkout-item").addClass("completed");
                        $(".btn-address").parents(".checkout-item").removeClass("active");
                        $(".btn-address").parents(".checkout-item").next().addClass("active");
                        $(".checkout-steps ul li.next").eq(index).addClass("completed");
                        $('html,body').animate({scrollTop: $(".delivery-type")},'slow');
                        selectDeliveryType();
                        stepscount = stepscount + 1;
                    }
                }else{
                    if(xhr.status == 204){
                      if(counter  >= 1){
                        $(".next-btn").css("display","inline");
                      }
                        $("#address-error").show();
                        stepscount = 0;
                        var index = $(".btn-address").parents(".checkout-item").index();
                        if($(".btn-address").parents(".checkout-item").hasClass("active")){
                            $(".btn-address").parents(".checkout-item").addClass("active");
                            $(".checkout-steps ul li.next").nextAll().removeClass("completed").removeClass("active");
                            $(".btn-address").parents(".checkout-item").nextAll().removeClass("completed").removeClass("active");
                        }
                    }
                }
            },
            type: "POST"
        });
    });
    $(document).on("click",".delivery-type input, .next-btn",function(e){
        counter = stepscount;
        $('#add_new_address')[0].reset();
        $('#at_post').val('');
        $(".next-btn").css("display","none");
        if($(this).parents(".checkout-item").next().hasClass("completed")){
            if(stepscount == 3){
                var indexCount = $(this).index();
                if(indexCount == 0){
                    $(this).parents(".checkout-item").addClass("completed").removeClass("active");
                    $(this).parents(".checkout-item").next().addClass("active").removeClass("completed");
                }
            }
            else if(stepscount == 2){
                var indexCount = $(this).index();
                if(indexCount == 0){
                    $(this).parents(".checkout-item").addClass("completed").removeClass("active");
                    $(this).parents(".checkout-item").next().addClass("active").removeClass("completed");
                }
            }
        }
        else{
            var index = $(this).parents(".checkout-item").index();
            $(this).parents(".checkout-item").addClass("completed");
            $(this).parents(".checkout-item").removeClass("active");
            $(this).parents(".checkout-item").next().addClass("active");
            $(".checkout-steps ul li").eq(index+1).addClass("completed");
            stepscount = stepscount + 1;
        }
    });
    $(document).on("click","#continue_to_payment",function(e){
        e.preventDefault();
        verifyOrderSummery();
        if($(this).parents(".checkout-item").next().hasClass("completed")){
            stepscount = stepscount;
        }
        else{
            stepscount = stepscount + 1;
        }
        codLimitCheck();
    });
    $(document).on("click",".f-pass",function(){
        $("#login-step2").hide();
        $(".reset-pass-form1").show();
    });
    $(document).on("click",".reset-continue",function(){
        $(".reset-pass-form1").css("display","none");
        $(".reset-pass-form2").css("display","block");
    });
    $(document).on("click",".checkout-steps ul li.next",function(){
        $('#add_new_address')[0].reset();
        var index = $(this).index();
        if(stepscount == 1){
            if(index == 1){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
            }
            if(index == 2){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
            }
        }
        else if(stepscount == 2){
            if(index == 1){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
            if(index == 2){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
            if(index == 3){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
            }
        }
        else if(stepscount == 3){
            if(index == 1){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(4).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
            if(index == 2){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(4).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
            if(index == 3){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(4).addClass("completed").removeClass("active");
            }
            if(index == 4){
                $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
        }
        else{
            $(".checkout-items-wrap .checkout-item").eq($(this).index()).addClass("active").removeClass("completed");
            $(".checkout-items-wrap .checkout-item").eq($(this).index()).nextAll().removeClass("active").removeClass("completed");
            $(this).nextAll().removeClass("completed");
        }
    });
    $(document).on("click",".btn-resetpassword",function(){
        $("#header").removeClass("fixed");
    });
    $(".address-block .address-list").mCustomScrollbar();
    $(document).on("click",".checkout-steps ul li a",function(){
        // console.log($(this).parent().index());
    });
    $(document).on("click",".checkout-item .title .prev",function(){
        if(stepscount == 1){
            var index = $(this).parents(".checkout-item").index();
            if(index == 1){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
            }
            else if(index == 2){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
            }
        }
        else if(stepscount == 2){
            var index = $(this).parents(".checkout-item").index();

            if(index == 1){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
            }
            else if(index == 2){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
            else if(index == 3){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
            }
        }
        else if(stepscount == 3){
            var index = $(this).parents(".checkout-item").index();

            if(index == 1){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(4).addClass("completed").removeClass("active");
            }
            else if(index == 2){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(4).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }
            else if(index == 3){
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(4).addClass("completed").removeClass("active");
            }
            else if(index == 4){
                codLimitCheck();
                $(this).parents(".checkout-item").addClass("active").removeClass("completed");
                $(".checkout-items-wrap .checkout-item").eq(1).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(2).addClass("completed").removeClass("active");
                $(".checkout-items-wrap .checkout-item").eq(3).addClass("completed").removeClass("active");
            }

        }
        else{
            var index = $(this).parents(".checkout-item").index();
            $(this).parents(".checkout-item").addClass("active");
            $(this).parents(".checkout-item").removeClass("completed");
            $(this).parents(".checkout-item").nextAll().removeClass("active");
            $(this).parents(".checkout-item").nextAll().removeClass("completed");
            $(".checkout-steps ul li").eq(index).addClass("completed");
            $(".checkout-steps ul li").eq(index).nextAll().removeClass("completed");
            $(this).nextAll().removeClass("completed");
        }
    });
    $('#modal_edit_address').on('shown.bs.modal', function (e) {
        $(".logo-wrap").css("z-index","1");
    })
});

function changeStep(stepId){
    var index = $(stepId).parents(".checkout-item").index();
    $(stepId).parents(".checkout-item").addClass("completed");
    $(stepId).parents(".checkout-item").removeClass("active");
    $(stepId).parents(".checkout-item").next().addClass("active");
    $(".checkout-steps ul li").eq(index+1).addClass("completed");
    //$('html,body').animate({scrollTop: $(".delivery-address").offset().top},'slow');
}