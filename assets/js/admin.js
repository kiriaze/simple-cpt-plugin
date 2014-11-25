(function($) {

    $(document).ready(function() {


        // Simple Works
        $('#'+simple_theme_slug+'_options-simple_plugin_name_options-rewrite').on('click', function() {
            $('#section-simple_plugin_name_options_rewrite').slideToggle(400);
        });

        if ( $('#'+simple_theme_slug+'_options-simple_plugin_name_options-rewrite:checked').val() !== undefined ) {
            $('#section-simple_plugin_name_options_rewrite').show();
        }

        $('#'+simple_theme_slug+'_options-simple_plugin_name_options-taxonomy').on('click', function() {
            $('#section-simple_plugin_name_tax').slideToggle(400);
        });

        if ( $('#'+simple_theme_slug+'_options-simple_plugin_name_options-taxonomy:checked').val() !== undefined ) {
            $('#section-simple_plugin_name_tax').show();
        }

    });

})(jQuery);