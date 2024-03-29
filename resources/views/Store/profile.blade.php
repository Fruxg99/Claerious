@extends('Template.store')
@section('title', 'Claerious - Products')

@section('content')

<?php
    if (strlen(session_id()) < 1) {
        session_start();
    }
?>

<section class="py-4">
    <div class="container">
        <div class="profile">
            <div class="profile-detail">

                <?php if (isset($data["user"]->profile_picture) && $data["user"]->profile_picture != "") { ?>

                    <img src="<?= $data["user"]->profile_picture ?>">

                <?php } else { ?>

                    <img src="https://e7.pngegg.com/pngimages/178/595/png-clipart-user-profile-computer-icons-login-user-avatars-monochrome-black.png">

                <?php } ?>

                <div class="profile-name">
                    <p><?= $data["user"]->name ?></p>
                </div>
            </div>
            <div class="profile-content">
                <div class="profile-content-header">
                    <div class="profile-content-header-item" onclick="showTab('Profile')" id="Profile">
                        Profil Akun
                    </div>
                    <div class="profile-content-header-item" onclick="showTab('Address')" id="Address">
                        Daftar Alamat Pengiriman
                    </div>
                    <div class="profile-content-header-item" onclick="showTab('Transaction')" id="Transaction">
                        Riwayat Transaksi
                    </div>
                    <div class="profile-content-header-item" onclick="showTab('Group')" id="Group">
                        Grup
                    </div>
                </div>
                <div class="profile-content-detail" id="contentProfile"></div>
                <div class="profile-content-detail" id="contentAddress"></div>
                <div class="profile-content-detail" id="contentTransaction"></div>
                <div class="profile-content-detail" id="contentGroup"></div>
            </div>
        </div>
    </div>
</section>

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

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Buat Kata Sandi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Kata Sandi<small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-user" id="userPass" name="userPass">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('userPass')" id="toggle_userPass"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Ulangi Kata Sandi<small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-user" id="userCPass" name="userCPass">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('userCPass')" id="toggle_userCPass"><i class="fa-solid fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModalPassword()">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="addUserPassword()" id="btnItemModal">Simpan</button>
                        </div>
                    </div>
                </div>
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
                <div style="text-align:center; font-size: 16pt; font-weight: 700;" class="mb-2 text-danger">Hapus Alamat Pengiriman</div>
                <div style="text-align:center;" class="mb-4" id="deleteProductName"></div>
                <div style="text-align:center;" class="mb-4">
                    Yakin ingin hapus alamat pengiriman ini?<br>Alamat pengiriman yang dihapus tidak dapat dikembalikan
                </div>
                <div style="text-align: center;">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batas</button>
                    <button class="btn btn-danger" type="button" id="btnDeleteProduct" data-dismiss="modal">Hapus</button>
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

<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h5 class="m-0 font-weight-bold text-primary">Beri Ulasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Rating (1-5)<small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-user" id="reviewRating">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Ulasan<small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-user" id="reviewContent">
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetModalReview()">Batal</button>
                            <button type="button" class="btn btn-primary" onclick="addReview()" id="btnReviewModal">Ulas</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox" data-client-key="SB-Mid-client-IRE0d_nhs4Jp2k9o"></script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= config('midtrans.client_key') ?>"></script>

