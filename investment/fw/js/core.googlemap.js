function investment_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof INVESTMENT_STORAGE['googlemap_init_obj'] == 'undefined') investment_googlemap_init_styles();
	INVESTMENT_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		INVESTMENT_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: INVESTMENT_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		investment_googlemap_create(id);

	} catch (e) {
		
		dcl(INVESTMENT_STORAGE['strings']['googlemap_not_avail']);

	};
}

function investment_googlemap_create(id) {
	"use strict";

	// Create map
	INVESTMENT_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(INVESTMENT_STORAGE['googlemap_init_obj'][id].dom, INVESTMENT_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in INVESTMENT_STORAGE['googlemap_init_obj'][id].markers)
		INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	investment_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (INVESTMENT_STORAGE['googlemap_init_obj'][id].map)
			INVESTMENT_STORAGE['googlemap_init_obj'][id].map.setCenter(INVESTMENT_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function investment_googlemap_add_markers(id) {
	"use strict";
	for (var i in INVESTMENT_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (INVESTMENT_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (INVESTMENT_STORAGE['googlemap_init_obj'].geocoder == '') INVESTMENT_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			INVESTMENT_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			INVESTMENT_STORAGE['googlemap_init_obj'].geocoder.geocode({address: INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = INVESTMENT_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					INVESTMENT_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						investment_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(INVESTMENT_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: INVESTMENT_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].title;
			INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (INVESTMENT_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				INVESTMENT_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				INVESTMENT_STORAGE['googlemap_init_obj'][id].map.setCenter(INVESTMENT_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in INVESTMENT_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								INVESTMENT_STORAGE['googlemap_init_obj'][id].map,
								INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			INVESTMENT_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function investment_googlemap_refresh() {
	"use strict";
	for (id in INVESTMENT_STORAGE['googlemap_init_obj']) {
		investment_googlemap_create(id);
	}
}

function investment_googlemap_init_styles() {
	// Init Google map
	INVESTMENT_STORAGE['googlemap_init_obj'] = {};
	INVESTMENT_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.investment_theme_googlemap_styles!==undefined)
		INVESTMENT_STORAGE['googlemap_styles'] = investment_theme_googlemap_styles(INVESTMENT_STORAGE['googlemap_styles']);
}