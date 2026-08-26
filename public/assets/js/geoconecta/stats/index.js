let inventoryData, barChartStacked;
let chartDonut, barChart;

$(document).ready(function () {
    getInventoryInfo(id_plan);

    $(".link-invetory-stats").on("click", function (event) {
        event.preventDefault();

        var id_plan = $(this).attr("val");
        getInventoryInfo(id_plan);
    });
});

// Exporta la información de la dirección seleccionada en formato PDF
function exportPDF() {
    let id_area = $("input[name=id_area_export]").val();
    $loading.show();

    let donutImage = (barImage = null);

    if (chartDonut) {
        donutImage = chartDonut.toBase64Image();
    }

    if (barChart) barImage = barChart.toBase64Image();

    let formData = new FormData();
    let _token = $('meta[name="csrf-token"]').attr("content");

    formData.append("donutImage", donutImage);
    formData.append("barImage", barImage);

    $.ajax({
        type: "POST",
        url: window.Laravel.routes['stats.area.export'].replace( "id_area", id_area ),
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: {
            responseType: "blob",
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", _token);
        },
        success: function (data) {
            if (data instanceof Blob) {
                var url = URL.createObjectURL(data);
                window.open(url, "_blank");
            }
        },
        error: function (xhr, status, errors) {},
        complete: function () {
            $loading.hide();
        },
    });
}

// Obtiene la información del inventario seleccionado
async function getInventoryInfo(id_area) {
    let _token = $token.val();
    $loading.show();

    await $.ajax({
        url: window.Laravel.routes['stats.direction-info'].replace( "id_direction", id_area ),
        type: "GET",
        data: {
            _token: _token,
        },
        success: async function (response) {
            $(".info").html(response.html);
            setTable();
            setOptionLinkSelected($("input[name='name-area']").val());
            setInventoryData();
            setInventoryBar(response.subarea_inventaries);
            $("input[name=id_area_export]").val(response.id_area);
            $("#export_stats_btn").attr(
                "href",
                window.Laravel.routes['stats.area.export'].replace( "id_area", response.id_area)
            );
        },
        error: function (xhr, status, errors) {
            $loading.show();
            let _token = $token.val();
            $.ajax({
                url: window.Laravel.routes['stats.error'],
                type: "GET",
                data: { _token: _token },
                success: function (response) {
                    $(".info").html(response);
                },
                error: function (xhr, status, errors) {},
                complete: function () {
                    $loading.hide();
                },
            });
        },
        complete: function () {
            analyticsDoughnut($("#planChart"), inventoryData);
            setBarChart($("#barChartStacked"), barChartStacked);
            $loading.hide();
        },
    });
}

// Inicializa los data tables
function setTable() {
    $(".table").DataTable({
        language: spanish,
        paginate: false,
    });
}

// Coloca la opción seleccionada
function setOptionLinkSelected(nameArea) {
    $("#option-link-selected").text(nameArea);
}

// Coloca los valores para la gráfica de barras
function setInventoryBar(subarea_inventaries) {
    barChartStacked = {
        labels: subarea_inventaries["labels"],
        stacked: true,
        legend: true,
        dataUnit: "Inventario",
        datasets: [
            {
                label: "Con Georreferencia Completa",
                color: "#9CABFF",
                background: "transparent",
                data: subarea_inventaries["inventaries_with_georreferencia"],
            },
            {
                label: "Con Georreferencia Faltante",
                color: "#5CCFE6",
                data: subarea_inventaries["inventaries_no_georreferencia"],
            },
            {
                label: "Sin Datos",
                color: "#F4AAA4",
                data: subarea_inventaries["inventaries_no_data"],
            },
        ],
    };
}

// Coloca los valores para la gráfica de pastel/dona
function setInventoryData() {
    var dataSet = [
        $("input[name='planGeo']").val(),
        $("input[name='planNoGeo']").val(),
        $("input[name='planNoRows']").val(),
    ];

    inventoryData = {
        labels: [
            "Georreferencias Completas",
            "Georreferencias Faltantes",
            "Sin Datos",
        ],
        dataUnit: "Inventarios",
        legend: false,
        datasets: [
            {
                borderColor: "#fff",
                background: ["#9CABFF", "#5CCFE6", "#F4AAA4"],
                data: dataSet,
            },
        ],
    };
}

