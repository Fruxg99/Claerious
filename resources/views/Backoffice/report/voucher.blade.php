@extends('Template.backoffice')
@section('title', 'Claerious - Laporan Stok')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Voucher</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Voucher</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="voucherTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Minimal Pembelian</th>
                                <th>Maksimal Potongan</th>
                                <th>Persen Potongan</th>
                                <th>Batas Penggunaan</th>
                                <th>Tanggal Berlaku</th>
                                <th>Jumlah Penggunaan</th>
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

    $(document).ready(function() {

        showVouchers()
    })

    function showVouchers() {
        table = $('#voucherTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/report/voucher/select",
                "type": "POST",
                "data": {
                    id_seller: ""
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
            "columnDefs": [{
                "width": "5%",
                "targets": 1
            }],
            "columns": [
                {
                    data: "name"
                },
                {
                    data: "type",
                    render: function(data, type, row) {
                        if (data == "0") {
                            return "Persen Pembelian"
                        } else {
                            return "Potongaan Harga"
                        }
                    }
                },
                {
                    data: "min_purchase",
                    render: function(data, type, row) {
                        return "Rp " + number_format(parseInt(data), 0, '.', ',')
                    }
                },
                {
                    data: "max_discount",
                    render: function(data, type, row) {
                        return "Rp " + number_format(parseInt(data), 0, '.', ',')
                    }
                },
                {
                    data: "discount_percentage",
                    render: function(data, type, row) {
                        return data + " %"
                    }
                },
                {
                    render: function(data, type, row) {
                        return number_format(parseInt(row.usage_limit), 0, '.', ',') + " Kali"
                    }
                },
                {
                    render: function(data, type, row) {
                        let effective_date = new Date(row.effective_date)
                        let due_date = new Date(row.due_date)

                        let formatted_effective_date = effective_date.toLocaleDateString('en-GB', {
                            day: 'numeric', month: 'short', year: 'numeric'
                        })

                        let formatted_due_date = due_date.toLocaleDateString('en-GB', {
                            day: 'numeric', month: 'short', year: 'numeric'
                        })

                        return formatted_effective_date + " - " + formatted_due_date
                    }
                },
                {
                    data: "usage_count",
                    render: function(data, type, row) {
                        return number_format(parseInt(data), 0, '.', ',') + " Kali"
                    }
                }
            ]
        })
    }
</script>

@endsection