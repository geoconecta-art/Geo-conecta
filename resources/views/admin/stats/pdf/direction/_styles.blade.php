<style>

    .page-break{
        page-break-before: always;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    .table_title{
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #6E5AAF;
    }

    /* HEADER */
        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 80px;
            text-transform: uppercase;
        }

        header .title{
            font-size: 14px !important;
        }

    /* END HEADER*/

    /* CARDS */
        #inventories_cards_container{
            margin-top: 80px;
            width: 95%;
            padding: 0px;
        }

        #inventories_cards_table{
            border-spacing: 1rem;
            width: 100%;
        }

        #inventories_cards_table td{
            color: #4e4e4e;
            border: 2px solid #C0C0C0;
            border-radius: 8px;
            padding: 1rem;
            height: 5.5rem;
        }

        .card_title{
            font-size: 1rem;
        }

        .inventaries_count{
            margin: 0px;
            font-size: xx-large;
            font-weight: normal;
            text-align: center;
        }
    /* END CARDS */

    /* GRÁFICA DE DONA */
        #card_donut_chart_img{
            width: 200px !important;
            height: auto; 
            object-fit: contain;
        }
    /* END GRÁFICA DE DONA */

    /* GRÁFICA DE BARRAS */
        #card_bar_chart_img{
            width: 750px !important;
            height: auto; 
            object-fit: contain;
        }
    /* END GRÁFICA DE BARRAS */

    /* INVENTARIOS POR ÁREA TABLA */
        #plans_by_area_container{
            margin-top: 80px;
        }

        #plans_by_area_table{
            width: 95%;
            border-spacing: 0px;
        }

        #plans_by_area_table thead{
            background-color: #6E5AAF;
            color: white;
            border: none !important;
        }

        #plans_by_area_table th{
            text-align: left;
            padding: 0.5rem;
            
        }

        #plans_by_area_table td{
            border-bottom: 1px solid #C0C0C0;
            font-size: 10px;
            padding: 0.5rem;
        }
    /* END INVENTARIOS POR ÁREA TABLA */

    /* GEORREFERENCIAS FALTANTES TABLA */
        #missing_geo_container{
            margin-top: 80px;
        }

        #missing_geo_table{
            width: 95%;
            border-spacing: 0px;
        }

        #missing_geo_table thead{
            background-color: #6E5AAF;
            color: white;
            border: none !important;
        }

        #missing_geo_table th{
            text-align: left;
            padding: 0.5rem;
            border: none !important;
        }

        #missing_geo_table td{
            border-bottom: 1px solid #C0C0C0;
            font-size: 10px;
            padding: 0.5rem;
        }
    /* END GEORREFERENCIAS FALTANTES TABLA */

    

    /* FOOTER */
        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 20px;
            font-size: 9px;
        }

        footer td {
            padding-top: 10px;
        }

        .no_page::before {
            content: "Página " counter(page);
        }
    /* END FOOTER */
</style>
