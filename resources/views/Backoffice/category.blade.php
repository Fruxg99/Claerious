@extends('Template.backoffice')
@section('title', 'Claerious - Kategori')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kategori</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#categoryModal">
        <i class="fas fa-plus fa-sm text-white-50 mr-2"></i> Kategori Baru
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoryTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Nama</th>
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

<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Kategori</h5>
                        <input type="hidden" class="form-control form-control-user" id="categoryID">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Nama Kategori</label>
                                <input type="text" class="form-control form-control-user" id="categoryName">
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
                                                <input type='file' id="categoryImage" class="custom-file-input">
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModal()">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="addCategory()" id="btnCategoryModal">Simpan</button>
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
                <div style="text-align:center; font-size: 16pt; font-weight: 700;" class="mb-2 text-danger">Hapus Kategori</div>
                <div style="text-align:center;" class="mb-4" id="deleteCategoryName"></div>
                <div style="text-align:center;" class="mb-4">
                    Yakin ingin hapus kategori ini?<br>Kategori yang sudah dihapus tidak dapat dikembalikan
                </div>
                <div style="text-align: center;">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" type="button" id="btnDeleteCategory" data-dismiss="modal">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cropBoxData, canvasData, cropper, image, base64Image = ""
    let pageIndex = 0, showEntries = 10

    $(document).ready(function() {
        $("#categoryImage").change(function() {
            readURL(this, "#imagePreview")
            $("#categoryModal").modal('hide')
            $("#cropperModal").modal('show')
        })
        $("#btnRemoveImage").click(function () {
            base64Image = ""
            $("#previewImage").empty('canvas')
            $(".custom-file-label").text("Choose Image")
            $("#categoryImage").val("")
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
            $("#categoryModal").modal('show')

            cropper.destroy()
        })

        showCategories()
    })

    function showCategories() {
        table = $('#categoryTable').DataTable({
            "stateSave": true,
            "destroy": true,
            "ajax": {
                "url": "/category/select",
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
                    render: function(data, type, row) {
                        return `
                            <li class="nav-item dropdown no-arrow" style="list-style: none;">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                                    <a id="optionUpdate" class="dropdown-item" href="#" data-toggle="modal" data-target="#categoryModal" 
                                        onclick="loadCategory('${row.id_category}')">
                                        <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Ubah Kategori
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a id="optionDelete" class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal" 
                                        onclick="deleteCategoryModal('${row.id_category}')">
                                        <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Hapus Kategori
                                    </a>
                                </div>
                            </li>
                        `
                    }
                }
            ]
        })
    }

    function addCategory() {
        let data = {
            name: $("#categoryName").val(),
            thumbnail: base64Image
        }

        $.ajax({
            data: data,
            url: "{{ url('category/insert') }}",
            method: 'POST',
            success: function(result) {
                $("#categoryModal").modal("hide")

                resetModal()
                showCategories()

                toastr.success("Berhasil menambahkan kategori baru")
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

    function loadCategory(id_category) {
        $.ajax({
            data: {
                id_category: id_category
            },
            url: "{{ url('category/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                // Set Input Value
                $("#categoryID").val(result.data.id_category)
                $("#categoryName").val(result.data.name)
                base64Image = result.data.thumbnail

                // Set Current Thumbnail Preview
                let thumbnail = document.createElement("img")
                $(thumbnail).attr("src", result.data.thumbnail)
                $("#previewImage").append(thumbnail)

                // Change Submit Button Event & Text
                $("#btnCategoryModal").html("Simpan")
                $("#btnCategoryModal").attr("onclick", "updateCategory()")

                $("#categoryModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function updateCategory() {
        let data = {
            id_category: $("#categoryID").val(),
            name: $("#categoryName").val(),
            thumbnail: base64Image
        }

        $.ajax({
            data: data,
            url: "{{ url('category/update') }}",
            method: 'POST',
            success: function(result) {
                $("#categoryModal").modal("hide")

                resetModal()
                showCategories()

                toastr.success("Berhasil ubah kategori")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function deleteCategoryModal(id_category) {
        $.ajax({
            data: {
                id_category: id_category
            },
            url: "{{ url('category/selectByID') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                $("#btnDeleteCategory").attr("onclick", `deleteCategory('${id_category}')`)
                $("#deleteCategoryName").html(result.data.name)
                $("#deleteModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function deleteCategory(id_category) {
        $.ajax({
            data: {
                id_category: id_category
            },
            url: "{{ url('category/delete') }}",
            method: 'POST',
            success: function(result) {
                $("#deleteModal").modal("hide")

                resetModal()
                showCategories()

                toastr.success("Berhasil hapus kategori")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function resetModal() {
        $("#categoryID").val("")
        $("#categoryName").val("")
        $("#categoryImage").val("")
        $("#previewImage").html("")
        $(".custom-file-label").text("Choose Image")
        base64Image = ""

        $("#btnCategoryModal").html("Submit")
        $("#btnCategoryModal").attr("onclick", "addCategory()")
    }
</script>

@endsection