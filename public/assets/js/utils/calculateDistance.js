/**
 * Obtiene la distancia en metros entre dos puntos
 * 
 * @param {Object} ini_pos objeto que contiene la información de las coordenadas del marcador inicial en el formato {lat: 12.12, lng: -99.123}.
 * @param {Object} fin_pos objeto que contiene la información de las coordenadas del marcador final en el formato {lat: 12.12, lng: -99.123}.
 * @returns {Number} distancia representa laa longitud obtenida según las coordenadas.
 */
function calculateDistance( ini_pos, fin_pos ){
    let distancia = 0;

    if( ini_pos && fin_pos ){
        // Radio de la Tierra en metros
        const R = 6371000;
        
        // Convertir las latitudes y longitudes de grados a radianes
        const lat1Rad = ini_pos.lat * (Math.PI / 180);
        const lon1Rad = ini_pos.lng * (Math.PI / 180);
        const lat2Rad = fin_pos.lat * (Math.PI / 180);
        const lon2Rad = fin_pos.lng * (Math.PI / 180);

        // Diferencias
        const deltaLat = lat2Rad - lat1Rad;
        const deltaLon = lon2Rad - lon1Rad;

        // Fórmula de Haversine
        const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                Math.cos(lat1Rad) * Math.cos(lat2Rad) *
                Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        // Distancia en metros
        distancia = Math.round(R * c * 100) / 100;
    }

    return distancia;
}