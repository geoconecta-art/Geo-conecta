<div id="inventories_cards_container">
    <table id="inventories_cards_table" >
        <tbody>
            <tr>
                <td colspan="3">
                    <div>
                        <b class="card_title"> Inventarios: </b> <br>
                        <h3 class="inventaries_count">
                            {{ $inventaries }}
                        </h3>
                    </div>
                </td>

                <td colspan="3">
                    <div>
                        <b class="card_title"> Inventarios con Georreferencias Completas :</b> <br>
                        <h3 class="inventaries_count">
                            {{ $inventaries_with_georreferencia}}
                        </h3>
                    </div>
                </td>

                <td colspan="3">
                    <div>
                        <b class="card_title"> Inventarios con Georreferencias Faltantes: </b> <br>
                        <h3 class="inventaries_count">
                            {{ $inventaries_no_georreferencia}}
                        </h3>
                    </div>
                </td>

                <td colspan="3">
                    <div>
                        <b class="card_title">Sin Datos:</b> <br>
                        <h3 class="inventaries_count">
                            {{ $inventaries_no_data}}
                        </h3>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <img id="card_donut_chart_img" src="{{ $donutImage }}" alt="Gráfica de Dona">
                </td>
                <td colspan="9">
                    <img id="card_bar_chart_img" src="{{ $barImage }}" alt="Gráfica de Barras" style="">
                </td>
            </tr>

        </tbody>
    </table>
</div>