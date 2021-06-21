

function pp_mm_initialize_google() {

	var input = document.getElementById('pp-mm-location');
	var autocomplete = new google.maps.places.Autocomplete(input, { types: ['geocode'] });
	autocomplete.setFields(['geometry', 'formatted_address']);

	google.maps.event.addListener(autocomplete, 'place_changed', function () {

		var place = autocomplete.getPlace();
		//console.log(place);

		document.getElementById('pp-mm-address').value = place.formatted_address;

		var lat = place.geometry.location.lat();
		var lng = place.geometry.location.lng();
		var latlng = lat + ',' + lng;
		document.getElementById('pp-mm-latlng').value = latlng;

	});
}

google.maps.event.addDomListener(window, 'load', pp_mm_initialize_google);

