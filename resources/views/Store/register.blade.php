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
                        <h2>Daftar dengan Email</h2>
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

            <form action="{{ url('do-register') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="form-group col-lg-11">
                                <label>Alamat Email<small class="text-danger">*</small></label>
                                <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-11">
                                <label>Sandi<small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" id="password" name="password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('password')" id="toggle_password"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-11">
                                <label>Ulangi Sandi<small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-user @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('confirm_password')" id="toggle_confirm_password"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                    @error('confirm_password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Nama<small class="text-danger">*</small></label>
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
                                <label>No. Telepon</label>
                                <input type="text" class="form-control form-control-user @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="form-group col-lg-11">
                                <label>Jenis Kelamin<small class="text-danger">*</small></label>
                                <div class="row" style="padding: 0 0.75rem;">
                                    <div class="form-check" style="width: 50%;">
                                        <input class="form-check-input" type="radio" value="0" name="gender" checked>
                                        <label class="form-check-label" for="gender">
                                            Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" value="1" name="gender">
                                        <label class="form-check-label" for="gender">
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            
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