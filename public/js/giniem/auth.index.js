jQuery(document).ready(function(){
    
    jQuery('#auth-form').submit(function(event){
        if (jQuery('#username').val() == '') {
            jQuery('#username').focus();
            return false;
        }
        
        if (jQuery('#password').val() == '') {
            jQuery('#password').focus();
            return false;
        }
    })
})