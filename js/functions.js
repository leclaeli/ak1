(function($){

	$(function() {
		// localStorage.removeItem('ex');
	
		// if ( $( "#select-student" ).length ) {
		// 	localStorage.st = $('#select-student').val();
		// } else {
		// 	localStorage.st = "";
		// }



		$( '#autocomplete' ).val( ak_localize.user_address );

		// datepicker
		$( "#datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	
		$( ".interest-title" ).click(function(event) {
			$( this ).next( ".hide-interests" ).slideToggle();
		});


		$( "#my-menu" ).mmenu({
			extensions: ["iconbar", "widescreen"],
			slidingSubmenus: false,
			navbar: {
				add: true,
			}
		});

		var API = $("#my-menu").data( "mmenu" );

		$(window).resize(function() {
			// This will fire each time the window is resized:
			if( $(window).width() >= 915 ) {
				$( "body" ).addClass( 'ak-opened-menu' ).removeClass( 'ak-closed-menu' );

				$( "#my-menu" ).on( "click", "#hamburger", function() {
					$( "body" ).toggleClass( 'ak-opened-menu ak-closed-menu' );
				});

				$( "#hamburger" ).click(function(event) {   
					API.closeAllPanels();
				});

				$( ".mm-vertical > li" ).not(':eq(0)').click(function() {
					if ( $( 'body' ).hasClass( 'ak-closed-menu' ) ) {
						$( "body" ).removeClass( 'ak-closed-menu' ).addClass( 'ak-opened-menu' );
					}
				});
				
			} else {
				$( "body" ).removeClass( 'ak-opened-menu ak-closed-menu' );
				
				$( ".mm-vertical > li" ).click(function() {
					API.open();
				});

				$( "#hamburger" ).click(function() {
					API.close();
					API.closeAllPanels();
				});
			} 
		}).resize(); // This will simulate a resize to trigger the initial run.
	
		$( 'form.filter-preferences :input:not("#autocomplete")' ).first().trigger('change');

	}); // end $(function() - self calling (on ready)

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
		//window.progLocation = [];
		$markers.each(function(index){
			add_marker( $(this), map, index );
		});
		// center map
		center_map( map );

		google.maps.event.addListener(map, 'tilesloaded', function(evt) {
			$('.asapkids-loading').hide();
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
	
	function add_marker( $marker, map, index ) {
		
		// var
		var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
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

		centerMarkerLabels();
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

	function getOrigin() {
		var origin1 = $( '#autocomplete' ).val();
		return origin1;
	}
	
	function programLocations() {
		window.progLocation = [];
		var $markers = $( '#programs-map' ).find( '.marker' );
		// get the lat and lng to calculate distances with
		$markers.each(function(index){
			var onlyLatLng = $( this ).attr('data-lat') + ', ' + $( this    ).attr('data-lng');
			progLocation[index] = onlyLatLng;
		});
	}	

	function calculateDistances() {
		programLocations();
	  	var service = new google.maps.DistanceMatrixService();
	  	service.getDistanceMatrix( {
		  	origins: [getOrigin()],
		  	destinations: progLocation,
		  	travelMode: google.maps.TravelMode.DRIVING,
		  	unitSystem: google.maps.UnitSystem.IMPERIAL,
		  	avoidHighways: false,
		  	avoidTolls: false
		}, 
		callback );
	}

	function callback(response, status) {
		if (status != google.maps.DistanceMatrixStatus.OK) {
			console.log('Error was: ' + status);
			howFarIsIt();
		} else {
			if ( $(".program-list.pinned").length ) {
				var origins = response.originAddresses;
				var destinations = response.destinationAddresses;
				for (var i = 0; i < origins.length; i++) {
					var results = response.rows[i].elements;
					//addMarker(origins[i], false);
					for (var j = 0; j < results.length; j++) {
						document.getElementsByClassName('program-list pinned')[j].setAttribute("data-distance", results[j].distance.value );
						document.getElementsByClassName('distance pinned')[j].innerHTML = results[j].distance.text;
					}
				}
				howFarIsIt();
			} else {
				//asapkidsRenderMapOnce();
				myLateFunction();
			}
		}
	}

	// Distance filter based on user input
	function howFarIsIt() {
		$( '.program-list.pinned').each(function(index, el) {
			var howFar = $( el ).attr('data-distance');
			var progId = $( el ).attr( 'data-id' );
			var maxDistance = $( '#select-distance' ).val();
			if (howFar > parseInt( maxDistance ) ) {

				$( el ).remove();
				$( '.marker[id="' + progId + '"]' ).remove();
			};
			// render the map
		});
		//asapkidsRenderMapOnce();
		myLateFunction();
	}    

	$.firstTime = true; // global

	function asapkidsRenderMapOnce() {
		if ( $.firstTime == true ) {
			$('.acf-map').each(function() {
				render_map( $(this) );
			});
			$('.asapkids-loading').text('Loading...').show();
			$.firstTime = false;
		}
	}
	 
	// Count results (needs to run after google maps) - called from howFarIsIt()
	function myLateFunction() {

		var totalResults = $( '.program-list' ).filter(':visible').length;
		if ( totalResults !== 1 ) {
			
			$( '.total-results' ).text( "Showing " + totalResults + " results" );
		} else {
			$( '.total-results' ).text( "Showing " +  totalResults + " result" );
		}
		
		if ( totalResults == 0 || !$( '.program-list.pinned').length ) {
			$( "#map-view" ).hide();
		} else {
			$( "#map-view" ).show();
		} 
		// if ( !$( '.program-list.pinned').length ) {
		// 	$( "#map-view" ).hide();
		// }       
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

		asapkidsRenderMapOnce();
	});

	$( '.asapkids-search-info' ).on('click', '#list-view:not(.clicked)', function(event) {
		event.preventDefault();
		$('.asapkids-loading').text('Loading...').hide();
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

	// submit forms
	$('.search-field').keyup(function(event) { // main search in header
		var fieldText = $( this ).val();
		$( '#filter-search' ).val( fieldText ); // hidden field on filter sidebar
	});
	$('.search-form').submit(function(event) {
		/* Act on the event */
		if ( $( '.filter-preferences' ).length ) {
			event.preventDefault();
			$( '.filter-preferences' ).submit();
		}
	});

	if ( $('.search-field' ).val() !== "" ) {
		$( '.clear-search' ).show();
	} else {}

	$( '.clear-search' ).click(function(event) {
		$('form.filter-preferences :input').each(function(index, el) {
			$( el ).val( "" );
		});
		for (var i = 0; i < localStorage.length; i++){
			localStorage.setItem(localStorage.key(i), "");
			lsValue = localStorage.getItem(localStorage.key(i));
			// console.log(localStorage.key(i) + ':' + lsValue);
		}
		$( '.search-field' ).val( "" ).trigger( 'keyup' );
		$( '.filter-preferences' ).submit();
	});

	// function initialize() {
		
	// 	function isEmpty( el ){
	// 		return $.trim( el.html() );
	// 	}
	// 	// if no user address is given don't calculate the distances
	// 	if ( isEmpty( $('#autocomplete') ) ) {
	// 		$('.acf-map').each(function() {
	// 			render_map( $(this) );
	// 		});
	// 		myLateFunction();
	// 	} else {
	// 		calculateDistances();
	// 	}

	// 	autocompleteObj();
	// }

	$('#autocomplete').focus(function(event) {
	 	autocompleteObj();
	 	geolocate();
	});

	// Create the autocomplete object, restricting the search to geographical location types.
	function autocompleteObj() {

		autocomplete = new google.maps.places.Autocomplete(
			/** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
			{ types: ['geocode'] 
		});
		// When the user selects an address from the dropdown,
		// populate the address fields in the form.
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			//fillInAddress();
  		});
	}

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

	// [START region_fillform]
	function fillInAddress() {
	  // Get the place details from the autocomplete object.
	  var place = autocomplete.getPlace();

	  for (var component in componentForm) {
		document.getElementById(component).value = '';
		document.getElementById(component).disabled = false;
	  }

	  // Get each component of the address from the place details
	  // and fill the corresponding field on the form.
	  for (var i = 0; i < place.address_components.length; i++) {
		var addressType = place.address_components[i].types[0];
		if (componentForm[addressType]) {
		  var val = place.address_components[i][componentForm[addressType]];
		  document.getElementById(addressType).value = val;
		}
	  }
	}
	// [END region_fillform]

	// [START region_geolocation]
	// Bias the autocomplete object to the user's geographical location,
	// as supplied by the browser's 'navigator.geolocation' object.
	function geolocate() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		  			var circle = new google.maps.Circle({
						center: geolocation,
						radius: position.coords.accuracy
		  			});
		  		autocomplete.setBounds(circle.getBounds());
			});
	  	}
	}
	// [END region_geolocation]

	$( '#autocomplete' ).change(function() {
		$( '#autocomplete' ).delay(500).queue( function() {
			$( 'form.filter-preferences :input:not("#autocomplete")' ).first().trigger('change');
			$( this ).dequeue();
		});
	});
	
	$.pageLoad = true; // global

	// Detect changes made to any of the filter menu fields and post the form to rerun the query
	
	$('body').on('change', 'form.filter-preferences :input:not("#autocomplete")', function(e) {
	
	// $('form.filter-preferences :input').change(function(e){
		
		//change the "Student" label to "Custom Search" when any field is changed
		// if($('.asapkids-student-name').text() !== 'Student' && $('.asapkids-student-name').text() !== 'Custom Search') {
		// 	$('#select-student option:selected').removeAttr('selected');
		// 	$('#select-student').val("");
		// 	$('.asapkids-student-name').text('Custom Search');
		// }	 
		
		//initiate arrays that will hold checkboxes if they haven't already been
		if(typeof ai == 'undefined') {
			var ai = [];
		}
		
		if(typeof dow == 'undefined') {
			var dow = [];
		}
		
		if(typeof ex == 'undefined') {
			var ex = [];
		}
		
		//populate checkbox arrays
		$("input:checkbox[name='ai[]']").each(function(index, element){
			if($(element).is(':checked')) {
				ai.push($(element).val());
			}
		});
		
		$("input:checkbox[name='dow[]']").each(function(index, element){
			if($(element).is(':checked')) {
				dow.push($(element).val());
			}
		});	 
		
		$("input:checkbox[name='ex[]']").each(function(index, element){
			if($(element).is(':checked')) {
				ex.push($(element).val());
			}
		});
	
		localStorage.ai = JSON.stringify(ai);
		localStorage.dow = JSON.stringify(dow);
		localStorage.ex = JSON.stringify(ex);
		
		//populate non-checkbox inputs



		$('form.filter-preferences :input').not(':checkbox').each(function(index, element) {
				
			if(element.name == 'sd') {
				sd = $(element).val();
				localStorage.sd = sd;
			}
			if(element.name == 'age') {
				age = $(element).val();
				localStorage.age = age;
			}
			if(element.name == 'addy') {
				addy = $(element).val();
				localStorage.addy = addy;
			}
			if(element.name == 'pr') {
				pr = $(element).val();
				localStorage.pr = pr;
			}
			if(element.name == 'di') {
				di = $(element).val();
				localStorage.di = di;
			}
			if(element.name == 's') {
				s = $(element).val();
				localStorage.s = s;
			}
			// if(element.name == 'st') {
			// 	localStorage.st = $(element).val();
			// }
		});

		st = "";

		// On initial page load 'st' equals current value of 'localStorage.st'
		if ( $.pageLoad == true ) {
			if( localStorage.st == 'undefined') {
				st = "";
				localStorage.st = "";
			} else {
				st = localStorage.st;
			}
			// console.log( 'st=' + st + ' | localStorage.st = ' + localStorage.st);
			$.pageLoad = false;
		} else { // on subsequent ajax loads 'st' and 'localStorage.st' will only change if the value of "#select-student" has changed. Otherwise changing a value on another field when a student is selected will not change the "#select-student" value to "" ("Custom Search").	
			stOnChange = $('#select-student').val();
			st = "";
			if ( $( "#select-student").length ) {
				if ( stOnChange !== localStorage.st ) {
					st = stOnChange;
					//localStorage.st = stOnChange;
				}
			} 	
		}

		// if ( $( "#select-student" ).length && $.pageLoad == false ) {
		// 	stOnChange = $('#select-student').val();
		// 	if (stOnChange !== localStorage.st) {
		// 		st = stOnChange;
		// 		localStorage.st = stOnChange;
		// 	} else {
		// 		st = "";
		// 	}
		// } else {
		// 	st = "";
		// }

		data = {
			action: 'filter_results',
			ai: ai,
			dow: dow,
			sd: sd,
			age: age,
			addy: addy,
			pr: pr,
			ex: ex,
			di: di,
			s: s,
			st: st,		
		};
		
		$('.asapkids-loading').text('Loading...').show();
		
		$.post(filter_options.ajax_url, data,
		function(response){
			add_query_params_url();
			var myJson = $($.parseJSON($($.parseHTML(response)).filter("#json_data").text()));
			
			var success_main = $($.parseHTML(response)).filter("#primary");
			var success_map  = $($.parseHTML(response)).find("#programs-map");
			//$('#programs-map').remove();
			//$('.asapkids-loading').hide();	
			$('#main').remove(); 
			$('#primary').html($(success_main).html());
			$('#programs-map').html($(success_map).html());
			$.firstTime = true;
			
			$('.asapkids-student-name').text(myJson[0].name);
			$('#select-student').val(myJson[0].st);
			localStorage.st = $('#select-student').val();
			$('#age').val(myJson[0].age);
			$('#autocomplete').val(myJson[0].addy);
			$('#select-distance').val(myJson[0].di);
			$("input:checkbox[name='ex[]']").prop( "checked", false);
			$("input:checkbox[name='ex[]']").val(myJson[0].ex);
			$("input:checkbox[name='dow[]']").prop( "checked", false);
			$("input:checkbox[name='dow[]']").val(myJson[0].dow);
			$("input:checkbox[name='ai[]']").prop( "checked", false);
			$("input:checkbox[name='ai[]']").val(myJson[0].ai);
			
			$( '#map-view' ).removeClass('clicked');
			if ( $( '.program-list' ).length ) {
				calculateDistances();
			} else {;
				myLateFunction();
				
				var result_count = 0;

				$('.asapkids-result').each(function() {
					result_count = result_count + 1;
				});
			}
			$('.asapkids-loading').hide();
		});
	});
	
	$( '.sign-out' ).click(function(event) {
		localStorage.clear();
	});

	function add_query_params_url() {
		//var myURL = document.location;
		if (history.pushState) {
			var newurl = 'http://' + window.location.hostname + '/wordpress/?' + locationHref();
		    //var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + locationHref();
		    window.history.pushState({path:newurl},'',newurl);
		}
	}

	function locationHref() {

		var localS = ['s','st','sd','age','addy','pr','di','ai','ex','dow'];
		$.each(localS, function(index, val) {
			var lsKey = localStorage.getItem(val);
			if (lsKey == "undefined" || lsKey == null) {
				localStorage.setItem( val, "");
			}
		});

		// If object isn't empty parse JSON. Chrome throws an error otherwise.
		aiParam = localStorage.ai != "" ? JSON.parse(localStorage.ai) : "";
		dowParam = localStorage.dow != "" ? JSON.parse(localStorage.dow) : "";
		exParam = localStorage.ex != "" ? JSON.parse(localStorage.ex) : "";
		
		var akQueryParams = $.param( {
				s : localStorage.s, 
				st : localStorage.st, 
				sd : localStorage.sd, 
				age : localStorage.age, 
				addy : localStorage.addy, 
				pr : localStorage.pr, 
				di : localStorage.di,
				ai : aiParam,  
				dow : dowParam,
				ex : exParam, 
			}, 
			false 
		);

		return akQueryParams;
	}

	function backToResultsUrl() {
		var currentUrl = 'http://' + window.location.hostname + '/wordpress/?' + locationHref();
		location.href = currentUrl;
	}

   $( ".back-to-results" ).click(function(event) {
   		backToResultsUrl();
   }); // End EL added...
   
   //using jQuery to hide "Apply Filters" button, this way if user has javascript disabled, search filtering still works
	$('#view-results').hide();
	
})(jQuery)