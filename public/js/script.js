$(document).ready(function(){
    $('form.payment_gateway_form').submit(function() {
        $('body').append('<div class="loading_effect"></div><div class="loading_block"></div>');
    });
})