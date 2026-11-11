(function($){

	wp.customize( 'orbital_link_color', function( value ) {
		value.bind( function( newval ) {
			$('.entry-content a, .entry-aside a').css('color', newval );
		} );
	} );

	wp.customize( 'orbital_navbar_background', function( value ) {
		value.bind( function( newval ) {
			$('.site-header, nav.site-navigation').css('background-color', newval );
		} );
	} );

	wp.customize( 'orbital_navbar_link_color', function( value ) {
		value.bind( function( newval ) {
			$('.site-header a').css('color', newval );
		} );
	} );

	wp.customize( 'orbital_layout_container', function( value ) {
		value.bind( function( newval ) {
			console.log(newval);
			$('.container').css('width', newval + 'rem' );
		} );
	} );

	wp.customize( 'orbital_typo_logo', function( value ) {
		value.bind( function( newval ) {
			$('head').append('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='+newval+'" type="text/css" />');
			$('.site-logo a').css('font-family', newval );
		} );
	} );

	wp.customize( 'orbital_typo_headings', function( value ) {
		value.bind( function( newval ) {
			$newvalarray = newval.split(':');
			console.log($newvalarray);
			$('head').append('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='+newval+'" type="text/css" />');

			$('h1,h2,h3,h4,h5,h6,.site-header, .jumbotron .title').css({
				"font-family": $newvalarray[0],
				"font-weight": $newvalarray[1],
			});
		} );
	} );

	wp.customize( 'orbital_typo_body', function( value ) {
		value.bind( function( newval ) {
			$newvalarray = newval.split(':');
			console.log(newval);
			$('head').append('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='+newval+'" type="text/css" />');
			$('body').css({
				"font-family": $newvalarray[0],
				"font-weight": $newvalarray[1],
			});
		} );
	} );

	wp.customize( 'orbital_typo_logo', function( value ) {
		value.bind( function( newval ) {
			$newvalarray = newval.split(':');
			$('head').append('<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='+newval+'" type="text/css" />');
			$('.site-brand a').css({
				"font-family": $newvalarray[0],
				"font-weight": $newvalarray[1],
			});
		} );
	} );

	wp.customize( 'orbital_layout_relation', function( value ) {
		value.bind( function( newval ) {
			$('.entry-content').css('flex-basis', 100 - newval + '%');
			$('.entry-content').css('max-width', 100 -  newval + '%');
			$('.entry-aside').css('flex-basis', newval + '%');
			$('.entry-aside').css('max-width', newval + '%');
		} );
	} );

	wp.customize( 'orbital_layout_sidebar_order', function( value ) {
		value.bind( function( newval ) {
			console.log(newval);
			$('.entry-aside').css('order', newval );
		} );
	} );

	wp.customize( 'orbital_loop_read_more', function( value ) {
		value.bind( function( newval ) {
			$('.entry-read-more').html(newval);
		} );
	} );
	
})(jQuery);
