<?php
    // Check if session has started
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ url('ogani/js/jquery-3.3.1.min.js') }}"></script>

    <title>Claerious - Daftar</title>

    @include('Template.Store.head_content')

</head>

<body id="page-top">
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> claerious@gmail.com</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header__logo">
                        <a href="{{ url('') }}"><img src="{{ url('assets/image/logo.png') }}" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="featured spad" style="padding-top: 40px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Daftar sebagai Penjual</h2>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-danger  alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	
                    <strong>{{ session('warning') }}</strong>
                </div>
            @endif

            <form action="{{ url('do-register-store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" id="profile" name="profile" value="">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row" style="padding: 0 0.75em;">
                            <label>Profil Toko</label>
                            <div id="storeProfile" name="storeProfile" class="dropzone col-lg-11 text-center">
                                <div class="dz-default dz-message" data-dz-message><span>Klik untuk upload gambar</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Nama Toko<small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Alamat<small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-user @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                                @error('address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Provinsi<small class="text-danger">*</small></label>
                                <select class="form-select @error('province') is-invalid @enderror" style="width: 100%;" id="province" name="province" onchange="loadCity()" value="{{ old('province') }}">
                                    <option selected disabled>Pilih Provinsi</option>

                                    <?php for($i = 0 ; $i < sizeof($data["provinces"]) ; $i++) { ?>

                                        <option value="<?= $data["provinces"][$i]->province_id ?>"><?= $data["provinces"][$i]->province ?></option>

                                    <?php } ?>

                                </select>
                                
                                @error('province')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Kabupaten / Kota<small class="text-danger">*</small></label>
                                <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" style="width: 100%;" value="{{ old('city') }}">
                                    <option selected disabled>Pilih Provinsi terlebih dahulu</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Kode Pos</label>
                                <input type="text" class="form-control form-control-user @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control form-control-user @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end" style="padding: 0 0.75em;">
                    <button type="submit" class="btn btn-primary" id="btnItemModal">Daftar</button>
                </div>
            </form>
        </div>
    </section>

    @include('Template.Store.footer')

    @include('Template.Store.script')

    <script>
        let currentProfileImage = "", currentProfileImageURL = ""
        Dropzone.autoDiscover = false
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            $("#storeProfile").dropzone({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "#",
                uploadMultiple: false,
                thumbnailWidth: null,
                thumbnailHeight: null,
                acceptedFiles: ".jpeg,.jpg,.png,.jfif",
                maxFilesize: 0.2, // Max File Size : 200 KB
                init: function() {
                    this.on("addedfile", function(file) {

                        // Get file extension
                        extension = file.name.split('.')
                        extension = extension[extension.length - 1]

                        if (currentProfileImage) { // Check if old chosen file exist
                            this.removeFile(currentProfileImage)
                        }
                        if (file.size / 1000 > 200) { // Check if file size > 200 KB
                            this.removeFile(file)
                            toastr.error("Maksimal ukuran file adalah 200 KB")
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
        })

        function togglePass(element) {
            if ($("#" + element).attr("type") == "password") {
                $("#" + element).attr("type", "text")
                $("#toggle_" + element).html(`<i class="fa-solid fa-eye-slash"></i>`)
            } else {
                $("#" + element).attr("type", "password")
                $("#toggle_" + element).html(`<i class="fa-solid fa-eye"></i>`)
            }
        }

        function loadCity() {
            let data = {
                province: $("#province").val()
            }

            $.ajax({
                data: data,
                url: "{{ url('/get-city') }}",
                method: 'POST',
                success: function(result) {
                    let html = `<option>Pilih Kabupaten / Kota</option>`

                    for(let i = 0 ; i < result.length ; i++) {
                        html += `<option value="${result[i].city_id}">${result[i].city_name}</option>`
                    }

                    $("#city").html(html)
                    $("#city").trigger('change')

                    html = ``

                    for(let i = 0 ; i < result.length ; i++) {
                        html += `<li data-value="${result[i].city_id}" class="option">${result[i].city_name}</li>`
                    }

                    $("#city").siblings(".nice-select").children("span").html("Pilih Kabupaten / Kota")
                    $("#city").siblings(".nice-select").children("ul").html(html)
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    
                }
            })
        }

        function resetValidation() {
            $("#userEmail").removeClass("is-invalid")
            $("#userPass").removeClass("is-invalid")
            $("#userCPass").removeClass("is-invalid")
            $("#userName").removeClass("is-invalid")
            $("#userPhone").removeClass("is-invalid")
        }
    </script>

</body>
</html>