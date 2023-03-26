@extends('Template.backoffice')
@section('title', 'Claerious - Products')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Product</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#itemModal">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> New Product
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="itemTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Option</th>
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

<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Tambah Produk Baru</h5>
                        <input type="hidden" class="form-control form-control-user" id="productID">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Nama Produk</label>
                                <input type="text" class="form-control form-control-user" id="productName">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Berat (gram)</label>
                                <input type="text" class="form-control form-control-user" id="productWeight">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Stok</label>
                                <input type="text" class="form-control form-control-user" id="productStock">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Harga</label>
                                <input type="text" class="form-control form-control-user" id="productPrice">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Gambar</label>
                                <div class="preview-image" id="previewImage" class="mb-3 d-none"></div>
                                <div class="col-md-12">
                                    <div class="row" style="flex: 1;">
                                        <div class="form-group mb-3 col-8 pl-0">
                                            <div class="custom-file">
                                                <input type='file' id="productImage" class="custom-file-input">
                                                <label class="custom-file-label">Pilih Gambar</label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-4 px-0">
                                            <button id="btnRemoveImage" class="btn btn-outline-danger btn-sm" type="button" style="width: 100%; height: 100%;">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Deskripsi</label>
                                <textarea id="productDescription" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModal()">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="addProduct()" id="btnItemModal">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Preview Gambar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img src="" alt="Picture" id="imagePreview">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
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
                <div style="text-align:center; font-size: 16pt; font-weight: 700;" class="mb-2 text-danger">Hapus Product</div>
                <div style="text-align:center;" class="mb-4" id="deleteProductName"></div>
                <div style="text-align:center;" class="mb-4">
                    Yakin ingin hapus produk ini?<br>Produk yang sudah dihapus tidak dapat dikembalikan
                </div>
                <div style="text-align: center;">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="button" id="btnDeleteProduct" data-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cropBoxData, canvasData, cropper, image, base64Image = ""
    let pageIndex = 0, showEntries = 10

    $(document).ready(function() {
        $("#productImage").change(function() {
            readURL(this, "#imagePreview")
            $("#itemModal").modal('hide')
            $("#cropperModal").modal('show')
        })
        $("#btnRemoveImage").click(function () {
            base64Image = ""
            $("#previewImage").empty('canvas')
            $(".custom-file-label").text("Choose Image")
            $("#productImage").val("")
            showElement("#previewImage", false, "d-block")
        })

        image = document.getElementById('imagePreview')

        $('#cropperModal').on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                initialAspectRatio: 1,
                aspectRatio: 1,
                autoCropArea: 0.8,
                background: false,
                cropBoxMovable: true,
                dragMode: 'none',
                highlight: true,
                guides: false,
                scalable: true,
                zoomable: false
            })
        }).on('hidden.bs.modal', function () {
            cropBoxData = cropper.getCropBoxData()
            canvasData = cropper.getCanvasData()
            base64Image = cropper.getCroppedCanvas({ width: 480 }).toDataURL()
            
            $("#previewImage").empty()
            $("#previewImage").append(cropper.getCroppedCanvas())
            showElement("#previewImage", true, "d-block")
            $("#itemModal").modal('show')

            cropper.destroy()
        })

        showProducts()
    })

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

    function showProducts() {
        table = $('#itemTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/product/select",
                "type": "POST",
                "data": {
                    id_seller: "S0001"
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
                    data: "stock",
                    render: function(data, type, row) {
                        return number_format(parseInt(data), 0, '.', ',')
                    }
                },
                {
                    data: "price",
                    render: function(data, type, row) {
                        return number_format(parseInt(data), 0, '.', ',')
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
                                    <a id="optionUpdate" class="dropdown-item" href="#" data-toggle="modal" data-target="#itemModal" 
                                        onclick="loadProduct('${row.id_product}')">
                                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Update Product
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a id="optionDelete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal" 
                                        onclick="deleteProductModal('${row.id_product}')">
                                        <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Remove Product
                                    </a>
                                </div>
                            </li>
                        `
                    }
                }
            ]
        })
    }

    function addProduct() {
        let data = {
            name: $("#productName").val(),
            description: $("#productDescription").val(),
            stock: $("#productStock").val().split('.').join(''),
            price: $("#productPrice").val().split('.').join(''),
            weight: $("#productWeight").val().split('.').join(''),
            thumbnail: base64Image
        }

        $.ajax({
            data: data,
            url: "{{ url('product/insert') }}",
            method: 'POST',
            success: function(result) {
                $("#itemModal").modal("hide")

                resetModal()
                showProducts()

                toastr.success("Successfully Add New Product")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function readURL(input, id) {
        if (input.files && input.files[0]) {
            $(".custom-file-label").text(input.files[0].name)
            var reader = new FileReader()

            reader.onloadend = function() {
                $("#cropperModal").modal('show')
                $(id).attr('src', reader.result)
            }

            // convert to base64 string
            reader.readAsDataURL(input.files[0])
        }
    }

    function loadProduct(id_product) {
        $.ajax({
            data: {
                id_product: id_product
            },
            url: "{{ url('product/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                // Set Input Value
                $("#productID").val(result.data.id_product)
                $("#productName").val(result.data.name)
                $("#productWeight").val(result.data.weight)
                $("#productStock").val(result.data.stock)
                $("#productPrice").val(result.data.price)
                $("#productDescription").val(result.data.description)
                base64Image = result.data.thumbnail

                // Product Name Set Read Only
                $("#productName").attr("readonly", "true")

                // Trigger Event
                $("#productWeight").trigger("keyup")
                $("#productStock").trigger("keyup")
                $("#productPrice").trigger("keyup")

                // Set Current Thumbnail Preview
                let thumbnail = document.createElement("img")
                $(thumbnail).attr("src", result.data.thumbnail)
                $("#previewImage").append(thumbnail)

                // Change Submit Button Event & Text
                $("#btnItemModal").html("Update")
                $("#btnItemModal").attr("onclick", "updateProduct()")

                $("#itemModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function updateProduct() {
        let data = {
            id_product: $("#productID").val(),
            name: $("#productName").val(),
            description: $("#productDescription").val(),
            stock: $("#productStock").val().split('.').join(''),
            price: $("#productPrice").val().split('.').join(''),
            weight: $("#productWeight").val().split('.').join(''),
            thumbnail: base64Image
        }

        $.ajax({
            data: data,
            url: "{{ url('product/update') }}",
            method: 'POST',
            success: function(result) {
                $("#itemModal").modal("hide")

                resetModal()
                showProducts()

                toastr.success("Successfully Update Product")
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

                toastr.success("Successfully Remove Product")
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
        base64Image = ""

        $("#btnItemModal").html("Submit")
        $("#btnItemModal").attr("onclick", "addProduct()")
    }
</script>

@endsection