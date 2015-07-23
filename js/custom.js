(function($){
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

        // $(function() {
        //     $('.date-picker').datepicker( {
        //         changeMonth: true,
        //         changeYear: true,
        //         showButtonPanel: true,
        //         dateFormat: 'MM yy',
        //         onClose: function(dateText, inst) { 
        //             var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        //             var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        //             $(this).datepicker('setDate', new Date(year, month, 1));
        //         }
        //     });
        // });
     
// Programs list and map view slider (should be able to consolidate)
        $( '.asapkids-search-info' ).on('click', '#map-view:not(.clicked)', function(event) {
            event.preventDefault();
            /* Act on the event */
            $( this ).addClass('clicked');

            $( '#main' ).animate({
                left: '-100%'
            }, 500, function() {
                $(this).css('left', '150%');
                $(this).appendTo('#primary');
            });

            $( '#main' ).next().animate({
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
                $(this).appendTo('#primary');
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

// submit forms
        $('.search-field').keyup(function(event) {
            /* Act on the event */
            var fieldText = $( this ).val();
            $( '#filter-search' ).val( fieldText );
        });
        $('.search-form').submit(function(event) {
            /* Act on the event */
            event.preventDefault();
            $( '.filter-preferences' ).submit();
        });

// Sort based on distance
        // $( '#sort-results' ).change(function(event) {
        //     if ( $( this ).val() == "distance" ) {
        //         var $programs = $( '#programs-list > ul' ),
        //             $programsli = $programs.children( '.pinned' );
        //         $programsli.sort(function(a,b){
        //             var ad = parseInt( a.getAttribute('data-distance') ),
        //                 bd = parseInt( b.getAttribute('data-distance') );
        //                 console.log(ad + ' : ' + bd);
        //             if(ad > bd) {
        //                 return 1;
        //             }
        //             if(ad < bd) {
        //                 return -1;
        //             }
        //             return 0;
        //         });
        //         $programsli.detach().prependTo( $programs );
        //     } else {
        //         $( '#sort-form' ).submit();
        //     }
        // });



// mmenu

        $("#my-menu").mmenu({
            extensions: ["iconbar", "widescreen"],
            slidingSubmenus: false,
            navbar: {
                add: true,
            }
        });



    }); // End $(function)
})(jQuery)

// Count results (needs to run after google maps) - called from howFarIsIt() in google-maps.js
        function myLateFunction() {
            var totalResults = jQuery( '.program-list' ).filter(':visible').length;
            jQuery( '.total-results' ).text( totalResults );
        }


