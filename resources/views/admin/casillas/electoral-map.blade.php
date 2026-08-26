@extends('admin.layout.app')
@section('style')
    <style>
        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: normal !important;
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

        .sumas-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sumas {
            flex: 1;
            text-align: center;
            padding: 10px;
        }

        .sumas h3 {
            margin: 0;
            font-size: 1.5em;
        }

        .sumas div {
            font-size: 2em;
            font-weight: bold;
            color: #333;
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
                            <div class="sumas-container">
                                <div class="sumas" id="SumaPan">
                                    <h3>PAN</h3>
                                </div>
                                <div class="sumas" id="SumaMorena">
                                    <h3>MORENA</h3>
                                </div>
                            </div>
                            <div id="google-map" style="width: 100%; height: 600px;"></div>
                            <div id="legend">
                                <h3>Indicadores</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Lista de colores específicos en formato hexadecimal
        //Se usa de manera global para poder acceder en cualquier momento
        var colores = [
            [
                "#9CC4E6", // Azul claro
                "#6DB5D2", // Azul medio
                "#4C93C3", // Azul fuerte
            ],
            [
                "#E9C1E2", // Rosa claro
                "#FF95D6", // Rosa medio
                "#D34BB3" // Rosa fuerte
            ],
            [
                "#FFCB45", // Movimiento ciudadano
                "#BB8A09", // Otros
                "#644800", // Nulos
            ]
        ];

        const names = [
            'PAN',
            'Morena',
            'MC',
            'Otros',
            'Nulos',
        ]

        //Arreglos que contienen la información para darle estilo al mapa
        const votesA =
            @json($votesA); //Arreglo que contiene el número de votos a favor por sección (PAN y coalición)
        const votesC =
            @json($votesC); //Arreglo que contiene el número de votos en contra (morena y coalición)
        const votesMC =
            @json($votesMC); //Arreglo que contiene el número de votos del partido movimiento ciudadano
        const votesN = @json($votesN);
        const votesO = @json($votesO);
        const nvp = @json($nvp);
        const panFields = @json($panFields);
        const morFields = @json($morFields);
        const type = '{!! $type !!}';

        console.log("Pan ");
        let valoresPan = Object.values(votesA);
        // Suma los valores
        let sumaPan = valoresPan.reduce((acumulador, valorActual) => acumulador + valorActual, 0);
        console.log(sumaPan);
        console.log("Morena");
        let valoresMorena = Object.values(votesC);
        // Suma los valores
        let sumaMorena = valoresMorena.reduce((acumulador, valorActual) => acumulador + valorActual, 0);
        console.log(sumaMorena);

        const panSum = document.getElementById("SumaPan");
        const divPan = document.createElement("div");
        divPan.innerHTML = sumaPan;
        panSum.appendChild(divPan);
        panSum.appendChild(document.createElement('br'));

        const morenaSum = document.getElementById("SumaMorena");
        const divMorena = document.createElement("div");
        divMorena.innerHTML = sumaMorena;
        morenaSum.appendChild(divMorena);
        morenaSum.appendChild(document.createElement('br'));



        function initMap() {

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

            map.data.loadGeoJson('/maps/2_SECCIONES_ELECTORALES_2024.geojson');

            map.data.setStyle(function(feature) {
                // Aquí defines cómo obtener el color para cada polígono
                var color = getColorToFeature(feature); // Implementa esta función para devolver el color deseado

                return {
                    fillOpacity: (color == '#000000') ? 0.5 :
                    0.95, //Si los registros siguen en 0's, la opacidad será de 0.5
                    fillColor: color,
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
                        feature.getProperty("SECCION"),
                        map,
                    );
                }
            });

            map.data.addListener("click", function(event) {
                var feature = event.feature;
                var contentString = '<div id="content">' +
                    '<h5 id="firstHeading" class="firstHeading">Sección: ' + feature.getProperty("SECCION") +
                    '</h5>' +
                    '<div id="bodyContent">' +
                    "<h6 class='mt-2'><b>Votos " + names[0] + ": </b>" + votesA[feature.getProperty("SECCION")] +
                    "</h6>" +
                    getRowVotes(feature.getProperty("SECCION"), panFields, 0) +
                    "<h6 class='mt-2'><b>Votos " + names[1] + ": </b>" + votesC[feature.getProperty("SECCION")] +
                    "</h6>" +
                    getRowVotes(feature.getProperty("SECCION"), morFields, 15) +
                    "<h6 class='mt-2'><b>Votos " + names[2] + ": </b>" + votesMC[feature.getProperty("SECCION")] +
                    "</h6>" +
                    "<h6 class='mt-2'><b>Votos " + names[3] + ": </b>" + votesO[feature.getProperty("SECCION")] +
                    "</h6>" +
                    "<h6 class='mt-2'><b>Votos " + names[4] + ": </b>" + votesN[feature.getProperty("SECCION")] +
                    "</h6>" +
                    "</div>" +
                    "</div>";
                console.log(contentString);
                // Add more properties as needed
                //infoWindow.close();
                infoWindow.setContent(contentString);
                infoWindow.setPosition(event.latLng);
                infoWindow.open(map);
            });

            function getRowVotes(section, arr, index) {
                var row = "";
                var i = (type == 'conteo-rapido' && index > 0) ? 4 : index;
                arr.forEach(element => {
                    //console.log({ element });
                    row += "<div class='mb-1'><b>" + element + ": </b>" + nvp[i][section] + "</div>";
                    i++;
                });
                return row;
            }

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


            function getColorToFeature(feature) {
                var propertyMap = "SECCION";
                var favor = votesA[feature.getProperty(propertyMap)]; //Se extraen los votos a favor del arreglo, según el índice del arreglo de secciones
                var contra = votesC[feature.getProperty(propertyMap)]; //Se extraen los votos en contra del arreglo, según el índice del arreglo de secciones
                return getColorRule(favor, contra);
            }

            //Retorna el color en base a ciertos criterios
            function getColorRule(f, c) {
                var i = (f > c) ? 0 : 1; //Si los votos a favor son mayores, entonces el índice i es 0, de lo contrario es 1
                var j = (Math.abs(f - c) >= 1 && Math.abs(f - c) <= 100) ?
                    0 //Si el valor absoluto de la sustracción es mayor que 1 y menor que 100, entonces se retorna un 0 (color claro)
                    :
                    (Math.abs(f - c) >= 101 && Math.abs(f - c) <= 200) ? 1 :
                    2; //Si el valor de la sustración es mayor o igual que 101 y menor o igual que 200, se retorna un 1 (color medio)
                //de lo contrario se retorna un 2 (color fuerte)
                var color = colores[i][
                    j
                ]; //Una vez que se han determinado los índices, se extrae el valor del color del arreglo colores.

                //En caso de que tanto los votos a favor como que los votos en contra sean 0, el color de relleno será negro, indicando que no hay registros
                if (f == 0 && c == 0)
                    color = '#000000';

                return color;
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
                div.style.position = "absolute";
                div.style.fontSize = "15px";
                div.style.fontWeight = "bold";
                div.style.color = (votesA[this.text] == 0 && votesC[this.text] == 0) ?
                    'black' :
                    (votesA[this.text] > votesC[this.text]) ?
                    '#003A61' :
                    '#E400AF';
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

            //var geocoder = new google.maps.Geocoder();

            /***************
             * INDICADORES * 
             ***************/
            //Se extrae el elemento 'legend'
            const legend = document.getElementById("legend");

            for (i = 0; i < 2; i++) {
                for (j = 0; j < 3; j++) {
                    const info = ((i == 0) ? names[i] : (i == 1) ? names[i] : names[j + 2]) + ((j * 100) + 1) + ' - ' + ((
                        j == 2) ? '...' : ((j + 1) * 100));
                    const div = document.createElement("div");

                    div.innerHTML = '<em class="icon ni ni-square-fill" style="color: ' + colores[i][j] + '"></em>' + info;
                    legend.appendChild(div);
                }

                legend.appendChild(document.createElement('br'));
            }

            const div = document.createElement("div");
            div.innerHTML = '<em class="icon ni ni-square-fill" style="color: #000000;"></em>' + "Sin registros";
            legend.appendChild(div);

            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
        }
    </script>


    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjnFkAKIPzt-1d0lQsu-SuInc4jqFYRYM&callback=initMap"></script>
@endsection
