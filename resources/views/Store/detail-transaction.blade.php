@extends('Template.store')
@section('title', 'Claerious - Products')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

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

<script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox" data-client-key="SB-Mid-client-IRE0d_nhs4Jp2k9o"></script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= config('midtrans.client_key') ?>"></script>

<script>

    $( document ).ready(function() {

        <?php if (isset($_SESSION["user"])) { ?>

            updateCartCount()
            updateWishlistCount()
            updateFavoriteCount()
            updateTotalPrice()
            loadShipment()

        <?php } ?>

    })

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
</script>

@endsection