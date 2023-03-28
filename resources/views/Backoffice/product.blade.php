@extends('Template.backoffice')
@section('title', 'Claerious - Produk')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Produk</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#itemModal">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Produk Baru
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="itemTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Gambar Utama</th>
                                <th>Nama</th>
                                <th>Stok</th>
                                <th>Harga</th>
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

<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 600px;">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Produk</h5>
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
                                <label>Kategori</label>
                                <select class="form-control" id="productCategory">
                                    <option selected disabled value="0">Pilih Kategori</option>
                                    <?php
                                        for($i = 0 ; $i < sizeof($data["categories"]) ; $i++) {
                                    ?>

                                        <option value="<?= $data["categories"][$i]->id_category ?>"><?= $data["categories"][$i]->name ?></option>

                                    <?php
                                        }
                                    ?>
                                </select>
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
                                <label>Deskripsi</label>
                                <textarea id="productDescription" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Gambar Utama</label>
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

                        <hr>

                        <div class="row">
                            <div class="form-group col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="groupPriceToggle" id="groupPriceToggle">
                                    <label class="form-check-label" for="voucherType">
                                        Aktifkan Harga Grosir (Grup)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="tabGroupPrice" style="display: none;">
                            <h4 style="padding-bottom: 12px;">Harga Grup</h4>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Minimal Pembelian Produk</label>
                                    <input type="text" class="form-control form-control-user" id="groupMinPurchase">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Harga</label>
                                    <input type="text" class="form-control form-control-user" id="groupPrice">
                                </div>
                            </div>

                            <div class="row" style="margin-bottom: 16px;">
                                <div class="col-lg-12">
                                    <div class="d-flex" style="justify-content: flex-end;">
                                        <button type="button" class="btn btn-secondary" onclick="addGroupPrice()">Tambah Harga</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="groupPriceTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Minimal Produk</th>
                                            <th>Harga</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablePriceList">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <hr>

                        <div id="tabProductImage" style="margin-bottom: 12px;">
                            <h4 style="padding-bottom: 12px;">Gambar Tambahan</h4>

                            <div id="productSubImage" name="productSubImage" class="dropzone col-lg-12 text-center">
                                <div class="dz-default dz-message" data-dz-message><span>Klik untuk upload gambar</span></div>
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
    let pageIndex = 0, showEntries = 10, groupPrice = [], productImage = [], ctrPrice = 0

    let currentProfileImage = "", currentProfileImageURL = ""
    Dropzone.autoDiscover = false

    $(document).ready(function() {
        $("#productImage").change(function () {
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

        $("#groupPriceToggle").change(function () {
            if ($("#groupPriceToggle").is(":checked")) {
                $("#tabGroupPrice").css("display", "block")
            } else {
                $("#tabGroupPrice").css("display", "none")
            }
        })

        $("#productSubImage").dropzone({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "#",
            addRemoveLinks: true,
            uploadMultiple: false,
            thumbnailWidth: 200,
            thumbnailHeight: 200,
            acceptedFiles: ".jpeg,.jpg,.png,.jfif",
            maxFiles: 4,
            maxFilesize: 1, // Max File Size : 1 MB
            init: function() {
                this.on("addedfile", function(file) {

                    // Get file extension
                    extension = file.name.split('.')
                    extension = extension[extension.length - 1]

                    // if (currentProfileImage) { // Check if old chosen file exist
                    //     this.removeFile(currentProfileImage)
                    // }
                    if (file.size / 1000 > 1000) { // Check if file size > 1 MB
                        this.removeFile(file)
                        toastr.error("Maksimal ukuran file adalah 1 MB")

                        console.log(file.size)
                    }
                    if (extension != "jpeg" && extension != "jpg" ) { // Check if file extension != jpeg or jpg
                        this.removeFile(file)
                        toastr.error("Pilih file .jpeg atau .jpg")
                    }

                    currentProfileImage = file
                    currentProfileImageURL = ""

                    $('#profile').val("")
                })
            },
            success: function(file, response) {
                currentProfileImageURL = file.dataURL

                $('#profile').val(file.dataURL)
            }
        })

        showProducts()
    })

    function showProducts() {
        table = $('#itemTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/product/select",
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
                "targets": 2
            }],
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
                                        Ubah Produk
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a id="optionDelete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal" 
                                        onclick="deleteProductModal('${row.id_product}')">
                                        <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Hapus Produk
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
        let newData = new FormData()
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
            url: "{{ url('product/insert') }}",
            method: 'POST',
            processData: false,
            contentType: false,
            success: function(result) {
                $("#itemModal").modal("hide")

                resetModal()
                showProducts()

                toastr.success("Berhasil tambah produk baru")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function addGroupPrice() {
        let newMinPurchase = $("#groupMinPurchase").val()
        let newPrice = $("#groupPrice").val()

        let newRow = document.createElement("tr")
        newRow.setAttribute("id", "priceRow-" + ctrPrice)
        let newCol = document.createElement("td")
        newCol.innerHTML = newPrice
        newRow.appendChild(newCol)

        newCol = document.createElement("td")
        newCol.innerHTML = newMinPurchase
        newRow.appendChild(newCol)

        newCol = document.createElement("td")
        let newBtn = document.createElement("button")
        let newIcon = document.createElement("i")
        newIcon.className = "fas fa-fw fa-trash"
        newBtn.appendChild(newIcon)
        newBtn.className = "btn btn-danger"
        newBtn.setAttribute("style", "padding: 4px 12px")
        newBtn.setAttribute("onclick", "removeGroupPrice('" +  ctrPrice + "')")
        newCol.appendChild(newBtn)
        newRow.appendChild(newCol)

        document.getElementById("tablePriceList").appendChild(newRow)

        let data = {
            id: ctrPrice,
            minPurchase: newMinPurchase,
            price: newPrice.split('.').join('')
        }

        groupPrice.push(data)
        ctrPrice++

        $("#groupMinPurchase").val("")
        $("#groupPrice").val("")
    }

    function removeGroupPrice(id) {
        let index = 0 ;

        for(let i = 0 ; i < groupPrice.length ; i++) {
            if (groupPrice[i].id == id) {
                index = i
            }
        }

        $("#priceRow-" + id).remove()

        groupPrice.splice(index, 1)
        console.log(groupPrice)
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
                $("#btnItemModal").html("Simpan")
                $("#btnItemModal").attr("onclick", "updateProduct()")

                $("#productCategory").val(result.data.id_category)

                $("#itemModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })

        $.ajax({
            data: {
                id_product: id_product
            },
            url: "{{ url('product/getPrices') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                let groupPrices = result.prices

                if (groupPrices.length > 0) {
                    $("#groupPriceToggle").prop("checked", true)
                    $("#groupPriceToggle").trigger("change")
                }

                for(let i = 0 ; i < groupPrices.length ; i++) {
                    let newRow = document.createElement("tr")
                    newRow.setAttribute("id", "priceRow-" + ctrPrice)
                    let newCol = document.createElement("td")
                    newCol.innerHTML = groupPrices[i].price
                    newRow.appendChild(newCol)

                    newCol = document.createElement("td")
                    newCol.innerHTML = groupPrices[i].target_accumulation
                    newRow.appendChild(newCol)

                    newCol = document.createElement("td")
                    let newBtn = document.createElement("button")
                    let newIcon = document.createElement("i")
                    newIcon.className = "fas fa-fw fa-trash"
                    newBtn.appendChild(newIcon)
                    newBtn.className = "btn btn-danger"
                    newBtn.setAttribute("style", "padding: 4px 12px")
                    newBtn.setAttribute("onclick", "removeGroupPrice('" +  ctrPrice + "')")
                    newCol.appendChild(newBtn)
                    newRow.appendChild(newCol)

                    document.getElementById("tablePriceList").appendChild(newRow)

                    let data = {
                        id: ctrPrice,
                        minPurchase: groupPrices[i].target_accumulation,
                        price: groupPrices[i].price
                    }

                    groupPrice.push(data)
                    ctrPrice++
                }
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