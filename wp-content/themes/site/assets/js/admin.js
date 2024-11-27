(function($){

    $(window).on('load', function(){

        $('#menu-to-edit').each(function(){
            
            $('.menu-item-settings .field-css-classes', this).remove();
            $('.menu-item-settings .field-xfn', this).remove();
            $('.menu-item-settings .field-link-target', this).remove();
            $('.menu-item-settings .field-title-attribute', this).remove();

            $('.menu-item-depth-0 .acf-fields', this).remove();
            $('.menu-item-depth-2 .acf-fields', this).remove();
            $('.menu-item-depth-3 .acf-fields', this).remove();
            $('.menu-item-depth-4 .acf-fields', this).remove();

            // $('.menu-item-edit-inactive .acf-fields', this).remove();
        });
        
    });

})(jQuery);