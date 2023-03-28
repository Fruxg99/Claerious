@extends('Template.backoffice')
@section('title', 'Claerious - Voucher')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Voucher</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#voucherModal">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Voucher Baru
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Voucher</h6>
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
                                <th>Opsi</th>
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

<div class="modal fade" id="voucherModal" tabindex="-1" role="dialog" aria-labelledby="voucherModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Voucher</h5>
                        <input type="hidden" class="form-control form-control-user" id="voucherID">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Tipe Voucher</label>
                                <div class="row" style="padding: 0 0.75rem;">
                                    <div class="form-check" style="width: 50%;">
                                        <input class="form-check-input" type="radio" value="0" name="voucherType" id="voucherTypePercentage" checked>
                                        <label class="form-check-label" for="voucherType">
                                            Persen Pembelian
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="1" name="voucherType" id="voucherTypeAmount">
                                        <label class="form-check-label" for="voucherType">
                                            Potongan Harga
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Nama Voucher</label>
                                <input type="text" class="form-control form-control-user" id="voucherName">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Minimal Pembelian</label>
                                <input type="text" class="form-control form-control-user" id="voucherMin">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Maksimal Potongan</label>
                                <input type="text" class="form-control form-control-user" id="voucherAmount">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Persen Potongan</label>
                                <input type="text" class="form-control form-control-user" id="voucherPercentage">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Batas Penggunaan</label>
                                <input type="text" class="form-control form-control-user" id="voucherLimit">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Tanggal Berlaku</label>
                                <div class="input-group date" data-provide="datepicker" id="dpStart">
                                    <input type="text" class="form-control" id="voucherStart">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Tanggal Expired</label>
                                <div class="input-group date" data-provide="datepicker" id="dpEnd">
                                    <input type="text" class="form-control" id="voucherEnd">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModal()">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="addVoucher()" id="btnVoucherModal">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" style="padding: 2rem;">
                <h1 style="text-align:center;">
                    <i class="fas fa-trash text-danger"></i>
                </h1>
                <div style="text-align:center; font-size: 16pt; font-weight: 700;" class="mb-2 text-danger">Remove Voucher</div>
                <div style="text-align:center;" class="mb-4" id="deleteVoucherName"></div>
                <div style="text-align:center;" class="mb-4">
                    Are you sure to remove this voucher?<br>Removed voucher can't be reverted
                </div>
                <div style="text-align: center;">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="btnDeleteVoucher" data-dismiss="modal">Remove</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let pageIndex = 0, showEntries = 10

    $(document).ready(function() {
        $(".date").datepicker({
            format: 'dd MM yyyy',
            startDate: new Date()
        })

        $("#voucherStart").change(function() {
            $('#dpEnd').datepicker('clearDates');
            $('#dpEnd').datepicker('setStartDate', new Date($("#voucherStart").val()));
        })

        $("#voucherMin").keyup(function() {
            var value = $(this).val()
            var result = autoNumeric(value)
            rawValue = parseInt(result.split('.').join(''))

            $(this).val(result)
        })
        $("#voucherMin").keydown(function(event) {
            var value = $(this).val()

            if (checkNumericInputWithZero(event.keyCode, value)) {
                return true
            } else {
                return false
            }
        })

        $("#voucherAmount").keyup(function() {
            var value = $(this).val()
            var result = autoNumeric(value)
            rawValue = parseInt(result.split('.').join(''))

            $(this).val(result)
        })
        $("#voucherAmount").keydown(function(event) {
            var value = $(this).val()

            if (checkNumericInputWithZero(event.keyCode, value)) {
                return true
            } else {
                return false
            }
        })

        $("#voucherPercentage").keyup(function() {
            var value = $(this).val()
            var result = autoNumeric(value)
            rawValue = parseInt(result.split('.').join(''))

            $(this).val(result)
        })
        $("#voucherPercentage").keydown(function(event) {
            var value = $(this).val()

            if (checkNumericInputWithZero(event.keyCode, value)) {
                return true
            } else {
                return false
            }
        })

        $("#voucherLimit").keyup(function() {
            var value = $(this).val()
            var result = autoNumeric(value)
            rawValue = parseInt(result.split('.').join(''))

            $(this).val(result)
        })
        $("#voucherLimit").keydown(function(event) {
            var value = $(this).val()

            if (checkNumericInputWithZero(event.keyCode, value)) {
                return true
            } else {
                return false
            }
        })

        showVouchers()
    })

    function showVouchers() {
        table = $('#voucherTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/voucher/select",
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
                        return number_format(parseInt(data), 0, '.', ',')
                    }
                },
                {
                    data: "max_discount",
                    render: function(data, type, row) {
                        return number_format(parseInt(data), 0, '.', ',')
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
                        return number_format(parseInt(row.usage_count), 0, '.', ',') + " / " + number_format(parseInt(row.usage_limit), 0, '.', ',')
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
                    render: function(data, type, row) {
                        return `
                            <li class="nav-item dropdown no-arrow" style="list-style: none;">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                                    <a id="optionUpdate" class="dropdown-item" href="#" data-toggle="modal" data-target="#voucherModal" 
                                        onclick="loadVoucher('${row.id_voucher}')">
                                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Ubah Voucher
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a id="optionDelete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal" 
                                        onclick="deleteVoucherModal('${row.id_voucher}')">
                                        <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Hapus Voucher
                                    </a>
                                </div>
                            </li>
                        `
                    }
                }
            ]
        })
    }

    function addVoucher() {
        let discountAmount = 0

        if ($('input[name="voucherType"]:checked').val() == 0) {
            discountPercentage = $("#voucherPercentage").val().split('.').join('')
        } else {
            discountPercentage = 0
        }

        let data = {
            name: $("#voucherName").val(),
            type: $('input[name="voucherType"]:checked').val(),
            min_purchase: $("#voucherMin").val().split('.').join(''),
            max_discount: $("#voucherAmount").val().split('.').join(''),
            discount_percentage: discountPercentage,
            usage_limit: $("#voucherLimit").val().split('.').join(''),
            effective_date: new Date($("#voucherStart").val()).toISOString().split('T')[0],
            due_date: new Date($("#voucherEnd").val()).toISOString().split('T')[0]
        }

        $.ajax({
            data: data,
            url: "{{ url('voucher/insert') }}",
            method: 'POST',
            success: function(result) {
                $("#voucherModal").modal("hide")

                resetModal()
                showVouchers()

                toastr.success("Berhasil menambahkan voucher")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function loadVoucher(id_voucher) {
        $.ajax({
            data: {
                id_voucher: id_voucher
            },
            url: "{{ url('voucher/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                // Format Date
                let effective_date = new Date(result.data.effective_date)
                let due_date = new Date(result.data.due_date)

                let formatted_effective_date = effective_date.toLocaleDateString('en-GB', {
                    day: 'numeric', month: 'long', year: 'numeric'
                })

                let formatted_due_date = due_date.toLocaleDateString('en-GB', {
                    day: 'numeric', month: 'long', year: 'numeric'
                })

                // Set Input Value
                $("#voucherID").val(result.data.id_voucher)
                $("#voucherName").val(result.data.name)
                $("#voucherMin").val(result.data.min_purchase)
                $("#voucherAmount").val(result.data.max_discount)
                $("#voucherPercentage").val(result.data.discount_percentage)
                $("#voucherLimit").val(result.data.usage_limit)
                $("#voucherStart").val(formatted_effective_date)
                $("#voucherEnd").val(formatted_due_date)

                // Voucher Type Radio
                if (result.data.type == 0) {
                    $("#voucherTypePercentage").prop('checked', true)
                } else {
                    $("#voucherTypeAmount").prop('checked', true)
                }

                // Product Name Set Read Only
                $("#voucherName").attr("readonly", "true")

                // Trigger Event
                $("#voucherMin").trigger("keyup")
                $("#voucherAmount").trigger("keyup")
                $("#voucherPercentage").trigger("keyup")
                $("#voucherLimit").trigger("keyup")

                // Change Submit Button Event & Text
                $("#btnVoucherModal").html("Simpan")
                $("#btnVoucherModal").attr("onclick", "updateVoucher()")

                $("#voucherModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function updateVoucher() {
        let discountPercentage = 0

        if ($('input[name="voucherType"]:checked').val() == 0) {
            discountPercentage = $("#voucherPercentage").val().split('.').join('')
        } else {
            discountPercentage = 0
        }

        let data = {
            id_voucher: $("#voucherID").val(),
            type: $('input[name="voucherType"]:checked').val(),
            min_purchase: $("#voucherMin").val().split('.').join(''),
            max_discount: $("#voucherAmount").val().split('.').join(''),
            discount_percentage: discountPercentage,
            usage_limit: $("#voucherLimit").val().split('.').join(''),
            effective_date: new Date($("#voucherStart").val()).toISOString().split('T')[0],
            due_date: new Date($("#voucherEnd").val()).toISOString().split('T')[0]
        }

        $.ajax({
            data: data,
            url: "{{ url('voucher/update') }}",
            method: 'POST',
            success: function(result) {
                $("#voucherModal").modal("hide")

                resetModal()
                showVouchers()

                toastr.success("Berhasil ubah voucher")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function deleteVoucherModal(id_voucher) {
        $.ajax({
            data: {
                id_voucher: id_voucher
            },
            url: "{{ url('voucher/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                $("#btnDeleteVoucher").attr("onclick", `deleteVoucher('${id_voucher}')`)
                $("#deleteVoucherName").html(result.data.name)
                $("#deleteModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function deleteVoucher(id_voucher) {
        $.ajax({
            data: {
                id_voucher: id_voucher
            },
            url: "{{ url('voucher/delete') }}",
            method: 'POST',
            success: function(result) {
                $("#deleteModal").modal("hide")

                resetModal()
                showVouchers()

                toastr.success("Berhasil hapus voucher")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function resetModal() {
        $("#voucherID").val("")
        $("#voucherName").val("")
        $("#voucherMin").val("")
        $("#voucherAmount").val("")
        $("#voucherPercentage").val("")
        $("#voucherLimit").val("")
        $("#voucherTypePercentage").prop('checked', true)
        $(".date").datepicker("clearDates")

        $("#productName").removeAttr("readonly")

        $("#btnVoucherModal").html("Submit")
        $("#btnVoucherModal").attr("onclick", "addVoucher()")
    }
</script>
@endsection