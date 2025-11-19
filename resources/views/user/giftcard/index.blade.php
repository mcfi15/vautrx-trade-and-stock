@extends('layouts.app')

@section('title', 'Gift Card')

@section('content')

<div class="container">
    <div class="row mt-3">
        <div class="col-12 col-md-6 order-2 order-md-1">
            <div class="page-title-content d-flex align-items-start mt-2">
                <span>Welcome, <span> {{ Auth::user()->name }}!</span> <br /></span>
            </div>
        </div>

        <div class="col-12 col-md-6 order-1 order-md-2 float-right">
            <ul class="text-right breadcrumbs list-unstyle">
                <li>
                    <a class="btn btn-primary btn-sm" href="/Finance/index">Wallet</a>
                </li>
                <li class="btn btn-primary btn-sm active">Giftcard Home</li>
                <li>
                    <a class="btn btn-primary btn-sm" href="{{ route('giftcard.create') }}">Create Gift Card</a>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-12 m-t-30">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gift Card</h4>
                </div>
                <div class="card-body text-center gift-card-buttons">
                    <a href="{{ route('giftcard.create') }}" class="btn btn-secondary col-md-3 col-sm-6 m-t-10">
                        <i class="fa fa-download f-s-24"></i> <span class="clearfix"></span> Send card
                    </a>
                    <a href="#!" data-toggle="modal" data-target="#getGiftCardModal" role="button" class="btn btn-secondary col-md-3 col-sm-6 m-t-10">
                        <i class="fa fa-upload f-s-24 fa-rotate-180"></i>
                        <span class="clearfix"></span> Check or Redeem
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card gift-card-list">
                <div class="col-12">
                    <ul class="nav nav-pills nav-fill" role="tablist">
                        <li class="nav-item">
                            <a aria-selected="true" class="nav-link active" data-toggle="pill" href="#mycards" role="tab">My cards</a>
                        </li>
                        <li class="nav-item">
                            <a aria-selected="false" class="nav-link" data-toggle="pill" href="#spentcards" role="tab">Spent</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade p-t-15 show active" role="tabpanel" id="mycards">
                        @if($myCards->count() > 0)
                        <div class="row">
                            @foreach($myCards as $card)
                            <div class="col-sm-6 col-md-4 col-lg-3 m-b-20">
                                <div class="gc-box">
                                    <div class="card">
                                        <div class="card-img-actions">
                                            <img class="card-img-top img-fluid" src="{{ asset('Public/assets/images/gift-card-bg.png') }}" alt="Gift Card">
                                            <div style="background-color:#28BAA7;" class="value-box">
                                                {{ $card->amount }} {{ $card->cryptocurrency->symbol }}
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <h6 class="font-weight-semibold mb-0">{{ $card->title }}</h6>
                                            <span class="text-muted">{{ $card->public_code }}</span>
                                            <div class="m-t-10">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="viewcode({{ $card->id }})">
                                                    View Code
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-data">
                            <i style="color:#1e90ff;" class="fa fa-gift"></i>
                            <p>There are no cards. <br/> Create one now!</p>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane fade p-t-15" role="tabpanel" id="spentcards">
                        @if($spentCards->count() > 0)
                        <div class="row">
                            @foreach($spentCards as $card)
                            <div class="col-sm-6 col-md-4 col-lg-3 m-b-20">
                                <div class="gc-box">
                                    <div class="card">
                                        <div class="card-img-actions">
                                            <img class="card-img-top img-fluid" src="/Public/assets/images/gift-card-bg.png" alt="Gift Card">
                                            <div style="background-color:#28BAA7;" class="value-box">
                                                {{ $card->amount }} {{ $card->cryptocurrency->symbol }}
                                            </div>
                                        </div>
                                        <div class="card-body text-center">
                                            <h6 class="font-weight-semibold mb-0">{{ $card->title }}</h6>
                                            <span class="text-muted">{{ $card->public_code }}</span>
                                            <div class="m-t-10">
                                                <button type="button" class="btn btn-info btn-sm" onclick="viewConsumed({{ $card->id }})">
                                                    View Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-data">
                            <i style="color:#1e90ff;" class="fa fa-gift"></i>
                            <p>There are no cards. <br/> Create one now!</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- get gift card modal start -->
