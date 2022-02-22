jQuery(document).ready(function($){
    
    var fgsname = '';

    $(window).resize(function() {
        if (window.innerWidth < 720) {
            fgsname = $('.fgs-name').innerHTML;
            $('.fgs-name').replaceWith('X');
        } else if (window.innerWidth > 720) {
            $('.fgs-name').innerHTML = fgsname;
        }
    }).resize();

});