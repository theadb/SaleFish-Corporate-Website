let page = $('main').attr('class');
console.log('page: ', page);
if (page === 'contact_us') {
	let map;

	function initMap() {
		const uluru = { lat: 43.8122352, lng: -79.5276061 };
		map = new google.maps.Map(document.getElementById("map"), {
			center: uluru,
			zoom: 12,
		});
		const icon = {
			url: BASEURL + '/img/marker.png',
			scaledSize: new google.maps.Size(50, 50), // scaled size
		};
		const marker = new google.maps.Marker({
			position: uluru,
			map: map,
			icon: icon
		});

		google.maps.event.addListener(marker, "click", function () {

			window.open(

				"https://www.google.com/maps/place/8395+Jane+St+%23203,+Concord,+ON+L4K+5Y2/@43.8121808,-79.5277303,17z/data=!3m1!4b1!4m5!3m4!1s0x882b2f0f9ebd82b9:0x617bae8e4bdb708b!8m2!3d43.8121808!4d-79.5277303",

				"_blank" // <- This is what makes it open in a new window.

			);

		});
	}

	window.initMap = initMap;
}