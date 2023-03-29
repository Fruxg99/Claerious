@extends('Template.backoffice')
@section('title', 'Claerious - Laporan Penjualan')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Penjualan</h1>
    <input type="text" id="daterangeReport" class="form-control ml-2" style="width: 250px;">
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Penjualan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="salesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let pageIndex = 0, showEntries = 10
    let dateStart, dateEnd

    $(document).ready(function() {
        $('#daterangeReport').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'))

            showSales()
        })

        showSales()
    })

    function showSales() {
        table = $('#salesTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/report/sales/select",
                "type": "POST",
                "data": {
                    dateStart: $('#daterangeReport').data('daterangepicker').startDate.format('YYYY-MM-DD'),
                    dateEnd: $('#daterangeReport').data('daterangepicker').endDate.format('YYYY-MM-DD')
                }
            },
            "initComplete": function(settings, json) {
                table.page(pageIndex).draw('page')
            },
            "pageLength": showEntries,
            "scrollY": "400px",
            "order": [
                [0, "asc"]
            ],
            "columns": [
                {
                    render: function(data, type, row) {
                        return `
                            <img src="${row.thumbnail}" style="width: 100px; height: 100px;">
                        `
                    }
                },
                {
                    data: "name"
                },
                {
                    data: "total",
                    render: function(data, type, row) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data).substring(3)
                    }
                }
            ]
        })
    }
</script>

@endsection