<div class="modal fade" id="getGiftCardModal" tabindex="-1" role="dialog" aria-labelledby="Get card" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">
                    <div class="coin-title d-flex align-items-center">
                        <div class="title">Get card</div>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills nav-fill m-t-40 row" role="tablist">
                    <li class="nav-item col-sm-12 col-md-4">
                        <a aria-selected="true" class="nav-link" data-toggle="pill" href="#check-value" role="tab">
                            <i class="ion ion-md-search"></i> Check Value
                            <span class="description">Enter card number to check card's value and status</span>
                        </a>
                    </li>
                    <li class="nav-item col-sm-12 col-md-4">
                        <a aria-selected="false" class="nav-link active" data-toggle="pill" href="#add-card" role="tab">
                            <i class="ion ion-md-add"></i> Add card
                            <span class="description">Enter the gift card code and link the gift card to your account</span>
                        </a>
                    </li>
                    <li class="nav-item col-sm-12 col-md-4">
                        <a aria-selected="false" class="nav-link" data-toggle="pill" href="#get-crypto" role="tab">
                            <i class="ion ion-md-card"></i> Get Crypto
                            <span class="description">Enter the gift card code to have the crypto credited to your account</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade p-t-15" role="tabpanel" id="check-value">
                        <div class="form-group">
                            <label for="gift-card-no"></label>
                            <div class="input-group">
                                <input class="form-control" placeholder="Please enter the gift card secret code" type="text" id="gift-card-no">
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text btn-2" onclick="checkit();">Check it</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade p-t-15 active show" role="tabpanel" id="add-card">
                        <div class="form-group">
                            <label for="add-gift-card-no"></label>
                            <div class="input-group">
                                <input class="form-control" placeholder="Please enter the gift card secret code" type="text" id="add-gift-card-no">
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text btn-2" onclick="redeemit1();">Redeem</button>
                                </div>
                            </div>
                        </div>
                        <p>We are not responsible for the illegal behavior or fraud of any third party associated with any Gift card; and assumes no liability to you. I accept and accept the Exchange Gift Card Terms</p>
                        <p>To view the full Terms and Conditions for the gift card, please visit the Help Center</p>
                    </div>
                    <div class="tab-pane fade p-t-15" role="tabpanel" id="get-crypto">
                        <div class="form-group">
                            <label for="gc-gift-card-no"></label>
                            <div class="input-group">
                                <input class="form-control" placeholder="Please enter the gift card secret code" type="text" id="gc-gift-card-no">
                                <div class="input-group-append">
                                    <button type="button" class="input-group-text btn-2" onclick="redeemit2();">Redeem now</button>
                                </div>
                            </div>
                        </div>
                        <p>We are not responsible for the illegal behavior or fraud of any third party associated with any Gift card; and assumes no liability to you. I accept and accept the Exchange Gift Card Terms</p>
                        <p>To view the full Terms and Conditions for the gift card, please visit the Help Center</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- get gift card modal end -->

<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js"></script>
<script>
function checkit() {
    var secret = $("#gift-card-no").val();
    if(secret == "" || secret == null){
        alert('Please enter card information');
        return false;
    }
    
    $.post("{{ route('giftcard.checkValue') }}", {secret_code: secret}, function(data) {
        if(data.status == 1) {
            alert(data.info);
        } else {
            alert(data.info);
        }
    }, 'json');
}

function redeemit1() {
    var secret = $("#add-gift-card-no").val();
    if(secret == "" || secret == null){
        alert('Please enter card information');
        return false;
    }
    
    $.post("{{ route('giftcard.redeem') }}", {secret_code: secret}, function(data) {
        if(data.status == 1) {
            alert(data.info);
            window.location.href = "{{ route('giftcard.index') }}";
        } else {
            alert(data.info);
        }
    }, 'json');
}

function redeemit2() {
    var secret = $("#gc-gift-card-no").val();
    if(secret == "" || secret == null){
        alert('Please enter card information');
        return false;
    }
    
    $.post("{{ route('giftcard.redeem') }}", {secret_code: secret}, function(data) {
        if(data.status == 1) {
            alert(data.info);
            window.location.href = "{{ route('giftcard.index') }}";
        } else {
            alert(data.info);
        }
    }, 'json');
}

function viewcode(id) {
    $.get("{{ route('giftcard.viewCode', '') }}/" + id, function(data) {
        var html = '<p style="font-weight: bold;text-align: center;font-size: 18px;">'+data.title+'</p>' +
                   '<div class="card"><div class="card-body"><ul>' +
                   '<li class="d-flex justify-content-between"><label>Value</label> <span>'+data.coin +' '+data.value+'</span></li>' +
                   '<li class="d-flex justify-content-between"><label>Card Number</label> <span><small>'+data.public+'</small></span></li>' +
                   '<li class="d-flex justify-content-between"><label>Code</label> <span><small>'+data.secret+'</small></span></li>' +
                   '</ul></div></div>';
        
        if(confirm(html + '\n\nClick OK to copy the secret code to clipboard.')) {
            navigator.clipboard.writeText(data.secret).then(function() {
                alert("Copied: " + data.secret);
            }, function() {
                alert("Failed to copy to clipboard");
            });
        }
    });
}

function viewConsumed(id) {
    $.get("{{ route('giftcard.viewConsumed', '') }}/" + id, function(data) {
        var html = '<p style="font-weight: bold;text-align: center;font-size: 18px;">'+data.title+'</p>' +
                   '<div class="card"><div class="card-body"><ul>' +
                   '<li class="d-flex justify-content-between"><label>Value</label> <span>'+data.coin +' '+data.value+'</span></li>' +
                   '<li class="d-flex justify-content-between"><label>Card Number</label> <span><small>'+data.public+'</small></span></li>' +
                   '<li class="d-flex justify-content-between"><label>Used</label> <span><small>'+data.used+'</small></span></li>' +
                   '</ul></div></div>';
        
        alert(html);
    });
}

// Initialize modals
$(document).ready(function() {
    $('#getGiftCardModal').on('show.bs.modal', function() {
        $('.nav-pills .nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        $('.nav-pills .nav-link[href="#add-card"]').addClass('active');
        $('#add-card').addClass('show active');
    });
});
</script>

<style>
.gift-card-list {
    min-height: 275px;
}
.value-box {
    position: absolute;
    top: 29px;
    left: 21px;
    font-size: 15px;
    color: #fff !important;
    background: #ffbe40;
    box-shadow: 0 5px 3px rgb(0 0 0 / 30%);
    padding: 2px;
    border-radius: 3px;
}
.gc-box {
    position: relative;
}
.card-img-actions {
    min-height: 166px;
}
.no-data {
    text-align: center;
    padding: 40px 0;
    color: #6c757d;
}
.no-data i {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
}
.description {
    display: block;
    font-size: 12px;
    color: #6c757d;
    margin-top: 5px;
}
</style>

@endsection