(function($){
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });
     
// Programs list and map view slider (should be able to consolidate)
        $( '.asapkids-search-info' ).on('click', '#map-view:not(.clicked)', function(event) {
            event.preventDefault();
            /* Act on the event */
            $( this ).addClass('clicked');

            $( '#programs-list' ).animate({
                left: '-100%'
            }, 500, function() {
                $(this).css('left', '150%');
                $(this).appendTo('#container');
            });

            $( '#programs-list' ).next().animate({
                left: '0%'
            }, 500);

            $( '#list-view' ).removeClass('clicked');
        });

        $( '.asapkids-search-info' ).on('click', '#list-view:not(.clicked)', function(event) {
            event.preventDefault();
            /* Act on the event */
            $( this ).addClass('clicked');
            $( '#programs-map' ).animate({
                left: '-100%'
            }, 500, function() {
                $(this).css( { 'left': '150%', } );
                $(this).appendTo('#container');
            });

            $( '#programs-map' ).next().animate({
                left: '0%'
            }, 500);

            $( '#map-view' ).removeClass('clicked');
        });
        
// AJAX Test
        $.ajax({
            url: testing.ajax_url,
            type: 'post',
            data: {
                action: 'post_love_add_love',
                post_id: 'Great to be AJAXED', 
            },
            success: function( response ) {
            }
        });

// Accordion
        $( '#accordion li .collapsed' ).slideUp(1);
        $( '#accordion li' ).click(function(e) {
            $(e.target).children('.collapsed').slideToggle();
        });

// submit forms
        $('.search-field').keyup(function(event) {
            /* Act on the event */
            var fieldText = $( this ).val();
            $( '#filter-search' ).val( fieldText );
        });
        $('.search-form').submit(function(event) {
            /* Act on the event */
            event.preventDefault();
            $( '#preferences' ).submit();
        });

// Count results (needs to run after google maps) - called from howFarIsIt() in google-maps.js
        function myLateFunction() {
            var totalResults = jQuery( '.program-list' ).filter(':visible').length;
            jQuery( '.total-results' ).text( totalResults );
            sortDistance();
        }
            
// Sort based on distance
        function sortDistance() {
            console.log('sort distance');
            var $programs = $( '#programs-list > ul' ),
                $programsli = $programs.children( '.pinned' );

            $programsli.sort(function(a,b){
                var ad = a.getAttribute('data-distance'),
                    bd = b.getAttribute('data-distance');

                if(ad > bd) {
                    return 1;
                }
                if(ad < bd) {
                    return -1;
                }
                return 0;
            });

            $programsli.detach().appendTo($programs);
        }
        

    }); // End $(function)
})(jQuery)


