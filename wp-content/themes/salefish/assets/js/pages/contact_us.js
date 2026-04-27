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
				"https://www.google.com/maps/place/SaleFish/@43.81222,-79.5303027,17z/data=!3m2!4b1!5s0x882b2f0f9f26ad5d:0x5d7ceb7d2cfbb7ab!4m6!3m5!1s0x882b2f0fa479e625:0xb7be17627d10ba9f!8m2!3d43.81222!4d-79.5277278!16s%2Fg%2F11bxfkmkzj?entry=ttu&g_ep=EgoyMDI2MDQxOS4wIKXMDSoASAFQAw%3D%3D",
				"_blank"
			);
		});
	}

	window.initMap = initMap;
}