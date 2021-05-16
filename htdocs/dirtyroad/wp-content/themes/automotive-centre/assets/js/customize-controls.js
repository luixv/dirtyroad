( function( api ) {

	// Extends our custom "automotive-centre" section.
	api.sectionConstructor['automotive-centre'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );