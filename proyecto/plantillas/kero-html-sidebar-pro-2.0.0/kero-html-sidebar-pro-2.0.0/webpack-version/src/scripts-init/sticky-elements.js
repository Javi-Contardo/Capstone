// Imports

import 'sticky-kit/dist/sticky-kit';

// Sticky Elements

$(document).ready(() => {

    $(window).scroll(function(){

        $('.app-layout__sidebar-inner .nav.flex-column').stick_in_parent({
            offset_top: 20
        });

        $('.stick-to-parent').stick_in_parent({
            offset_top: 86
        });

    });

});
