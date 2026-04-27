let page = $('main').attr('class');
if (page === 'contact_us') {
	let map;

	// initMap is async because we use the Maps importLibrary() pattern, which
	// is the modern replacement for the deprecated google.maps.Marker. We
	// import the "marker" library to get AdvancedMarkerElement + PinElement.
	async function initMap() {
		const uluru = { lat: 43.8122352, lng: -79.5276061 };

		// Import the libraries we need (per Google's best practice — pairs
		// with `loading=async&libraries=marker&v=weekly` on the loader URL)
		const { Map } = await google.maps.importLibrary("maps");
		const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

		map = new Map(document.getElementById("map"), {
			center: uluru,
			zoom: 12,
			// AdvancedMarkerElement requires a mapId. "DEMO_MAP_ID" works for
			// development; for production, replace with a real Map ID created
			// in Google Cloud Console (Maps Platform → Map Management).
			mapId: "DEMO_MAP_ID",
		});

		// Brand-coloured pin — replaces the old custom SVG path icon. The
		// PinElement gives us SaleFish purple with a white border, matching
		// the previous look without needing the deprecated icon API.
		const pin = new PinElement({
			background: "#452D8C",
			borderColor: "#ffffff",
			glyphColor: "#ffffff",
			scale: 1.2,
		});

		const marker = new AdvancedMarkerElement({
			position: uluru,
			map,
			content: pin.element,
			title: "SaleFish HQ",
			gmpClickable: true,
		});

		marker.addListener("click", function () {
			window.open(
				"https://www.google.com/maps/place/8395+Jane+St+%23203,+Concord,+ON+L4K+5Y2/@43.8121808,-79.5277303,17z/data=!3m1!4b1!4m5!3m4!1s0x882b2f0f9ebd82b9:0x617bae8e4bdb708b!8m2!3d43.8121808!4d-79.5277303",
				"_blank"
			);
		});
	}

	window.initMap = initMap;
}