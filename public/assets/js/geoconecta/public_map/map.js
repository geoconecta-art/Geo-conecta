let iniLat = 19.543454812522057;
let iniLng = -99.23502275276154;
let centerLocation = { lat: iniLat, lng: iniLng };
let map;

// Inicializar mapa
async function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 20,
        center: centerLocation,
        draggableCursor: "default",
        mapTypeId: "satellite",
        styles: setMapStyles(),
    });

    await getAtizapanBounds(map);
    await loopPlans();
}

// Coloca los estilos en el mapa para eliminar las landmarks
function setMapStyles() {
    return [
        {
            featureType: "administrative",
            elementType: "geometry",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "poi",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "road",
            elementType: "labels.icon",
            stylers: [{ visibility: "off" }],
        },
        {
            featureType: "transit",
            stylers: [{ visibility: "off" }],
        },
    ];
}

//Obtiene el poligono correspondiente al municipio de atizapan para acoplar la vista
async function getAtizapanBounds(map) {
    map.data.loadGeoJson("/maps/1_LIMITE_ATIZAPÁN_DE_ZARAGOZA.geojson");

    map.data.addListener("addfeature", async function (event) {
        fitAtizapanBounds(map);
    });

    map.data.setStyle({
        fillColor: "transparent",
        strokeColor: "red",
    });
}

// Ajusta la vista del mapa a los límitesd de Atizapán
async function fitAtizapanBounds(map) {
    var northEast = new google.maps.LatLng(
        19.612226459442805,
        -99.21042012814358
    );
    var southWest = new google.maps.LatLng(
        19.515617135661422,
        -99.35653201255865
    );

    cityBounds = await new google.maps.LatLngBounds(southWest, northEast);
    map.fitBounds(cityBounds);
}
