@extends('Template.backoffice')
@section('title', 'Claerious - Transaksi')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Transaksi</h1>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="transactionTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Pembeli</th>
                                <th>Alamat</th>
                                <th>Total</th>
                                <th>Status</th>
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

<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 700px;">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Detail Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Nomor Transaksi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-user" id="transactionID" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Nama Penerima</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-user" id="transactionReceiver" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Alamat</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-user" id="transactionAddress" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>No Telp Penerima</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-user" id="transactionPhone" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Gambar</th>
                                            <th scope="col">Nama Produk</th>
                                            <th scope="col">Penjual</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableProductList"></tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row" style="margin-bottom: 8px;">
                            <div class="col-7">Potongan Voucher</div>
                            <div class="col-3 text-danger" id="discountPrice"></div>
                        </div>
                        <div class="row" style="margin-bottom: 8px;">
                            <div class="col-7">Biaya Pengiriman</div>
                            <div class="col-3" id="shipmentPrice"></div>
                        </div>
                        <div class="row" style="margin-bottom: 8px;">
                            <div class="col-7">Biaya Layanan</div>
                            <div class="col-3">Rp 5.000</div>
                        </div>
                        <div class="row">
                            <div class="col-7"><b>Total</b></div>
                            <div class="col-3" id="totalPrice" style="font-weight: 700;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let pageIndex = 0, showEntries = 10

    $(document).ready(function() {
        showTransaction()
    })

    function showTransaction() {
        table = $('#transactionTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/transaction/select",
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
                "width": "10%",
                "targets": 2
            },{
                "width": "5%",
                "targets": 4
            }],
            "columns": [
                {
                    data: "name"
                },
                {
                    render: function(data, type, row) {
                        return row.address + ", " + row.city_name + ", " + row.postal_code
                    }
                },
                {
                    data: "total",
                    render: function(data, type, row) {
                        return number_format(parseInt(data), 0, '.', ',')
                    }
                },
                {
                    data: "status",
                    render: function(data, type, row) {
                        if (data == 2) {
                            return "Belum Diproses"
                        } else if (data == 3) {
                            return "Belum Dikirim"
                        } else if (data == 4) {
                            return "Dalam Pengiriman"
                        } else if (data >= 5) {
                            return "Selesai"
                        }
                    }
                },
                {
                    render: function(data, type, row) {
                        let html = ``
                        html += `<li class="nav-item dropdown no-arrow" style="list-style: none;">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                                    <a id="optionUpdate" class="dropdown-item" href="#" 
                                        onclick="detailOrder('${row.id_trans}')">
                                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Detail Pesanan
                                    </a>`

                        if (row.status == 2) {
                            html += `<a id="optionDelete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal" 
                                        onclick="declineOrder('${row.id_trans}')">
                                        <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Tolak Pesanan
                                    </a>`
                        } else if (row.status == 3) {
                            html += `<a id="optionUpdate" class="dropdown-item" href="#" data-toggle="modal" data-target="#sendModal" 
                                        onclick="sendOrder('${row.id_trans}')">
                                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Kirim Pesanan
                                    </a>`
                        }

                        html += `</div>
                            </li>`

                        return html
                    }
                }
            ]
        })
    }

    function detailOrder(id_trans) {
        $.ajax({
            data: {
                id_trans: id_trans
            },
            url: "{{ url('transaction/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                detailTrans = result.transaction[0]
                detailItems = result.items

                $("#transactionID").val(detailTrans.id_trans)
                $("#transactionReceiver").val(detailTrans.receiver_name)
                $("#transactionAddress").val(detailTrans.address + ", " + detailTrans.city_name + ", " + detailTrans.postal_code)
                $("#transactionPhone").val(detailTrans.receiver_phone)

                $("#transactionModal").modal('show')

                let html = ``

                for(let i = 0 ; i < detailItems.length ; i++) {
                    html += `<tr>
                                <td><img src="${detailItems[i].thumbnail}" style="max-width: 100px; max-height: 100px;"></td>
                                <td>
                                    ${detailItems[i].product_name}<br>
                                    ${detailItems[i].qty} x ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(detailItems[i].price)}
                                </td>
                                <td>${detailItems[i].seller_name}</td>
                            </tr>`
                }

                $("#tableProductList").html(html)

                $("#shipmentPrice").html(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(detailTrans.shipping_cost))
                $("#discountPrice").html(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(detailTrans.coupon))
                $("#totalPrice").html(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(detailTrans.total))
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function declineOrder(id_trans) {
        $.ajax({
            data: {
                id_trans: id_trans
            },
            url: "{{ url('transaction/rejectOrder') }}",
            method: 'POST',
            success: function(result) {
                showTransaction()
                toastr.success("Berhasil tolak pesanan")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function sendOrder(id_trans) {
        $.ajax({
            data: {
                id_trans: id_trans
            },
            url: "{{ url('transaction/sendOrder') }}",
            method: 'POST',
            success: function(result) {
                showTransaction()
                toastr.success("Berhasil kirim pesanan")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function updateProduct() {
        let newData = new FormData()
        newData.append("id_product", $("#productID").val())
        newData.append("id_category", $("#productCategory").val())
        newData.append("name", $("#productName").val())
        newData.append("description", $("#productDescription").val())
        newData.append("stock", $("#productStock").val().split('.').join(''))
        newData.append("price", $("#productPrice").val().split('.').join(''))
        newData.append("weight", $("#productWeight").val().split('.').join(''))
        newData.append("thumbnail", base64Image)
        newData.append("groupPrice[]", JSON.stringify(groupPrice))

        let subImages = Dropzone.forElement("#productSubImage").getAcceptedFiles()
        for (i = 0; i < subImages.length; i++) {
            let file = subImages[i]

            var reader = new FileReader();
            reader.readAsDataURL(file)
            
            newData.append("file[]", file)
        }

        $.ajax({
            data: newData,
            url: "{{ url('product/update') }}",
            method: 'POST',
            processData: false,
            contentType: false,
            success: function(result) {
                $("#itemModal").modal("hide")

                resetModal()
                showProducts()

                toastr.success("Berhasil ubah produk")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function deleteProductModal(id_product) {
        $.ajax({
            data: {
                id_product: id_product
            },
            url: "{{ url('product/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                $("#btnDeleteProduct").attr("onclick", `deleteProduct('${id_product}')`)
                $("#deleteProductName").html(result.data.name)
                $("#deleteModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function deleteProduct(id_product) {
        $.ajax({
            data: {
                id_product: id_product
            },
            url: "{{ url('product/delete') }}",
            method: 'POST',
            success: function(result) {
                $("#deleteModal").modal("hide")

                resetModal()
                showProducts()

                toastr.success("Berhasil hapus produk")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function resetModal() {
        $("#productID").val("")
        $("#productName").val("")
        $("#productWeight").val("")
        $("#productStock").val("")
        $("#productPrice").val("")
        $("#productImage").val("")
        $("#previewImage").html("")
        $(".custom-file-label").text("Choose Image")
        $("#productDescription").val("")
        $("#productName").removeAttr("readonly")
        $("#productCategory").val("0")
        Dropzone.forElement("#productSubImage").removeAllFiles(true)
        base64Image = ""
        productImage = []
        groupPrice = []
        ctrPrice = 0

        $("#tablePriceList").html("")
        if ($("#groupPriceToggle").is(':checked')) {
            $("#groupPriceToggle").prop("checked", false)
        }
        $("#groupPriceToggle").trigger("change")

        $("#btnItemModal").html("Submit")
        $("#btnItemModal").attr("onclick", "addProduct()")
    }

    $("#productWeight").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#productWeight").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })

    $("#productStock").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#productStock").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })

    $("#productPrice").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#productPrice").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })

    $("#groupMinPurchase").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#groupMinPurchase").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })

    $("#groupPrice").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#groupPrice").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })
</script>

@endsection