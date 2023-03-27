<!-- Header Section Begin -->
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
            <div class="col-lg-3">
                <div class="header__logo">
                    <a href="{{ url('') }}"><img src="{{ url('assets/image/logo.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="header__cart">
                    <ul>
                        
                        <?php 
                            if (isset($_SESSION["user"])) {
                        ?>

                            <li><a href="{{ url('/favorite') }}"><i class="fa fa-star"></i> <span class="favoriteCount">0</span></a></li>
                            <li><a href="{{ url('/wishlist') }}"><i class="fa fa-heart"></i> <span class="wishlistCount">0</span></a></li>
                            <li><a href="{{ url('/cart') }}"><i class="fa fa-shopping-bag"></i> <span class="cartCount">0</span></a></li>
                            <li>
                                <div class="header__account dropdown" id="accountLogin">
                                    <!-- <i class="fa fa-user"></i> <?= json_decode($_SESSION["user"])->name ?> -->
                                    <a href="{{ url('/profile') }}" class="dropbtn">
                                        <i class="fa fa-user"></i> 
                                        <?= explode(" ", json_decode($_SESSION["user"])->name)[0] ?>
                                    </a>
                                </div>

                                <div class="dropdown-content">
                                    <div class="row dropdown-profile">
                                        <div class="dropdown-profile-image">

                                            <?php if (isset(json_decode($_SESSION["user"])->profile_picture)) { ?>

                                                <img src="<?= json_decode($_SESSION['user'])->profile_picture ?>">

                                            <?php } else { ?>

                                                <img src="https://e7.pngegg.com/pngimages/178/595/png-clipart-user-profile-computer-icons-login-user-avatars-monochrome-black.png">

                                            <?php } ?>
                                            
                                        </div>
                                        <div class="dropdown-profile-name">
                                            <?= json_decode($_SESSION["user"])->name ?>
                                        </div>
                                    </div>
                                    <div class="dropdown-profile-detail row mt-4">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div>Saldo</div>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <div>Rp <?= number_format(json_decode($_SESSION["user"])->saldo,0,",",".") ?></div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <a href="{{ url('/logout') }}"><i class="fa fa-arrow-right-from-bracket"></i> Keluar</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            
                                        </div>
                                    </div>
                                </div>
                            </li>

                        <?php
                            } else {
                        ?>

                            <li><a onclick='$("#loginModal").modal("show")'><i class="fa fa-star"></i> <span class="favoriteCount">0</span></a></li>
                            <li><a onclick='$("#loginModal").modal("show")'><i class="fa fa-heart"></i> <span class="wishlistCount">0</span></a></li>
                            <li><a onclick='$("#loginModal").modal("show")'><i class="fa fa-shopping-bag"></i> <span class="cartCount">0</span></a></li>
                            <li>
                                <a class="header__account" onclick='$("#loginModal").modal("show")'>
                                    <i class="fa fa-user"></i> Masuk
                                </a>
                            </li>

                        <?php
                            }
                        ?>
                            
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Section End -->

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Masuk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger print-error-msg" id="errMsg" style="display: none;">
                    Email atau sandi salah
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label>Email</label>
                        <input type="email" class="form-control form-control-user" id="loginEmail" placeholder="email@contoh.com">
                        <div class="invalid-feedback" id="loginEmailMessage"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12">
                        <label>Sandi</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-user" id="loginPassword" name="loginPassword">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePass('loginPassword')" id="toggle_loginPassword"><i class="fa-solid fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary" style="width: 100%;" onclick="login()">Masuk</button>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-login-google" style="width: 100%;" onclick="googleLogin()">
                            <img width="15px" style="margin-bottom:3px; margin-right:5px" alt="Google login" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
                            Google
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-between mt-2">
                        <a href="{{ url('/register') }}" class="login-additional-option">Buat Akun</a>
                        <a href="{{ url('/forgot-password') }}" class="login-additional-option">Lupa Sandi?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePass(element) {
        if ($("#" + element).attr("type") == "password") {
            $("#" + element).attr("type", "text")
            $("#toggle_" + element).html(`<i class="fa-solid fa-eye-slash"></i>`)
        } else {
            $("#" + element).attr("type", "password")
            $("#toggle_" + element).html(`<i class="fa-solid fa-eye"></i>`)
        }
    }

    function login() {
        let data = {
            email: $("#loginEmail").val(),
            password: $("#loginPassword").val()
        }

        $.ajax({
            data: data,
            url: "{{ url('login') }}",
            method: 'POST',
            success: function(result) {
                $("#errMsg").css("display", "none")
                $("#loginEmail").removeClass("is-invalid")
                $("#loginPassword").removeClass("is-invalid")

                location.reload()
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status)
                if (xhr.status == 404) {
                    $("#errMsg").css("display", "block")
                    $("#loginEmail").addClass("is-invalid")
                    $("#loginPassword").addClass("is-invalid")
                }
            }
        })
    }

    function googleLogin() {
        window.location.href = "{{ url('auth/google') }}"
    }
</script>