<script>
    let cityId = "", cityName = ""
    let profile, transaction, latestTransaction = "-", filterMonth = new Date().getMonth() + 1, filterYear = new Date().getFullYear();

    $(document).ready(function() {

        $("#Profile").trigger("click")

    })

    function showProfile() {
        let html = `
            <div class="profile-biodata">
                <h3 class="profile-biodata-title">Profil Akun</h3>
                <div class="profile-biodata-item">
                    <span>Nama</span><span><?= $data["user"]->name ?></span>
                </div>
                <div class="profile-biodata-item">
                    <span>Jenis Kelamin</span><span><?= $data["user"]->gender == 0 ? "Laki-laki" : ($data["user"]->gender == 1 ? "Perempuan" : "-") ?></span>
                </div>
                <div class="profile-biodata-item">
                    <span>Saldo</span><span>Rp <?= number_format($data["user"]->saldo, 0, ",", ".") ?></span>
                </div>
                <h3 class="profile-biodata-title" style="margin-top: 24px;">Kontak</h3>
                <div class="profile-biodata-item">
                    <span>Email</span><span><?= $data["user"]->email ?></span>
                </div>
                <div class="profile-biodata-item">
                    <span>Nomor HP</span><span><?= $data["user"]->phone ? $data["user"]->phone : "-" ?></span>
                </div>
                <h3 class="profile-biodata-title" style="margin-top: 24px;">Toko</h3>

                <?php if ($data["store"] != "") { ?>

                    <div class="profile-biodata-item">
                        <span>Toko</span><span><?= $data["store"]->name ?></span>
                    </div>
                    <div class="profile-biodata-item">
                        <a style="cursor: pointer;" onclick="checkStore()">Cek halaman toko</a>
                    </div>

                <?php } else { ?>
                    
                    <div class="profile-biodata-item">
                        <span>Toko</span><span>-</span>
                    </div>
                    <div class="profile-biodata-item">
                        <a href="{{ url('/register-store') }}">Buka toko sekarang</a>
                    </div>

                <?php } ?>
            </div>
        `

        $("#contentProfile").html(html)
    }

    function showAddress() {
        $.ajax({
            url: "{{ url('address/select') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

                let html = `
                    <div style="margin-top: 12px;">` +
                        `<button class="btn btn-primary rounded" style="background-color: #4179E8; border: none; outline: none; margin: 0 12px 0 auto; display: block;" data-toggle="modal" data-target="#addressModal">` +
                            `<i class="fas fa-plus" style="margin-right: 7px;"></i> Tambah Alamat` +
                        `</button>` +
                    `</div>
                `;

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
                                    <button class='btn btn-danger rounded btn-alamat-hapus' onclick='removeShipmentInfo("${result[i].id_address}")'><i class='fas fa-trash-alt'></i></button>
                                </div>
                            </div><hr>`
                    }
                } else {
                    html += "<h4 style='opacity: 0.75; margin: 0 32px; text-transform: none;'>Anda belum punya alamat pengiriman</h4>"
                }

                $("#contentAddress").html(html)
                $("hr").last().remove()
            }
        })        
    }

    function showTransaction() {
        let html = `
            <div class="profile-biodata">
                <div class="profile-transaction" id="filterHead">
                    <div class="row" style="width: 100%; margin: 0;">
                        <div class="col-lg-8 px-0">
                            <h3 class="profile-biodata-title">Daftar Transaksi</h3>
                        </div>
                        <div class="col-lg-4 px-0">
                            <div class="input-group date" data-provide="datepicker" id="dateFilter">
                                <input type="text" class="form-control" name="dateFilter" id="date" onchange="loadTransaction()">
                                <span class="input-group-addon" style="width: unset;">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="transaction-history" id="history"></div>
            </div>
        `

        $("#contentTransaction").html(html)

        $("#dateFilter").datepicker({
            format: "MM yyyy",
            startView: "months", 
            minViewMode: "months",
            orientation: "bottom"
        });

        $("#dateFilter").datepicker("setDate", new Date());

        $("#dateFilter").datepicker().on("changeMonth", function(e) {
            filterMonth = new Date(e.date).getMonth() + 1
            filterYear = new Date(e.date).getFullYear()
        })

        $("#date").trigger("change")
    }

    function loadTransaction() {
        $.ajax({
            url: "profile/transaction/get",
            method: "POST",
            data: {
                month: filterMonth,
                year: filterYear
            },
            success: function(result) {
                result = JSON.parse(result)
                historyTrans = result.transaction

                let html = `
                    <table class="table table-hover" style="margin-top: 12px; text-align: center;">
                        <thead style="">
                            <tr>
                                <th>Tanggal</th>
                                <th>Penerima</th>
                                <th>Alamat</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                `
                for (let i = 0 ; i < historyTrans.length ; i++) {
                    html += `
                            <tr style="cursor: pointer;">
                                <td onclick="showDetailTransaction('${historyTrans[i].id_trans}')">${dateFormat(historyTrans[i].created_at)}</td>
                                <td onclick="showDetailTransaction('${historyTrans[i].id_trans}')">${historyTrans[i].receiver_name}</td>
                                <td onclick="showDetailTransaction('${historyTrans[i].id_trans}')">${historyTrans[i].address}</td>
                                <td onclick="showDetailTransaction('${historyTrans[i].id_trans}')">${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(historyTrans[i].total)}</td>`
                    
                    if (historyTrans[i].status == 0) {
                        html += `<td>Dibatalkan</td>`
                    } else if (historyTrans[i].status == 1) {
                        html += `<td>
                                    <button class="btn btn-secondary" onclick="payTransaction('${historyTrans[i].id_trans}')">Bayar</button>
                                </td>`
                    } else if (historyTrans[i].status == 2) {
                        html += `<td>Menunggu Diproses</td>`
                    } else if (historyTrans[i].status == 3) {
                        html += `<td>Sedang Diproses</td>`
                    } else if (historyTrans[i].status == 4) {
                        html += `<td>Dalam Pengiriman</td>`
                    } else if (historyTrans[i].status == 5) {
                        html += `<td>
                                    <button class="btn btn-secondary" onclick="loadReview('${historyTrans[i].id_trans}')">Beri Ulasan</button>
                                </td>`
                    } else if (historyTrans[i].status == 6) {
                        html += `<td>Terulas</td>`
                    }
                    
                    html += `</tr>`
                    
                }
                html += `
                        </tbody>
                    </table>
                `

                if (historyTrans.length == 0) {
                    html = `<h3 style="text-align: center; margin: 12px 0 0 0;">Tidak ada transaksi bulan ini</h3>`
                }

                $("#history").html(html)
            }
        })
    }

    function loadReview(id_trans) {
        $("#btnReviewModal").attr("onclick", "addReview('" + id_trans + "')")
        $("#reviewModal").modal('show')
        $("#transactionModal").modal('hide')
    }

    function addReview(id_trans) {
        $.ajax({
            data: {
                id_trans: id_trans,
                rating: $("#reviewRating").val(),
                review: $("#reviewContent").val()
            },
            url: "{{ url('product/review') }}",
            method: 'POST',
            success: function(result) {
                toastr.success("Produk berhasil diulas")
            },
            error: function (xhr, ajaxOptions, thrownError) {
                toastr.error(thrownError)
            }
        })
    }

    function payTransaction(id_trans) {
        $.ajax({
            data: {
                id_trans: id_trans
            },
            url: "{{ url('profile/transaction/selectById') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)

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
                    },
                    onPending: function(result){
                        
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

    function showDetailTransaction(id_trans) {
        $.ajax({
            url: "profile/transaction/getDetails",
            method: "POST",
            data: {
                id_trans: id_trans
            },
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
            }
        })
    }

    function dateFormat(date) {
        const Months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        const shortMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agst', 'Sept', 'Okt', 'Nov', 'Des']
        
        var dt = new Date(date);
        var dtstring = (dt.getDate()).toString().padStart(2, '0')
            + '-' + shortMonths[dt.getMonth()]
            + '-' + dt.getFullYear()
            + ' ' + (dt.getHours()).toString().padStart(2, '0')
            + ':' + (dt.getMinutes()).toString().padStart(2, '0')
            + ':' + (dt.getSeconds()).toString().padStart(2, '0');

        return dtstring
    }

    function showGroup() {
        $.ajax({
            url: "{{ url('group/getByUser') }}",
            method: 'POST',
            success: function(result) {
                // result = JSON.parse(result)

                console.log(result)

                let html = `<table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Grup</th>
                                        <th scope="col">Gambar Produk</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Produk Terbeli</th>
                                        <th scope="col">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                for(let i = 0 ; i < result.length ; i++) {
                    html += `<tr>
                                <td>Grup ${result[i].user_name}</td>
                                <td><img src="${result[i].thumbnail}" style="max-width: 100px; max-height: 100px;"></td>
                                <td>${result[i].product_name}</td>
                                <td>${result[i].current_accumulation} / ${result[i].target_accumulation}</td>
                                <td>`
                    
                                if (result[i].id_leader == "<?= json_decode($_SESSION["user"])->id_user ?>") {
                                    if (result[i].current_accumulation >= result[i].target_accumulation) {
                                        html += `<button class="btn btn-secondary" onclick="finishGroup('${result[i].id_group}')">Teruskan Pesanan</button>`
                                    } else {
                                        html += `<button class="btn btn-secondary" onclick="disbandGroup('${result[i].id_group}')">Bubarkan Grup</button>`
                                    }
                                } else {
                                    html += `<button class="btn btn-secondary" onclick="leaveGroup('${result[i].id_group}')">Keluar Grup</button>`
                                }
                                        
                        html += `</td>
                            </tr>`
                }

                html += `</tbody>
                        </table>`

                $("#contentGroup").html(html)
            }
        })
    }

    function disbandGroup(id_group) {
        $.ajax({
            data: {
                id_group: id_group
            },
            url: "{{ url('/group/disband') }}",
            method: 'POST',
            success: function(result) {
                showGroup()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                
            }
        })
    }
    
    function finishGroup(id_group) {
        $.ajax({
            data: {
                id_group: id_group
            },
            url: "{{ url('/group/finish') }}",
            method: 'POST',
            success: function(result) {
                showGroup()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                
            }
        })
    }

    function leaveGroup(id_group) {
        $.ajax({
            data: {
                id_group: id_group
            },
            url: "{{ url('/group/leave') }}",
            method: 'POST',
            success: function(result) {
                showGroup()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                
            }
        })
    }

    function showTab(id) {
        hideAll()
        removeHeaderFocus()
        document.getElementById(id).classList.toggle("profile-header-focus")
        document.getElementById("content" + id).style.display = "block"

        if (id == "Profile") {
            showProfile()
        } else if (id == "Address") {
            showAddress()
        } else if (id == "Transaction") {
            showTransaction()
        } else if (id =="Group") {
            showGroup()
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

    function removeShipmentInfo(addressId) {
        let data = {
            id_address: addressId
        }

        $.ajax({
            data: data,
            url: "{{ url('address/delete') }}",
            method: 'POST',
            success: function(result) {
                showAddress()
            }
        })
    }

    function checkStore() {
        $.ajax({
            url: "{{ url('/get-user') }}",
            method: 'POST',
            success: function(result) {
                result = JSON.parse(result)
                user = result.user

                if (user.password == "") {
                    $("#passwordModal").modal("show")
                } else {
                    window.location.href = 'http://backoffice.claerious.store'
                }
            }
        })
    }

    function addUserPassword() {
        let pass = $("#userPass").val()
        let cpass = $("#userCPass").val()

        if (pass == cpass) {
            $.ajax({
                data: {
                    password: pass
                },
                url: "{{ url('/add-password') }}",
                method: 'POST',
                success: function(result) {
                    window.location.href = "http://backoffice.claerious.store/"
                }
            })
        } else {
            toastr.error("Kata sandi tidak sama")
        }
    }

    function hideAll() {
        let elements = document.getElementsByClassName("profile-content-detail")
        for(let i = 0 ; i < elements.length ; i++) {
            elements[i].style.display = "none"
        }
    }

    function removeHeaderFocus() {
        let headers = document.getElementsByClassName("profile-content-header-item")
        for(let i = 0 ; i < headers.length ; i++) {
            headers[i].classList.remove('profile-header-focus')
        }
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

    function resetModalPassword() {
        $("#userPass").val("")
        $("#userCPass").val("")
    }

    function resetModalReview() {
        $("#reviewRating").val("")
        $("#reviewContent").val("")
        $("#btnReviewModal").attr("onclick", "addReview()")
    }

    function togglePass(element) {
        if ($("#" + element).attr("type") == "password") {
            $("#" + element).attr("type", "text")
            $("#toggle_" + element).html(`<i class="fa-solid fa-eye-slash"></i>`)
        } else {
            $("#" + element).attr("type", "password")
            $("#toggle_" + element).html(`<i class="fa-solid fa-eye"></i>`)
        }
    }

    $("#reviewRating").keyup(function() {
        var value = $(this).val()
        var result = autoNumeric(value)
        rawValue = parseInt(result.split('.').join(''))

        $(this).val(result)
    })
    $("#reviewRating").keydown(function(event) {
        var value = $(this).val()

        if (checkNumericInputWithZero(event.keyCode, value)) {
            return true
        } else {
            return false
        }
    })
</script>

@endsection