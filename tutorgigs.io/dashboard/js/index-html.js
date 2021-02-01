jQuery(document).ready(function($){
    $('.bxslider').bxSlider({
      controls: false,
      auto:true
    });
    
    jQuery('.btn-slide-down').click(function(){
        jQuery('html, body').animate({
            scrollTop: $("#p-sec-2").offset().top
        }, 1000);
        return false;
    });
    
	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}
    
    $("#index-login-form #login-submit").click(function(){
        var valid = true
        $("#index-login-form input").each(function(){
            var val_input = $(this).val();
            if(val_input == ""){
                $(this).css('border-color','red');
                $(this).focus();
                valid = false;
                return false;
            }
        });
        
        var email = $("#index-login-form input#login-email").val();
        
		if( ( !isEmail(email) ) && ( email != "" ) ){
			alert('Email is correct!');
            $("#index-login-form input#login-email").focus();
            valid = false;
            return false;
		}
        
        
        if(!valid){
            return false;
        }else{
            var email = $("#index-login-form input#login-email").val();
            var pass = $("#index-login-form input#login-password").val();
            
            var data = {
    			action   : 'login_index',
    			email  : email,
                pass : pass
    		} 
            
    		$.ajax({
    			type	: 'POST',
    			url		: 'questions/inc/p-ajax-login-index-html.php',
    			data	: data,
                dataType: 'json',
    			success	: function(response) {
                    if(response.error)
                        alert(response.error);
                    if(response.msg){
    				    $('div.users').html(response.msg);
                        $('body').removeClass('no-user-login');
                        $('body').addClass('logged');
                        window.location.href="questions/index.php"
                    }
    			}
    		});
            
            return false;
        }
    });
    
    $("#index-login-form input").keyup(function(){
        $(this).css('border-color','#cccccc');
    });
    
    
    // click button menu repondsive 
    
    $('#header .navbar-toggle').click(function(){
        $('.menu.main-menu').slideToggle();
        $('#header .users').slideToggle();
    });
    
    // hover animation main menu
    $(".main-menu ul li").hover(function(){
        $(this).addClass('hover-menu');
    },function(){
        $(this).removeClass('hover-menu');
    })
});