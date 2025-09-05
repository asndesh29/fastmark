let map = null;
let infoWindow = null;
let markers = [];

function initMap(defaultLocation) {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: defaultLocation,
    });

    infoWindow = new google.maps.InfoWindow({
        content: "Click the map to get Lat/Lng!",
        position: defaultLocation,
    });

    // Initialize geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };
                map.setCenter(userLocation);
                infoWindow.setPosition(userLocation);
                infoWindow.setContent("Click the map to get Lat/Lng!");
                infoWindow.open(map);
            },
            () => handleLocationError(true, infoWindow, map.getCenter())
        );
    } else {
        handleLocationError(false, infoWindow, map.getCenter());
    }

    // Initialize search box
    const input = document.getElementById("map-input");
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (!places.length) return;

        markers.forEach(marker => marker.setMap(null));
        markers = [];

        const bounds = new google.maps.LatLngBounds();
        places.forEach(place => {
            if (!place.geometry || !place.geometry.location) return;

            const marker = new google.maps.Marker({
                map,
                title: place.name,
                position: place.geometry.location,
            });
            markers.push(marker);

            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('longitude').value = place.geometry.location.lng();

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }

            marker.addListener('click', () => {
                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();
                infoWindow.setPosition(place.geometry.location);
                infoWindow.setContent(place.name || "Selected location");
                infoWindow.open(map);
            });
        });

        map.fitBounds(bounds);
    });

    map.addListener('click', (event) => {
        const coordinates = event.latLng.toJSON();
        document.getElementById('latitude').value = coordinates.lat;
        document.getElementById('longitude').value = coordinates.lng;
        infoWindow.setPosition(event.latLng);
        infoWindow.setContent(JSON.stringify(coordinates, null, 2));
        infoWindow.open(map);
    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? "Click the map to get Lat/Lng!"
            : "Error: Your browser does not support geolocation."
    );
    infoWindow.open(map);
}
