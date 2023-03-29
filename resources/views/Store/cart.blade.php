@extends('Template.store')
@section('title', 'Claerious - Products')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<section class="shoping-cart spad" style="padding: 20px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex" style="justify-content: flex-end;">
                    <div style="margin-top: 12px;">
                        <button class="btn btn-primary rounded" style="background-color: #4179E8; border: none; outline: none; margin: 0 12px 0 auto; display: block;" data-toggle="modal" data-target="#addressListModal">
                            <i class="fas fa-list" style="margin-right: 7px;"></i> Pilih Alamat
                        </button>
                    </div>
                    <div style="margin-top: 12px;">
                        <button class="btn btn-primary rounded" style="background-color: #4179E8; border: none; outline: none; margin: 0 12px 0 auto; display: block;" data-toggle="modal" data-target="#addressModal">
                            <i class="fas fa-plus" style="margin-right: 7px;"></i> Tambah Alamat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" id="selectedAddress">

                <?php if (sizeof($data["address"]) > 0) { ?>

                    <div class='profile-address' style="border: 1px solid #4179E8; border-radius: 20px; padding: 30px 40px;">
                        <div class='list-alamat-content'>
                            <div style="font-weight: 700; font-size: 20px"><?= $data["address"][0]->label ?></div>
                            <div style="font-size: 16px;"><?= $data["address"][0]->receiver_name ?></div>
                            <div style="font-size: 16px;"><?= $data["address"][0]->address ?>, <?= $data["address"][0]->city_name ?></div>
                            <div style="font-size: 16px;"><?= $data["address"][0]->receiver_phone ?></div>
                        </div>
                    </div>

                <?php } else { ?>

                    <h4 style="width: 100%;">Silahkan Buat Alamat Terlebih Dahulu</h4>


                <?php } ?>
            </div>
        </div>
    </div>
</section>

