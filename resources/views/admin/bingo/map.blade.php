@extends('admin.layout.app')
@section('style')
    <style>
        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: normal !important;
        }

        .label {
            position: absolute;
            font-size: 15px;
            font-weight: bold;
            z-index: 1;
        }

        .info-window-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #legend {
            background: #fff;
            padding: 10px;
            margin: 10px;
            border: 1px solid #000;
            border-radius: 10px
        }

        #legend h3 {
            margin-top: 0;
            font-size: 20px;
        }

        #legend em {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">{{ $title }}</h3>
                    </div>

                </div>
            </div>

            <div class="nk-block">
                <div class="card card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner px-0 py-5">
                            
                            <div class="mb-5">
                                <div onclick="initMap('secciones')" class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                    <span>Mapa por secciones</span><em class="icon ni map-big"></em>
                                </div>
                                <div onclick="initMap('regiones')" class="btn btn-outline-light mr-md-2 mb-3 mb-md-0">
                                    <span>Mapa por regiones</span><em class="icon ni map-big"></em>
                                </div>
                            </div>
                            

                            <div id="google-map" style="width: 100%; height: 600px;"></div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function initMap(type) {
            //console.log(type);

            const position = {
                lat: 19.56227,
                lng: -99.25951
            };

            // Inicializar el mapa
            var map = new google.maps.Map(document.getElementById('google-map'), {
                zoom: 13,
                center: position,
            });

            var infoWindow = new google.maps.InfoWindow();

            var propertyMap = null;

            var votantes = null;
            var promovidos = null;


            if (type === "regiones") {
                map.data.loadGeoJson('/maps/3_REGIONES.geojson');
                propertyMap = "REGIÓN";
                votantes = @json($votantesByRegion);
                promovidos = @json($promovidosByRegion);
            } else { //secciones
                map.data.loadGeoJson('/maps/2_SECCIONES_ELECTORALES_2024.geojson');
                propertyMap = "SECCION";
                votantes = @json($votantesBySection);
                promovidos = @json($promovidosBySection);
            }

            //console.log(promovidos);


            map.data.setStyle(function(feature) {
                // Aquí defines cómo obtener el color para cada polígono
                var color = obtenerColorParaFeature(feature);

                return {
                    fillColor: color,
                    fillOpacity: 0.9,
                    strokeWeight: 2,
                };
            });

            map.data.addListener("addfeature", function(event) {
                var feature = event.feature;
                var geometry = feature.getGeometry();

                if (feature.getGeometry().getType() === "MultiPolygon") {
                    var centroid = calculateMultiPolygonCentroid(geometry.getArray());
                    var label = new LabelOverlay(
                        centroid,
                        feature.getProperty(propertyMap),
                        map
                    );
                }
            });

            map.data.addListener("click", function(event) {
                var feature = event.feature;
                var seccion = feature.getProperty(propertyMap);
                var votos = votantes[seccion];
                var totalPromovidos = promovidos[seccion];

                var contentString =
                    '<div class="info-window">' +
                    '<div class="info-window-title">' + propertyMap + ' ' + feature.getProperty(propertyMap) +
                    '</div>' +
                    '<div><strong>Votantes: </strong>' +
                    votos +
                    '/' +
                    totalPromovidos +
                    '</div>' +
                    '<div><strong>' +
                    calcularPorcentaje(votos, totalPromovidos) +
                    '% de votos posibles' +
                    '</strong></div>' +
                    '</div>';
                // Add more properties as needed
                //infoWindow.close();
                infoWindow.setOptions({
                    content: contentString,
                });

                infoWindow.setPosition(event.latLng);
                infoWindow.open(map);
            });

            function calculateMultiPolygonCentroid(multiPolygon) {
                var totalArea = 0;
                var x = 0;
                var y = 0;

                multiPolygon.forEach(function(polygon) {
                    var paths = polygon.getArray()[0].getArray();
                    var area = 0;
                    var cx = 0;
                    var cy = 0;

                    for (var i = 0; i < paths.length; i++) {
                        var j = (i + 1) % paths.length;
                        var point1 = paths[i];
                        var point2 = paths[j];

                        var cross =
                            point1.lat() * point2.lng() - point2.lat() * point1.lng();
                        area += cross;
                        cx += (point1.lat() + point2.lat()) * cross;
                        cy += (point1.lng() + point2.lng()) * cross;
                    }

                    area = area / 2;
                    cx = cx / (6 * area);
                    cy = cy / (6 * area);

                    totalArea += area;
                    x += cx * area;
                    y += cy * area;
                });

                x = x / totalArea;
                y = y / totalArea;

                return new google.maps.LatLng(x, y);
            }

            function calcularPorcentaje(valor1, valor2) {
                // Verificar que valor2 no sea cero para evitar la división por cero
                if (valor2 === 0) {
                    //throw new Error("El valor 2 no puede ser cero");
                    return 0;
                }

                // Calcular el porcentaje
                const porcentaje = (valor1 / valor2) * 100;

                // Retornar el resultado como número flotante
                return parseFloat(porcentaje.toFixed(2));
            }

            function obtenerColorParaFeature(feature) {
                var seccion = feature.getProperty(propertyMap);
                var votos = votantes[seccion];
                var totalPromovidos = promovidos[seccion];
                var porcentaje = calcularPorcentaje(votos, totalPromovidos);

                if (porcentaje > 80) { //80% - 100%
                    return "#637ca1";
                } else if (porcentaje >= 60) { //60% - 79%
                    return "#70a1cd";
                } else if (porcentaje >= 40) { //40% - 59%
                    return "#92c0dd";
                } else if (porcentaje >= 20) { //20% - 39%
                    return "#bedbea";
                } else if (porcentaje > 0) { //1% - 19%
                    return "#e0ebf5";
                } else { //0%
                    return "#f7fafd";
                }
            }

            function LabelOverlay(position, text, map) {
                this.position = position;
                this.text = text;
                this.div = null;
                this.setMap(map);
            }

            LabelOverlay.prototype = new google.maps.OverlayView();

            LabelOverlay.prototype.onAdd = function() {
                var div = document.createElement("div");
                div.className = 'label';
                div.innerHTML = this.text;
                this.div = div;

                var panes = this.getPanes();
                panes.markerLayer.appendChild(div);
            };

            LabelOverlay.prototype.draw = function() {
                var overlayProjection = this.getProjection();
                var position = overlayProjection.fromLatLngToDivPixel(this.position);
                var div = this.div;
                div.style.left = position.x + "px";
                div.style.top = position.y + "px";
            };

            LabelOverlay.prototype.onRemove = function() {
                this.div.parentNode.removeChild(this.div);
                this.div = null;
            };

            const indicadores = [{
                    color: '#f7fafd',
                    text: '0% de votos'
                },
                {
                    color: '#e0ebf5',
                    text: '1% a 19% de votos'
                },
                {
                    color: '#bedbea',
                    text: '20% a 39% de votos'
                },
                {
                    color: '#92c0dd',
                    text: '40% a 59% de votos'
                },
                {
                    color: '#70a1cd',
                    text: '60% a 79% de votos'
                },
                {
                    color: '#637ca1',
                    text: '80% a 100% de votos'
                }
            ];

            const legend = document.createElement('div');
            legend.id = "legend";

            // Contenedor al que se agregarán los divs
            //const legend = document.getElementById('legend');

            const h3 = document.createElement('h3');
            h3.innerHTML = "Indicadores";
            legend.appendChild(h3);
            legend.appendChild(document.createElement('br'));

            // Crear los divs dinámicamente y agregarlos al contenedor
            indicadores.forEach(item => {
                const div = document.createElement('div');
                div.innerHTML =
                    `<em class="icon ni ni-square-fill" style="color: ${item.color}"></em> ${item.text}`;
                legend.appendChild(div);
                legend.appendChild(document.createElement('br'));
            });

            // Obtener el div de referencia
            //const referenceDiv = document.getElementById('reference-div');

            // Insertar el nuevo div antes del div de referencia
            //referenceDiv.insertAdjacentElement('beforebegin', newDiv);


            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

            //var geocoder = new google.maps.Geocoder();

        }
    </script>


    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjnFkAKIPzt-1d0lQsu-SuInc4jqFYRYM&callback=initMap"></script>
@endsection
