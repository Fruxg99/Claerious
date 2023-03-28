@extends('Template.store')
@section('title', 'Claerious - Detail Produk')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<section class="hero hero-normal">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Kategori</span>
                    </div>
                    <ul>

                        <!-- Categories -->
                        <?php for($i = 0 ; $i < sizeof($data["categories"]) ; $i++) { ?>

                            <li><a href="/product?category=<?= urlencode($data["categories"][$i]->name) ?>"><?= $data["categories"][$i]->name ?></a></li>

                        <?php } ?>

                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form" style="width: 100%;">
                        <form action="{{ url('/product') }}" method="POST">
                            @csrf
                            <input type="text" placeholder="Cari nama produk" name="search" id="search" value="<?= $data['search'] ?>">
                            <button type="submit" class="site-btn">CARI</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large"
                            src="<?= $data['item']->thumbnail ?>" alt="">
                    </div>
                    <div class="product__details__pic__slider owl-carousel">
                        <?php
                            for($i = 0 ; $i < sizeof($data["product_images"]) ; $i++) {
                        ?>
                            <img data-imgbigurl="https://<?= $data['product_images'][$i]->thumbnail ?>" src="https://<?= $data['product_images'][$i]->thumbnail ?>" alt="">
                        <?php 
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?= $data["item"]->name ?></h3>

                    <div class="product__details__rating">

                        <!-- Rating -->
                        <?php if ($data["item"]->rating_count > 0) { ?>

                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <span>(<?= number_format($data["item"]->rating_count) ?> ulasan)</span>

                        <?php } else { ?>

                            <span class="m-0">Belum terulas</span>

                        <?php } ?>

                    </div>

                    <div class="product__details__price">Rp <?= number_format($data["item"]->price,0,",",".") ?> <small style="font-size: 14px; color: #1C1C1C; cursor: pointer;" data-toggle="modal" data-target="#priceModal">+<?= sizeof($data['prices']) ?> Harga lainnya</small></div>
                    <div class="product__details__quantity">
                        <div class="quantity">
                            <div class="pro-qty">
                                <input type="text" value="1" id="productQty">
                            </div>
                        </div>
                    </div>
                    <button class="primary-btn" onclick='addToCart(`<?= $data["item"]->id_product ?>`)'>Tambah ke keranjang</button>
                    <button class="heart-icon" onclick='addWishlist(`<?= $data["item"]->id_product ?>`)' id="iconWishlist"><i class="fa-regular fa-heart"></i></button>
                    <button class="heart-icon" onclick='addFavorite(`<?= $data["item"]->id_product ?>`)' id="iconFavorite"><i class="fa-regular fa-star"></i></i></button>
                    <ul>
                        <li><b>Ketersediaan</b> <span><?= $data["item"]->stock ?> tersisa</span></li>
                        <li><b>Berat</b> <span><?= number_format($data["item"]->weight) ?> gram</span></li>
                        <li><b>Bagikan</b>
                            <div class="share">
                                <a style="cursor: pointer;" onclick="shareWhatsapp()"><i class="fa fa-whatsapp"></i></a>
                                <a style="cursor: pointer;" onclick="copyLink()"><i class="fa-solid fa-link"></i></a>
                            </div>
                        </li>
                        <li class="product__details__store"><h3 style="font-size: 24px; margin: 0;">Penjual</h3></li>
                        <li class="product__details__store" style="border: none; padding: 0; margin: 0;">
                            <img src="<?= $data["store"]->profile_picture ?>" alt="" class="store__image">
                            <div class="store__details">
                                <div class="store__details__name"><a href="../seller/<?= urlencode($data["store"]->name) ?>/<?= urlencode($data["store"]->id_seller) ?>"><?= $data["store"]->name ?></a></div>
                                <div class="store__details__city text-grey"><?= $data["store"]->city_name ?></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#deskripsi" role="tab"
                                aria-selected="true">Deskripsi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#ulasan" role="tab"
                                aria-selected="false">Ulasan <span>(<?= number_format($data["item"]->rating_count) ?>)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#grup" role="tab"
                                aria-selected="false">Grup</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="deskripsi" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Deskripsi Produk</h6>
                                <p><?= $data["item"]->description ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="ulasan" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Ulasan Produk</h6>
                            </div>
                        </div>
                        <div class="tab-pane" id="grup" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <h6>Daftar Grup</h6>
                                    </div>
                                    <div class="col-lg-7 col-md-6"></div>
                                    <div class="col-lg-2 col-md-3">
                                        <button class="btn btn-primary" style="padding: 4px 16px;" data-toggle="modal" data-target="#groupModal">Buat Grup Baru</button>
                                    </div>
                                </div>

                                <?php 
                                    if (sizeof($data["groups"]) > 0) {
                                        for($i = 0 ; $i < sizeof($data["groups"]) ; $i++) {
                                ?>

                                        <div class='profile-address'>
                                            <div class='list-alamat-content'>
                                                <div style="font-weight: 700; font-size: 20px;">Grup <?= $data["groups"][$i]->name ?> (<?= $data["groups"][$i]->current_accumulation ?>/<?= $data["groups"][$i]->target_accumulation ?>)</div>
                                                <div style="font-size: 16px;">Harga Satuan : Rp <?= number_format($data["groups"][$i]->price,0,",",".") ?></div>
                                            </div>
                                            <div class='profile-address-action'>
                                                <button class='btn btn-primary rounded btn-alamat-hapus' onclick='joinGroup("<?= $data["groups"][$i]->id_group ?>")'>Gabung Grup</button>
                                            </div>
                                        </div><hr>

                                <?php 
                                        }
                                    } else {
                                ?>

                                    <h4>Tidak ada grup tersedia</h4>

                                <?php 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Daftar Harga Grup</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Minimal Pembelian</th>
                                    <th scope="col">Harga</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php for($i = 0 ; $i < sizeof($data["prices"]) ; $i++) { ?>

                                    <tr>
                                        <td class="shoping__cart__quantity">
                                            <?= number_format($data["prices"][$i]["price"],0,",",".") ?> pc(s)
                                        </td>
                                        <td class="shoping__cart__price" id="price-<?= $i ?>">
                                            Rp <?= number_format($data["prices"][$i]["price"],0,",",".") ?>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <form action="{{ url('/product/group/payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="leaderID" value="<?= json_decode($_SESSION["user"])->id_user ?>">
                        <div class="card-header py-3">
                            <h5 class="m-0 font-weight-bold text-primary">Buat Grup Baru</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <input type="hidden" name="productID" value="<?= $data["item"]->id_product ?>">
                                    <label>Pilih Target Harga<small class="text-danger">*</small></label>
                                    <div class="input-group">
                                        <select class="form-control form-control-user" id="groupPriceSelect" name="groupPriceSelect">
                                            <option selected disabled value="-1">Pilih Target Harga</option>
                                            <?php 
                                                for($i = 0 ; $i < sizeof($data["prices"]) ; $i++) {
                                            ?>
                                                <option value="<?= $data["prices"][$i]["id"] ?>"><?= $data["prices"][$i]["target_accumulation"] ?> pc(s) - Rp <?= number_format($data["prices"][$i]["price"],0,",",".") ?></option>
                                            <?php 
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Jumlah Produk yang Dibeli<small class="text-danger">*</small></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="groupQtyPurchase" name="groupQtyPurchase" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetGroupModal()">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnGroupModal">Buat</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="joinGroupModal" tabindex="-1" role="dialog" aria-labelledby="joinGroupModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <form action="{{ url('/product/group/join') }}" method="POST">
                        @csrf
                        <div class="card-header py-3">
                            <h5 class="m-0 font-weight-bold text-primary">Gabung Grup <span id="groupName"></span></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <input type="hidden" id="productID" name="productID" value="<?= $data["item"]->id_product ?>">
                                    <input type="hidden" id="groupID" name="groupID">
                                    <input type="hidden" id="groupPriceID" name="groupPriceID">
                                    <input type="hidden" id="leaderID" name="leaderID">
                                    <label>Target Harga<small class="text-danger">*</small></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" id="joinPrice" name="joinPrice" aria-describedby="basic-addon1" required disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Jumlah Produk yang Dibeli<small class="text-danger">*</small></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="joinQtyPurchase" name="joinQtyPurchase" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetJoinGroupModal()">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnJoinGroupModal">Gabung</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()
            checkWishlist()
            checkFavorite()

        <?php } ?>

    })

    function joinGroup(group_id) {
        $("#groupID").val(group_id)

        console.log(group_id)

        $.ajax({
            data: {
                id_group: group_id
            },
            url: "{{ url('product/get-group') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)
                group = result.group[0]
                console.log(group)

                $("#groupPriceID").val(group.id_group_price)
                $("#groupName").html(group.name + " (" + group.current_accumulation + "/" + group.target_accumulation + ")")
                $("#leaderID").val(group.id_leader)
                $("#joinPrice").val(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(group.price).substring(3))
                
                $("#joinGroupModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function showPrices(product_id) {
        let data = {
            product_id: product_id
        }

        $.ajax({
            data: data,
            url: "{{ url('product/get-prices') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)
                prices = result.prices
                console.log(prices)
                
                $("#priceModal").modal("show")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function addFavorite(product_id) {

        <?php if (isset($_SESSION["user"])) { ?>

            let data = {
                product_id: product_id
            }

            $.ajax({
                data: data,
                url: "{{ url('favorite/add-favorite') }}",
                method: 'POST',
                success: function(result) {
                    updateFavoriteCount()

                    if (result.message == "Removed from favorite") {
                        toastr.success("Produk dihapus dari favorite")
                    } else {
                        toastr.success("Produk ditambahkan ke favorite")
                    }

                    // Check if function exist
                    if (typeof checkFavorite === "function") {
                        checkFavorite()
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(thrownError)
                }
            })

        <?php } else { ?>

            $("#loginModal").modal("show")

        <?php } ?>

    }

    function checkWishlist() {
        $.ajax({
            data: {
                id_product: "<?= $data["item"]->id_product ?>"
            },
            url: "{{ url('/wishlist/check-wishlist') }}",
            method: 'POST',
            success: function(result) {
                if (result.message == "wishlisted") {
                    $("#iconWishlist").html(`<i class="fa-solid fa-heart"></i>`)
                } else {
                    $("#iconWishlist").html(`<i class="fa-regular fa-heart"></i>`)
                }
            }
        })
    }

    function checkFavorite() {
        $.ajax({
            data: {
                id_product: "<?= $data["item"]->id_product ?>"
            },
            url: "{{ url('/favorite/check-favorite') }}",
            method: 'POST',
            success: function(result) {
                if (result.message == "favorited") {
                    $("#iconFavorite").html(`<i class="fa-solid fa-star"></i>`)
                } else {
                    $("#iconFavorite").html(`<i class="fa-regular fa-star"></i>`)
                }
            }
        })
    }

    function shareWhatsapp() {
        let text = encodeURIComponent("Cek produk ini!\n\n<?= $data["item"]->name ?>\n\nHanya dengan harga Rp <?= number_format($data["item"]->price,0,",",".") ?> saja kamu sudah bisa mendapatkan produk ini. Untuk selengkapnya cek di link berikut:\n<?= url()->current() ?>")

        window.open(`https://wa.me/?text=${text}`)
    }

    function copyLink() {
        navigator.clipboard.writeText("<?= url()->current() ?>")
        toastr.success("Tautan tersalin")
    }

    function resetGroupModal() {
        $("#groupQtyPurchase").val("")
        $("#groupPriceSelect").val("-1")
    }

    function resetJoinGroupModal() {
        $("#joinPrice").val("")
        $("#joinQtyPurchase").val("")
    }

    $("#groupQtyPurchase").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#groupQtyPurchase").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })

    $("#joinQtyPurchase").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#joinQtyPurchase").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })
</script>

@endsection