<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Produk</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tableCart">

                            <?php 
                            
                                $old_seller_id = "";
                                for($i = 0 ; $i < sizeof($data["cart"]) ; $i++) { 
                                    if ($old_seller_id != $data["cart"][$i]["id_seller"]) { 
                                        if ($i != 0) {?>

                                            <tr id="shipment-row-<?= $old_seller_id ?>">
                                                <td class="shoping__cart__item" style="padding: 10px 0;">
                                                    <h5 style="font-size: 16px; font-weight:700; text-align: left; margin-bottom: 0.5rem;">Jenis Pengiriman</h5>
                                                    <select class="form-select shipment-option" id="shipment-<?= $old_seller_id ?>" onchange='updateTotalPrice()'>
                                                        <option selected disabled>Pilih Pengiriman</option>
                                                    </select>
                                                </td>
                                                <td class="shoping__cart__price"></td>
                                                <td class="shoping__cart__quantity" colspan="3">
                                                    <h5 style="font-size: 16px; font-weight:700; text-align: left; margin-bottom: 0.5rem;">Kupon Toko</h5>
                                                    <div class="transaction-voucher" id="voucher-<?= $old_seller_id ?>">
                                                        <div class="transaction-voucher-button" data-toggle="modal" data-target="#modalVoucher" onclick='loadVouchers("<?= $old_seller_id ?>")'><span><i class="fas fa-tags"></i> Lebih hemat pakai kupon</span><i class="fas fa-angle-right font-20"></i></div>
                                                    </div>
                                                    <input type="hidden" class="discount-voucher" id="discount-<?= $old_seller_id ?>" value="0">
                                                </td>
                                            </tr>

                                    <?php      

                                        }
                                        $old_seller_id = $data["cart"][$i]["id_seller"];

                                    ?>

                                        <tr id="seller-row-<?= $data["cart"][$i]["id_seller"] ?>">
                                            <td class="shoping__cart__item" colspan="5" style="padding: 10px 0;">
                                                <img src="<?= $data["cart"][$i]["profile_picture"] ?>" alt="" class="store__image">
                                                <h5 style="font-size: 20px; font-weight: 700;"><?= $data["cart"][$i]["seller_name"] ?></h5>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                        
                                        <tr id="cart-row-<?= $i ?>" name="cart-<?= $data["cart"][$i]["id_seller"] ?>">
                                            <td class="shoping__cart__item">
                                                <img src="<?= $data["cart"][$i]["thumbnail"] ?>" alt="" style="max-width: 100px;">
                                                <h5><?= $data["cart"][$i]["name"] ?></h5>
                                            </td>
                                            <td class="shoping__cart__price" id="price-<?= $i ?>">
                                                Rp <?= number_format($data["cart"][$i]["price"],0,",",".") ?>
                                            </td>
                                            <td class="shoping__cart__quantity">
                                                <div class="quantity">
                                                    <div class="pro-qty">
                                                        <input type="text" value='<?= $data["cart"][$i]["qty"] ?>' id="qty-<?= $i ?>" onchange='updateCartQty(`<?= $i ?>`)'>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="shoping__cart__total" id="total-<?= $i ?>">
                                                Rp <?= number_format($data["cart"][$i]["price"] * $data["cart"][$i]["qty"],0,",",".") ?>
                                            </td>
                                            <td class="shoping__cart__item__close">
                                                <span class="icon_close" onclick='removeFromCart(`<?= $i ?>`)'></span>
                                            </td>
                                            <input type="hidden" value='<?= $data["cart"][$i]["id_product"] ?>' id="id-<?= $i ?>">
                                            <input type="hidden" class="weight-product" value='<?= $data["cart"][$i]["weight"] * $data["cart"][$i]["qty"] ?>' id="weight-<?= $data["cart"][$i]["id_seller"] ?>-<?= $i ?>">
                                        </tr>

                                <?php if ($i == (sizeof($data["cart"]) - 1)) { ?>

                                    <tr id="shipment-row-<?= $data["cart"][$i]["id_seller"] ?>">
                                        <td class="shoping__cart__item" style="padding: 10px 0;">
                                            <h5 style="font-size: 16px; font-weight:700; text-align: left; margin-bottom: 0.5rem;">Jenis Pengiriman</h5>
                                            <select class="form-select shipment-option" id="shipment-<?= $data["cart"][$i]["id_seller"] ?>" onchange='updateTotalPrice()'>
                                                <option selected disabled>Pilih Pengiriman</option>
                                            </select>
                                        </td>
                                        <td class="shoping__cart__price"></td>
                                        <td class="shoping__cart__quantity" colspan="3">
                                            <h5 style="font-size: 16px; font-weight:700; text-align: left; margin-bottom: 0.5rem;">Kupon Toko</h5>
                                            <div class="transaction-voucher voucher-option" id="voucher-<?= $data["cart"][$i]["id_seller"] ?>">
                                                <div class="transaction-voucher-button" data-toggle="modal" data-target="#modalVoucher" onclick='loadVouchers("<?= $data["cart"][$i]["id_seller"] ?>")'><span><i class="fas fa-tags"></i> Lebih hemat pakai kupon</span><i class="fas fa-angle-right font-20"></i></div>
                                            </div>
                                            <input type="hidden" class="discount-voucher" id="discount-<?= $data["cart"][$i]["id_seller"] ?>" value="0">
                                        </td>
                                    </tr>

                                <?php } 
                            } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Kupon Potongan</h5>
                        <form action="#">
                            <input type="text" placeholder="Masukkan Kode Kupon">
                            <button type="submit" class="site-btn">Gunakan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__checkout">
                    <h5>Ringkasan Keranjang</h5>
                    <ul>
                        <li>Subtotal <span id="subtotal"></span></li>
                        <li>Diskon Kupon <span id="discount" class="text-danger"></span></li>
                        <li>Biaya Pengiriman <span id="shipment"></span></li>
                        <li>Biaya Layanan <span id="service">Rp 5.000</span></li>
                        <li>Total <span id="total"></span></li>
                    </ul>
                    <a onclick="checkout()" class="primary-btn" style="cursor: pointer; color: white;">LANJUT KE PEMBAYARAN</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalVoucher" tabindex="-1" aria-labelledby="modalVoucher" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-body">
                <div class="modal-header" style="align-items: center; padding: 0 0 12px 0; border: none;">
                    <h3>Daftar Kupon</h3>
                </div>
                <div class="invalid-feedback" id="errorCoupon" style="font-weight: bold;"></div>
                <div id="listVoucher" class="list-voucher">
                    <h4 style='opacity: 0.5; margin-top: 10px; text-transform: none;'>Toko ini tidak memiliki kupon</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressListModal" tabindex="-1" aria-labelledby="addressListModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none;">
            <div class="modal-body">
                <div class="modal-header" style="align-items: center; padding: 0 0 12px 0; border: none;">
                    <h3>Daftar Alamat</h3>
                </div>
                <div class="invalid-feedback" id="errorCoupon" style="font-weight: bold;"></div>
                <div id="listAddress" class="list-voucher"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <!-- <form action="{{ url('/add-address') }}" method="POST"> -->
                        <div class="card-header py-3">
                            <h5 class="m-0 font-weight-bold text-primary">Alamat Pengiriman Baru</h5>
                            <input type="hidden" class="form-control form-control-user" id="shipID">
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul style="padding-left: 40px;"></ul>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Nama Penerima<small class="text-danger">*</small></label>
                                    <input type="text" class="form-control form-control-user" id="shipReceiver">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>No. Telepon<small class="text-danger">*</small></label>
                                    <input type="text" class="form-control form-control-user" id="shipPhone">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Label Alamat<small class="text-danger">*</small></label>
                                    <input type="text" class="form-control form-control-user" id="shipName">
                                    <small class="text-muted">contoh: alamat rumah, alamat kantor</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Alamat<small class="text-danger">*</small></label>
                                    <input type="text" class="form-control form-control-user" id="shipAddress">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12">
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
                            <input type="hidden" class="form-control form-control-user" id="shipCityId">
                            <input type="hidden" class="form-control form-control-user" id="shipCity">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label>Kabupaten / Kota<small class="text-danger">*</small></label>
                                    <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" style="width: 100%;" value="{{ old('city') }}" onchange="saveCityData()">
                                        <option selected disabled>Pilih Provinsi terlebih dahulu</option>
                                    </select>
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Kode Pos<small class="text-danger">*</small></label>
                                    <input type="text" class="form-control form-control-user" id="shipPostal">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModal()">Batal</button>
                                <button type="button" class="btn btn-primary" onclick="addShipmentInfo()" id="btnItemModal">Simpan</button>
                            </div>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox" data-client-key="SB-Mid-client-IRE0d_nhs4Jp2k9o"></script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= config('midtrans.client_key') ?>"></script>

<script>
    let cityId = "", cityName = "", addressID = ""

    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()
            updateTotalPrice()
            loadShipment()
            showAddress()

        <?php } ?>

        <?php if (sizeof($data["address"]) > 0) { ?>

            addressID = "<?= $data["address"][0]->id_address ?>"

        <?php } ?>

    })

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
                // $("#city").trigger('change')

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

    function saveCityData() {
        cityId = $("#city").val()
        cityName = $("#city").siblings(".nice-select").children("span").text()
    }

    function showAddress() {
        $.ajax({
            url: "{{ url('address/select') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)
                let html = ``

                if (result.length > 0) {
                    for(let i = 0 ; i < result.length ; i++) {
                        html += 
                            `<div class='profile-address'>
                                <div class='list-alamat-content'>
                                    <div style="font-weight: 600;">${result[i].label}</div>
                                    <div style="font-size: 14px;">${result[i].receiver_name}</div>
                                    <div style="font-size: 14px;">${result[i].address}, ${result[i].city_name}</div>
                                    <div style="font-size: 14px;">${result[i].receiver_phone}</div>
                                </div>
                                <div class='profile-address-action'>
                                    <button class='btn btn-primary rounded btn-alamat-hapus' onclick='setAddress("${result[i].id_address}")'>Gunakan</button>
                                </div>
                            </div><hr>`
                    }
                } else {
                    html += "<h4 style='opacity: 0.75; margin: 0 32px; text-transform: none;'>Anda belum punya alamat pengiriman</h4>"
                }

                $("#listAddress").html(html)
                $("hr").last().remove()
            }
        })        
    }

    function setAddress(id_address) {
        $.ajax({
            data: {
                id_address: id_address
            },
            url: "{{ url('address/selectById') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                addressID = result.id_address

                let html = `<div class='profile-address' style="border: 1px solid #4179E8; border-radius: 20px; padding: 30px 40px;">
                        <div class='list-alamat-content'>
                            <div style="font-weight: 700; font-size: 20px;">${result.label}</div>
                            <div style="font-size: 16px;">${result.receiver_name}</div>
                            <div style="font-size: 16px;">${result.address}, ${result.city_name}</div>
                            <div style="font-size: 16px;">${result.receiver_phone}</div>
                        </div>
                    </div>`

                $("#selectedAddress").html(html)
                $("#addressListModal").modal('hide')

                console.log(id_address)
            }
        })  
    }

    function addShipmentInfo() {
        let data = {
            shipReceiver: $("#shipReceiver").val().trim(),
            shipPhone: $("#shipPhone").val().trim(),
            shipName: $("#shipName").val().trim(),
            shipAddress: $("#shipAddress").val().trim(),
            shipCityId: cityId,
            shipCity: cityName.trim(),
            shipPostal: $("#shipPostal").val().trim()
        }

        $.ajax({
            data: data,
            url: "{{ url('address/insert') }}",
            method: 'POST',
            success: function(result) {
                $("#addressModal").modal("hide")
                $(".print-error-msg").css('display','none')

                resetModal()
                showAddress()

                toastr.success("Berhasil menambahkan alamat baru")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                let msg = xhr.responseJSON.errors
                let checkKey = false

                $(".print-error-msg").find("ul").html('')
                $(".print-error-msg").css('display','block')

                $.each( msg, function( key, value ) {
                    if (key == "shipCityId") {
                        checkKey = true
                    }
                    if (key == "shipCity" && checkKey) {
                        
                    } else {
                        $(".print-error-msg").find("ul").append('<li>'+value+'</li>')
                    }
                })
            }
        })
    }

    function loadShipment() {
        let shipments = $("select.shipment-option")
        let div_shipments = $("div.shipment-option")
        let id_seller = "", weight = 0
        let shipment_result = ""

        shipments.each(function(index, element) {
            weight = 0
            
            id_seller = $(element).attr("id").split("-")[1]

            let weights = $(".weight-product")
            weights.each(function(idx, elm) {
                if ($(elm).attr("id").split("-")[1] == id_seller) {
                    weight += parseInt($(elm).val())
                }
            })

            let data = {
                id_address: "A0001",
                id_seller: id_seller,
                weight: weight
            }

            $.ajax({
                data: data,
                url: "{{ url('shipment/selectBySeller') }}",
                method: 'POST',
                success: function(result) {
                    shipment_result = result

                    // Change bootstrap select
                    let html = `<option value=null>Pilih Pengiriman</option>`
                    let htmlDiv = ``

                    for(let i = 0 ; i < shipment_result.length ; i++) {
                        let courier = shipment_result[i].details[0].costs

                        for(let j = 0 ; j < courier.length ; j++) {
                            html += `<option value="${shipment_result[i].courier}-${courier[j].service}-${new Intl.NumberFormat(undefined, { 
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                        minimumFractionDigits: 0,
                                    }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")}">
                                    ${shipment_result[i].courier} - ${courier[j].service} - ${new Intl.NumberFormat(undefined, { 
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                        minimumFractionDigits: 0,
                                    }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")}</option>`

                            htmlDiv += `<li data-value="${shipment_result[i].courier}-${courier[j].service}-${new Intl.NumberFormat(undefined, { 
                                        style: 'currency',
                                        currency: 'IDR',
                                        maximumFractionDigits: 0,
                                        minimumFractionDigits: 0,
                                    }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")}" class="option">
                                    ${shipment_result[i].courier} - ${courier[j].service} - ${new Intl.NumberFormat(undefined, { 
                                    style: 'currency',
                                    currency: 'IDR',
                                    maximumFractionDigits: 0,
                                    minimumFractionDigits: 0,
                                }).format(courier[j].cost[0].value).replace("IDR", "Rp").replace(/,/g, ".")} </option>`
                        }

                        console.log(courier)
                    }

                    $(element).html(html)

                    $(div_shipments[index]).children("span").html("Pilih Pengiriman")
                    $(div_shipments[index]).children("ul").html(htmlDiv)
                }
            })
        })
    }

    function loadVouchers(id_seller) {
        let vouchers = $(".voucher-option")
        vouchers.each(function(index, element) {
            if ($(element).is('select')) {
                id_seller = $(element).attr("id").split("-")[1]
            }

            let data = {
                id_seller: id_seller
            }

            $.ajax({
                data: data,
                url: "{{ url('/voucher/selectBySeller') }}",
                method: 'POST',
                success: function(result) {
					$("#listVoucher").html('')

                    let html = ``
					for (let i = 0; i < result.length; i++) {
                        html +=
                            `<div class='list-voucher-item'>
                                <div class='list-voucher-content'>
                                    <div class="voucher-content-name" id="coupon-${result[i].id_voucher}"><b>${result[i].name}</b></div>
                                    <div class="voucher-content-snk">Syarat dan Ketentuan</div>
                                    <div class="voucher-snk-list">
                                        <ol style="padding-left: 16px; font-size: 12px; margin: 0;">`

                        if (result[i].type == 0) {
                            // Voucher percentage
                            html += `<li>
                                        <div class="voucher-snk-item">
                                            Diskon ${result[i].discount_percentage}% maksimal ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].max_discount).replace("IDR", "Rp").replace(/,/g, ".")} dengan minimal pembelian senilai ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].min_purchase).replace("IDR", "Rp").replace(/,/g, ".")}.
                                        </div>
                                    </li>`
                        } else if (result[i].type == 1) {
                            // Voucher fixed amount
                            html += `<li>
                                        <div class="voucher-snk-item">
                                            Potongan harga senilai ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].max_discount).replace("IDR", "Rp").replace(/,/g, ".")} dengan minimal pembelian senilai ${new Intl.NumberFormat(undefined, { 
                                                style: 'currency', 
                                                currency: 'IDR',
                                                maximumFractionDigits: 0, 
                                                minimumFractionDigits: 0, 
                                            }).format(result[i].min_purchase).replace("IDR", "Rp").replace(/,/g, ".")}.
                                        </div>
                                    </li>`
                        }

                        if (result[i].usage_limit > 0) {
                            html += `<li>
                                    <div class="voucher-snk-item">
                                        Berlaku untuk ${result[i].usage_limit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} pengguna pertama.
                                    </div>
                                </li>`
                        }

                        html += `
                                    </ol>
                                </div>
                            </div>
                            <div class='list-voucher-action'>
                                <button id='${result[i].id_voucher}' class='btn btn-primary rounded btn-select-voucher' onclick='chooseVoucher(this.id, "${result[i].id_seller}")'>Pilih</button>
                            </div>
                        </div>
                        <hr style="border-top: 1px solid rgba(0,0,0,0.25); margin: 8px 0;">`
                    }

                    if (result.length == 0) {
                        html += `<h4 style='opacity: 0.5;'>Toko ini tidak memiliki kupon</h4>
                                <hr style="border-top: 1px solid rgba(0,0,0,0.25); margin: 8px 0;">`
                        $("#listVoucher").css({overflow: "hidden"})
                    } else {
                        $("#listVoucher").css({overflow: "auto"})
                    }

                    $("#listVoucher").html(html)
                    $("#listVoucher").children().last().remove()
                }
            })
        })
    }

    function chooseVoucher(id_voucher, id_seller) {
        $.ajax({
            type: 'POST',
            url: '{{ url("voucher/selectById") }}',
            data: {
                'id_voucher': id_voucher
            },
            success: function(result) {
                let subtotal = parseInt($("#subtotal").html().split("&nbsp;")[1].replace(/\./g, ''))
                let discount = 0

                if (result.type == 0) {
                    // Voucher percentage
                    if (subtotal > result.min_purchase) {
                        discount = subtotal * result.discount_percentage / 100

                        if (discount > result.max_discount) {
                            discount = result.max_discount
                        }
                    }
                } else if (result.type == 1) {
                    // Voucher fixed amount
                    if (subtotal > result.min_purchase) {
                        discount = result.max_discount
                    }
                }

                $("#discount-" + id_seller).val(discount)

                let html = `<div class="transaction-voucher-button" onclick="cancelVoucher('${id_seller}')"><div><span>Kamu hemat Rp ${discount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</span><span style="font-size: 12px; font-weight: 400;">Menggunakan kupon ${result.name}</span></div><i class="fas fa-angle-right font-20"></i></div>`
                $("#voucher-" + id_seller).html(html)
                $("#modalVoucher").modal('hide')
                
                updateTotalPrice()
            }
        })
    }

    function cancelVoucher(id_seller) {
        html = `<div class="transaction-voucher-button" data-toggle="modal" data-target="#modalVoucher" onclick='loadVouchers("${id_seller}")'><span><i class="fas fa-tags"></i> Lebih hemat pakai kupon</span><i class="fas fa-angle-right font-20"></i></div>`
        $("#voucher-" + id_seller).html(html)
        $("#discount-" + id_seller).val(0)
        updateTotalPrice()
    }

    function updateCartQty(cart_row) {
        let data = {
            user_id: <?= json_decode($_SESSION["user"])->id ?>,
            product_id: $("#id-" + cart_row).val(),
            qty: $("#qty-" + cart_row).val()
        }

        $.ajax({
            data: data,
            url: "{{ url('cart/update-cart-qty') }}",
            method: 'POST',
            success: function(result) {
                updateRowPrice(cart_row)
                updateCartCount()

                let weights = $(".weight-product")
                weights.each(function(index, element) {
                    if ($(element).attr("id").split("-")[2] == cart_row) {
                        $(element).val(result.message * $("#qty-" + cart_row).val())
                    }
                })
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function updateRowPrice(cart_row) {
        let price = parseInt($("#price-" + cart_row).html().trim().split(" ")[1].replace(".",""))
        let qty = $("#qty-" + cart_row).val()
        let totalPrice = qty * price

        $("#total-" + cart_row).html(new Intl.NumberFormat(undefined, { 
            style: 'currency', 
            currency: 'IDR',
            maximumFractionDigits: 0, 
            minimumFractionDigits: 0, 
        }).format(totalPrice).replace("IDR", "Rp").replace(/,/g, "."))

        updateTotalPrice()
    }

    function updateTotalPrice() {
        let rows = $(".shoping__cart__total")
        let discounts = $(".discount-voucher")
        let shipments = $("select.shipment-option")
        let totalPrice = 0, servicePrice = 5000, totalDiscount = 0, totalShipment = 0

        rows.each(function(index, element) {
            totalPrice += parseInt($(element).html().trim().replace("&nbsp;", " ").split(" ")[1].replace(".", ""))
        })

        discounts.each(function(index, element) {
            totalDiscount+= parseInt($(element).val())
        })

        shipments.each(function(index, element) {
            if ($(element).val() != null && $(element).val() != "null") {
                totalShipment += parseInt($(element).val().split("Rp")[1].substr(1).replace(/\./g, ""))
            }
        })

        $("#subtotal").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(totalPrice).replace("IDR", "Rp").replace(/,/g, "."))

        $("#discount").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(totalDiscount).replace("IDR", "Rp").replace(/,/g, "."))

        $("#shipment").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(totalShipment).replace("IDR", "Rp").replace(/,/g, "."))

        let total = totalPrice + servicePrice + totalShipment - totalDiscount

        $("#total").html(new Intl.NumberFormat(undefined, { 
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }).format(total).replace("IDR", "Rp").replace(/,/g, "."))
    }

    function removeFromCart(cart_row) {
        let data = {
            user_id: <?= json_decode($_SESSION["user"])->id ?>,
            product_id: $("#id-" + cart_row).val()
        }

        $.ajax({
            data: data,
            url: "{{ url('cart/remove-cart-item') }}",
            method: 'POST',
            success: function(result) {

                let rows = $("#tableCart tr")
                let itemCount = 0
                let row_id = 0, seller_id = result.message

                rows.each(function(index, element) {
                    // if this seller id == seller id of deleted item
                    if (seller_id == $(element).attr("id").split('-')[2]) {
                        itemCount = 0
                    }

                    // If element has name attribute
                    if ($(element).attr("name")) {
                        // If this seller id == seller id of deleted item
                        if (seller_id == $(element).attr("name").split('-')[1]) {
                            itemCount++
                        }
                    }

                    // get current row id
                    row_id = $(element).attr("id").split('-')[2]

                    // remove row of deleted item
                    if (row_id == cart_row) {
                        $(element).remove()
                    }
                })

                rows = $("#tableCart tr")

                // If last item of seller is deleted, remove seller name
                if (itemCount == 1) {
                    rows.each(function(index, element) {
                        if ($(element).attr("id").split('-')[2] == result.message) {
                            $(element).remove()
                        }
                    })
                }

                updateCartCount()
                updateTotalPrice()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function checkout() {
        let total = parseInt($("#total").text().substr(3).replace(/\./g, ""))
        let coupon = parseInt($("#discount").text().substr(3).replace(/\./g, ""))
        let shipping = parseInt($("#shipment").text().substr(3).replace(/\./g, ""))

        console.log("total : ", total)
        console.log("coupon : ", coupon)
        console.log("shipping : ", shipping)

        $.ajax({
            data: {
                id_address: addressID,
                total: total,
                coupon: coupon,
                shipping: shipping
            },
            url: "{{ url('checkout') }}",
            method: 'POST',
            success: function(result) {
                console.log(result)
                let transID = result.trans_id

                // Trigger snap popup.
                window.snap.pay(result.snap_token, {
                    onSuccess: function(result){
                        $.ajax({
                            data: {
                                trans_id: transID
                            },
                            url: "{{ url('checkout/payment-success') }}",
                            method: 'POST',
                            success: function(result) {
                                
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(thrownError)
                            }
                        })
                        window.location.href = "/"
                    },
                    onPending: function(result){
                        window.location.href = "/"
                    },
                    onError: function(result){
                        $.ajax({
                            data: {
                                id_trans: transID
                            },
                            url: "{{ url('checkout/payment-failed') }}",
                            method: 'POST',
                            success: function(result) {
                                
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(thrownError)
                            }
                        })
                    },
                    onClose: function(){
                        $.ajax({
                            data: {
                                id_trans: transID
                            },
                            url: "{{ url('checkout/payment-failed') }}",
                            method: 'POST',
                            success: function(result) {
                                
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                toastr.error(thrownError)
                            }
                        })
                    }
                })
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function resetModal() {
        $("#shipReceiver").val("")
        $("#shipPhone").val("")
        $("#shipName").val("")
        $("#shipAddress").val("")
        $("#shipPostal").val("")

        cityId = ""
        cityName = ""

        // Reset Province Select Option
        $("#province").siblings(".nice-select").children("span").html("Pilih Provinsi")
        $.each( $("#province").siblings(".nice-select").children("ul").children("li"), function( key, value ) {
            if (key == 0) {
                $(this).addClass("selected focus", true)
            } else {
                $(this).removeClass("selected focus")
            }
        })

        // Reset City Select Option
        let html = `<li data-value="Pilih Provinsi terlebih dahulu" class="option selected disabled focus">Pilih Provinsi terlebih dahulu</li>`

        $("#city").siblings(".nice-select").children("span").html("Pilih Provinsi terlebih dahulu")
        $("#city").siblings(".nice-select").children("ul").html(html)

        $("#btnItemModal").html("Simpan")
        $("#btnItemModal").attr("onclick", "addShipmentInfo()")

        $(".print-error-msg").css('display','none')
    }
</script>

@endsection