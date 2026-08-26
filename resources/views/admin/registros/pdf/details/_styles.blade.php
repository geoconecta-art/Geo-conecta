<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    header {
        position: fixed;
        top: 0px;
        left: 0px;
        right: 0px;
        height: 160px;
        z-index: 100;
        text-transform: uppercase;
    }

    header .title {
        font-size: 0.75rem;
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

    .plan-table th {
        border: none;
        padding-top: 5px;
        padding-bottom: 5px;
        text-align: left;
        padding-left: 5px;
    }

    .plan-table td {
        text-align: left;
        padding: 5px;
        border: 1px solid #6E5AAF;
    }

    body {
        margin-top: 120px;
        margin-bottom: 20px;
    }

    .page-break {
        page-break-before: always;
    }

    .no_page::before {
            content: "Página " counter(page);
        }
</style>
