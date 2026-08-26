<style>
    .text-upercase{ text-transform: uppercase;}
    .fw-bold{ font-weight: bold;}
    .fs-small{font-size: small;}
    .fs-xs{font-size: x-small;}
    .fs-xxs{font-size: xx-small;}
    .text-center{text-align: center;}
    .w-100{ width: 100%; }
    .d-block{ display: block; }
    .border-black{ border: 1px solid black; }

    .punto {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .line {
        border: 0;
        width: 10px;
    }

    body{ 
        font-family: Arial, Helvetica, sans-serif;
    }

    #map_container{
        width: 685px;
        height: 685px;
        overflow: hidden;
        text-align: center;
        padding: 0px;
        margin: 0px;
        position: relative;
    }

    #map_container img{
        position: absolute;
        top: 0px;
        left: 0px;
    }

    .row{
        display: flex !important;
        align-items: center;
    }

    #location_img_container, #escudo_img_container, #cenit_img_container, #logo_img_container{
        width: fit-content !important;
        padding: 0.5rem;
    }

    #location_img_container img{
        width: 140px;
    }
    #escudo_img_container img{
        width: 98px;
        overflow: hidden;
    }

    #area_name, #plan_name{
        padding: 0.5rem;
        height: 55px;
        max-height: 50px;
    }

    #key_container{
        padding: 0.5rem;
    }

    #simbology_table{
        padding: 1rem;
        height: 194px;
        overflow: hidden;
    }

    #cenit_img_container img{
        width: 90px;
    }

    #logo_img_container img{
        padding-top: 0.25rem;
        padding-bottom: 0.45rem;
        width: 145px;
    }

    #created_by_geo{
        border: 1px solid black;
        font-size: 8pt;
        text-align: center;
        width: 100%;
        color: #6E5AAF;
    }
</style>