// Coloca la gráfica de dona
function analyticsDoughnut(selector, set_data) {
    var $selector = selector ? $(selector) : $(".analytics-doughnut");
    $selector.each(function () {
        var $self = $(this),
            _self_id = $self.attr("id"),
            _get_data =
                typeof set_data === "undefined" ? eval(_self_id) : set_data;

        var selectCanvas = document.getElementById(_self_id).getContext("2d");
        var chart_data = [];

        for (var i = 0; i < _get_data.datasets.length; i++) {
            chart_data.push({
                backgroundColor: _get_data.datasets[i].background,
                borderWidth: 2,
                borderColor: _get_data.datasets[i].borderColor,
                hoverBorderColor: _get_data.datasets[i].borderColor,
                data: _get_data.datasets[i].data,
            });
        }

        chartDonut = new Chart(selectCanvas, {
            type: "doughnut",
            data: {
                labels: _get_data.labels,
                datasets: chart_data,
            },
            options: {
                legend: {
                    display: _get_data.legend ? _get_data.legend : false,
                    rtl: NioApp.State.isRTL,
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        fontColor: "#6783b8",
                    },
                },
                rotation: -1.5,
                cutoutPercentage: 70, // Ajusta este valor si quieres el hueco más pequeño
                maintainAspectRatio: false,
                tooltips: {
                    enabled: true,
                    rtl: NioApp.State.isRTL,
                    callbacks: {
                        title: function (tooltipItem, data) {
                            return data["labels"][tooltipItem[0]["index"]];
                        },
                        label: function (tooltipItem, data) {
                            return (
                                data.datasets[tooltipItem.datasetIndex]["data"][
                                    tooltipItem["index"]
                                ] +
                                " " +
                                _get_data.dataUnit
                            );
                        },
                    },
                    backgroundColor: "#1c2b46",
                    titleFontSize: 13,
                    titleFontColor: "#fff",
                    titleMarginBottom: 6,
                    bodyFontColor: "#fff",
                    bodyFontSize: 12,
                    bodySpacing: 4,
                    yPadding: 10,
                    xPadding: 10,
                    footerMarginTop: 0,
                    displayColors: false,
                },
            },
        });
    });
}

// Coloca la gráfica de barras
function setBarChart(selector, set_data) {
    var $selector = selector ? $(selector) : $(".bar-chart");
    $selector.each(function () {
        var $self = $(this),
            _self_id = $self.attr("id"),
            _get_data =
                typeof set_data === "undefined" ? eval(_self_id) : set_data,
            _d_legend =
                typeof _get_data.legend === "undefined"
                    ? false
                    : _get_data.legend;

        var selectCanvas = document.getElementById(_self_id).getContext("2d");
        var chart_data = [];

        for (var i = 0; i < _get_data.datasets.length; i++) {
            chart_data.push({
                label: _get_data.datasets[i].label,
                data: _get_data.datasets[i].data,
                // Styles
                backgroundColor: _get_data.datasets[i].color,
                borderWidth: 2,
                borderColor: "transparent",
                hoverBorderColor: "transparent",
                borderSkipped: "bottom",
                barPercentage: 0.6,
                categoryPercentage: 0.7,
            });
        }

        barChart = new Chart(selectCanvas, {
            type: "bar",
            data: {
                labels: _get_data.labels,
                datasets: chart_data,
            },
            options: {
                plugins: {
                    legend: {
                        display: _get_data.legend ? _get_data.legend : false,
                        labels: {
                            boxWidth: 30,
                            padding: 20,
                            color: "#000000",
                        },
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            title: function (tooltipItem) {
                                return tooltipItem[0].dataset.label;
                            },
                            label: function (tooltipItem) {
                                return (
                                    tooltipItem.dataset.data[
                                        tooltipItem.dataIndex
                                    ] +
                                    " " +
                                    _get_data.dataUnit
                                );
                            },
                        },
                        backgroundColor: "#eff6ff",
                        titleColor: "#6783b8",
                        bodyColor: "#9eaecf",
                        padding: 10,
                    },
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        // Cambiado de yAxes a y
                        display: true,
                        stacked: true, // Mantener apilado
                        beginAtZero: true,
                        ticks: {
                            color: "#000000",
                            padding: 5,
                            stepSize: 1,
                            precision: 0,
                        },
                        grid: {
                            color: NioApp.hexRGB("#526484", 0.2),
                            tickLength: 0,
                            zeroLineColor: NioApp.hexRGB("#526484", 0.2),
                        },
                    },
                    x: {
                        // Cambiado de xAxes a x
                        display: true,
                        stacked: true, // Mantener apilado
                        ticks: {
                            color: "#000000",
                            padding: 5,
                            reverse: NioApp.State.isRTL,
                        },
                        grid: {
                            color: "transparent",
                            tickLength: 10,
                            zeroLineColor: "transparent",
                        },
                    },
                },
            },
        });
    });
}
