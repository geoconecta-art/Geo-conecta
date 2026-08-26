<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
    }

    header {
        position: fixed;
        top: 0px;
        left: 0px;
        right: 0px;
        height: 80px;
        text-transform: uppercase;
    }

    header .title {
        font-size: 1.1rem;
    }

    header .subtitle {
        font-size: 0.7rem;
    }

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

    .people-table td{
        border: 1px solid #c0c0c0;
    }

    .people-table th {
        /* border: none; */
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: left;
        padding-left: 5px;
    }

    .people-table td {
        border-bottom: 1px solid #c0c0c0;
        text-align: left;
        padding: 5px;
    }

    .people-table tfoot td {
        /* border: none; */
        text-align: left;
        padding: 5px;
    }

    body {
        margin-top: 120px;
        margin-bottom: 20px;
    }

    .page-break {
        page-break-before: always;
    }

    .watermark {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.5;
        font-size: 12rem;
        color: #c0c0c0;
        z-index: -1;
    }

    .no_page::before {
            content: "Página " counter(page);
        }
</style>
