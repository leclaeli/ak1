(function($){
    $(function() {
    
	/*
	*  render_map
	*
	*  This function will render a Google Map onto the selected jQuery element
	*
	*  @type    function
	*  @date    8/11/2013
	*  @since   4.3.0
	*
	*  @param   $el (jQuery element)
	*  @return  n/a
	*/
	function render_map( $el ) {

	    // var
	    var $markers = $el.find('.marker');

	    programName = [];
	    $('.program-name').each(function(index, el) {
	        programName[index] = $(this).html();
	    });

	    // vars
	    var args = {
	        zoom        : 16,
	        center      : new google.maps.LatLng(0, 0),
	        mapTypeId   : google.maps.MapTypeId.ROADMAP
	    };

	    // create map               
	    var map = new google.maps.Map( $el[0], args);
	    geocoder = new google.maps.Geocoder();

	    // add a markers reference
	    map.markers = [];

	    // add markers
	    $markers.each(function(index){
	        add_marker( $(this), map, index );
	    });

	    // center map
	    center_map( map );

	    //center labels
	    $( '#map-view' ).one( "click", function(event) {
	        centerMarkerLabels();
	    });
	    
	}

	/*
	*  add_marker
	*
	*  This function will add a marker to the selected Google Map
	*
	*  @type    function
	*  @date    8/11/2013
	*  @since   4.3.0
	*
	*  @param   $marker (jQuery element)
	*  @param   map (Google Map object)
	*  @return  n/a
	*/
	progLocation = [];
	function add_marker( $marker, map, index ) {
	    
	    // var
	    var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
	    var onlyLatLng = $marker.attr('data-lat') + ', ' + $marker.attr('data-lng');
	    progLocation[index] = onlyLatLng;
	    // create marker
	    var marker = new MarkerWithLabel({
	        position: latlng,
	        map: map,
	        draggable: true,
	        labelContent: programName[index],
	        labelAnchor: new google.maps.Point(0, 0),
	        labelClass: "labels", // the CSS class for the label
	        labelStyle: {opacity: 0.75},
	        title : programName[index],
	    });
	    // add to array
	    map.markers.push( marker );

	    // if marker contains HTML, add it to an infoWindow
	    if( $marker.html() )
	    {
	        // create info window
	        var infowindow = new google.maps.InfoWindow({
	            content     : $marker.html()
	        });

	        // show info window when marker is clicked
	        google.maps.event.addListener(marker, 'click', function() {
	            infowindow.open( map, marker );
	        });
	    }

	}

	/*
	*  center_map
	*
	*  This function will center the map, showing all markers attached to this map
	*
	*  @type    function
	*  @date    8/11/2013
	*  @since   4.3.0
	*
	*  @param   map (Google Map object)
	*  @return  n/a
	*/

	function center_map( map ) {

	    // vars
	    var bounds = new google.maps.LatLngBounds();

	    // loop through all markers and create bounds
	    $.each( map.markers, function( i, marker ){
	        var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
	        bounds.extend( latlng );
	    });

	    // only 1 marker?
	    if( map.markers.length == 1 )
	    {
	        // set center of map
	        map.setCenter( bounds.getCenter() );
	        map.setZoom( 16 );
	    }
	    else
	    {
	        // fit to bounds
	        map.fitBounds( bounds );
	    }
	}

	function centerMarkerLabels() {
	    // get width of google maps marker labels and center them
	    $( '.labels' ).each(function(index, el) {
	        var labelWidth = $( el ).width() / 2;
	        var cssLeft = parseInt( $( el ).css( 'left' ) );
	        var newLeft = cssLeft - labelWidth ;
	        $( el ).css('left', newLeft );
	    });
	}

	/*
	*  This function will render each map and calculate distances when the document is ready (page has loaded)
	*/

	$(document).ready(function(){
	    var $markers = $( '#programs-map' ).find( '.marker' );
	    $markers.each(function(index){
	        var onlyLatLng = $( this ).attr('data-lat') + ', ' + $( this    ).attr('data-lng');
	        progLocation[index] = onlyLatLng;
	    });
	    calculateDistances();
	  
	});

    // Calculate Distance - Distance Matrix Service
    $( '#autocomplete' ).val( ak_localize.user_address );
    $(function() {
        $( '#autocomplete' ).blur(function() {
            $( '#autocomplete' ).delay(500).queue( function() {
                calculateDistances();
                $( this ).dequeue();
            });
        });
    });

	function getOrigin() {
	    //var origin1 = $( '#autocomplete' ).val();
	    //RDK DEV 20150723: hard-setting for now, need to pull location dynamically
	    var origin1 = get_user_location.address;
	    console.log('address: '+origin1);
	    return origin1;
	}

	function calculateDistances() {
	  var service = new google.maps.DistanceMatrixService();
	  service.getDistanceMatrix(
	    {
	      origins: [getOrigin()],
	      destinations: progLocation,
	      travelMode: google.maps.TravelMode.DRIVING,
	      unitSystem: google.maps.UnitSystem.IMPERIAL,
	      avoidHighways: false,
	      avoidTolls: false
	    }, callback);
	}

	function callback(response, status) {
	    if (status != google.maps.DistanceMatrixStatus.OK) {
	    console.log('Error was: ' + status);
	    howFarIsIt();
	    } else {
	    var origins = response.originAddresses;
	    var destinations = response.destinationAddresses;
	    var outputDiv = document.getElementById('outputDiv');
	    console.log(destinations);
	    outputDiv.innerHTML = '';
	    //deleteOverlays();

	        for (var i = 0; i < origins.length; i++) {
	            var results = response.rows[i].elements;
	            //addMarker(origins[i], false);
	            for (var j = 0; j < results.length; j++) {
	                //addMarker(destinations[j], true);
	                outputDiv.innerHTML += origins[i] + ' to ' + destinations[j]
	                + ': ' + results[j].distance.text + ' in '
	                + results[j].duration.text + '<br>';
	                document.getElementsByClassName('program-list pinned')[j].setAttribute("data-distance", results[j].distance.value );
	                document.getElementsByClassName('distance pinned')[j].innerHTML = results[j].distance.text;
	            }
	        }
	        howFarIsIt();
	    }
	}

	// Distance filter based on user input
	function howFarIsIt() {
	    $( '.program-list.pinned').each(function(index, el) {
	        var howFar = $( el ).attr('data-distance');
	        var progId = $( el ).attr( 'id' );
	        var maxDistance = ak_localize.distance;
	        if (howFar > parseInt( maxDistance ) ) {
	            $( el ).remove();
	            $( '.marker[id="' + progId + '"]' ).remove();
	            //$( '.marker' ).hide();
	        };
	        // render the map
	    });

	    $('.acf-map').each(function(){
	        render_map( $(this) );
	    });
	    myLateFunction();
	}    
     
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

    }); // End $(function)
    

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

	// document ready
	$(function() {

		// datepicker
		$( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true
        });

		// mmenu
        $( "#my-menu" ).mmenu({
            extensions: ["iconbar", "widescreen"],
            slidingSubmenus: false,
            navbar: {
                add: true,
            }
        });

    }); // end $( function() {} )


	// This example displays an address form, using the autocomplete feature
	// of the Google Places API to help users fill in the information.
	var placeSearch, autocomplete;
	var componentForm = {
		street_number: 'short_name',
		route: 'long_name',
		locality: 'long_name',
		administrative_area_level_1: 'short_name',
		country: 'long_name',
		postal_code: 'short_name'
	};

	function initialize() {
	// Create the autocomplete object, restricting the search
	// to geographical location types.
	autocomplete = new google.maps.places.Autocomplete(
		/** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
		{ types: ['geocode'] });
	}

	// [START region_geolocation]
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function geolocate() {
	  if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
		  var geolocation = new google.maps.LatLng(
			  position.coords.latitude, position.coords.longitude);
		  var circle = new google.maps.Circle({
			center: geolocation,
			radius: position.coords.accuracy
		  });
		  autocomplete.setBounds(circle.getBounds());
		});
	  }
	}
	// [END region_geolocation]

	// Count results (needs to run after google maps) - called from howFarIsIt() in google-maps.js
    function myLateFunction() {
        var totalResults = $( '.program-list' ).filter(':visible').length;
        $( '.total-results' ).text( totalResults );
    }
    
})(jQuery)