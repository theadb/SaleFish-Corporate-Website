let page = $('main').attr('class');
if (page === 'contact_us') {
	let map;

	// Note on AdvancedMarkerElement vs google.maps.Marker:
	// We tried migrating to AdvancedMarkerElement to silence the
	// "google.maps.Marker is deprecated" warning. AdvancedMarkerElement
	// requires a Cloud-created Map ID — the public DEMO_MAP_ID was retired
	// by Google in 2025 and now causes the map to silently fail to render.
	// To get rid of the warning fully, create a Map ID in Google Cloud
	// Console (Maps Platform → Map Management → Create New) and replace
	// the Marker block below with the Advanced version. Until then we
	// stick with Marker (Google: "not scheduled to be discontinued").
	function initMap() {
		const uluru = { lat: 43.8122352, lng: -79.5276061 };
		map = new google.maps.Map(document.getElementById("map"), {
			center: uluru,
			zoom: 12,
		});
		const icon = {
			path: 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z',
			fillColor: '#452D8C',
			fillOpacity: 1,
			strokeColor: '#ffffff',
			strokeWeight: 1.5,
			scale: 2.2,
			anchor: new google.maps.Point(12, 22),
		};
		const marker = new google.maps.Marker({
			position: uluru,
			map: map,
			icon: icon
		});

		google.maps.event.addListener(marker, "click", function () {
			window.open(
				"https://www.google.com/maps/place/8395+Jane+St+%23203,+Concord,+ON+L4K+5Y2/@43.8121808,-79.5277303,17z/data=!3m1!4b1!4m5!3m4!1s0x882b2f0f9ebd82b9:0x617bae8e4bdb708b!8m2!3d43.8121808!4d-79.5277303",
				"_blank"
			);
		});
	}

	window.initMap = initMap;
}