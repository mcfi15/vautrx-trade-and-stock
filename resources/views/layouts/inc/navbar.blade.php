<header class="dark-bb">

        <head>
            <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/icon-park.css') }}" />
            <style>
                .banner-section {
                    height: 514px !important;
                }

                .nav__item a {
                    text-decoration: none;
                    cursor: pointer;
                    color: white !important;
                }

                .m-logo {
                    display: none;
                }

                .nav-mobile {
                    display: none !important;
                    z-index: 10;
                    position: absolute;
                    top: 48px;
                    overflow-y: auto;
                    width: 248px;
                    height: calc(100% - 48px);
                    background-color: #000;
                    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
                        0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
                    /* display: none !important; */
                }

                .nav__overview {
                    border-bottom: 1px solid #ccc;
                    background-color: #fafafa;
                    font-size: 14px;
                    color: #0288d1;
                    height: 54px;
                    padding: 20px 25px;
                    margin-bottom: 12px;
                }

                .nav__overview i {
                    display: flex;
                    justify-content: center;
                    width: 15px;
                    margin-right: 20px;
                }

                .nav__label {
                    font-weight: 300;
                    display: block;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    font-size: 12px;
                    color: #b5b5b5;
                    padding: 15px 24px;
                }

                .nav__item {
                    font-weight: 400;
                    display: block;
                    font-size: 14px;
                    color: #757575;
                    padding: 11px 25px;
                    display: flex;
                    align-items: center;
                }

                .nav__item.overview {
                    color: #fff;
                    border-bottom: 1px solid #e4e4e4;
                    background-color: #e0a800;
                    height: 54px;
                    margin-bottom: 12px;
                }

                .nav__item:hover {
                    background-color: #e0a800;
                    cursor: pointer;
                }

                .nav__item i {
                    display: flex;
                    justify-content: center;
                    width: 15px;
                    margin-right: 20px;
                }

                .main {
                    position: absolute;
                    left: 248px;
                    width: calc(100% - 248px);
                    height: calc(100% - 48px);
                }

                .main .title-bar {
                    background-color: #039be5;
                    height: 54px;
                    width: 100%;
                    display: flex;
                    align-items: center;
                }

                .main .title-bar h2 {
                    font-size: 20px;
                    font-weight: 300;
                    margin-left: 24px;
                }

                .main .content-area {
                    position: relative;
                    height: calc(100% - 54px);
                    color: #999;
                    display: flex;
                    justify-content: center;
                }

                .main .content-area p {
                    align-self: center;
                }

                .nav__toggle {
                    display: none !important;
                }

                @media only screen and (max-width: 991px) {
                    .nav__toggle {
                        display: block !important;
                    }

                    .m-logo {
                        height: 48px;
                        display: flex;
                        align-items: center;
                        padding: 0px 10px;
                        border-bottom: 1px solid #e4e4e4;
                    }

                    .m-logo .logo__icon {
                        display: inline-block;
                        height: 28px;
                        margin: 10px;
                    }

                    .m-logo .logo__text {
                        display: inline-block;
                        height: 18px;
                        opacity: 0.6;
                    }

                    .mobile-mask.show {
                        display: block;
                        z-index: 25;
                        background: #000;
                        opacity: 0.5;
                        top: 0;
                        position: absolute;
                        height: 100%;
                        width: 100%;
                        cursor: pointer;
                    }

                    .nav-mobile {
                        display: none;
                    }

                    .nav-mobile.show {
                        display: block !important;
                        z-index: 30;
                        position: fixed;
                        top: 0px;
                        overflow-y: auto;
                        width: 248px;
                        height: 100%;
                        background-color: #000;
                        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
                            0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
                        right: 0;
                    }

                    .nav-mobile.show__overview {
                        border-bottom: 1px solid #e4e4e4;
                        background-color: #fafafa;
                        font-size: 14px;
                        color: #0288d1;
                        height: 52px;
                        padding: 20px 25px;
                        margin-bottom: 12px;
                    }

                    .nav-mobile.show__overview i {
                        display: flex;
                        justify-content: center;
                        width: 15px;
                        margin-right: 20px;
                    }

                    .nav-mobile.show__label {
                        font-weight: 300;
                        display: block;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                        font-size: 12px;
                        color: #b5b5b5;
                        padding: 15px 24px;
                    }

                    .nav-mobile.show__item {
                        font-weight: 400;
                        display: block;
                        font-size: 14px;
                        color: #757575;
                        padding: 11px 25px;
                        display: flex;
                        align-items: center;
                    }

                    .nav-mobile.show__item.overview {
                        color: #0288d1;
                        border-bottom: 1px solid #ccc;
                        background-color: #fafafa;
                        height: 54px;
                        margin-bottom: 12px;
                    }

                    .nav-mobile.show__item:hover {
                        background-color: red;
                        cursor: pointer;
                    }

                    .nav-mobile.show__item i {
                        display: flex;
                        justify-content: center;
                        width: 15px;
                        margin-right: 20px;
                    }

                    .main {
                        position: absolute;
                        left: 0px;
                        width: 100%;
                    }

                    .main .title-bar {
                        background-color: #039be5;
                        height: 54px;
                        width: 100%;
                        display: flex;
                        align-items: center;
                    }

                    .main .title-bar h2 {
                        font-size: 20px;
                        font-weight: 300;
                        margin-left: 24px;
                    }
                }

                .nav-mobile .wallet_mob_dropdown.dropdown-menu.show,
                .nav-mobile .dropdown-menu.show {
                    width: 100%;
                    top: 40px !important;
                }

                .nav-mobile .dropdown-menu.show .dropdown-body {
                    border: 1px solid;
                    border-radius: 9px;
                }

                .WalletNav,
                .nav__item {
                    position: relative;
                }

                .mob_dark_mode {
                    position: absolute;
                    right: 0;
                    top: 0;
                    z-index: 1;
                    cursor: pointer;
                }

                span.mob_dark_mode a i {
                    font-size: 20px;
                }

                .pl0 {
                    padding-left: 0;
                }

                .logout_a {
                    padding: 6px 0;
                }
            </style>
        </head>

        <nav class="navbar navbar-expand-lg">
            {{-- <a class="navbar-brand" href="{{ url('/') }}"><img alt="Dectrx" src="Upload/public/65d5f5101e8de.png" /></a> --}}
            
            <a class="navbar-brand" href="{{ url('/') }}"><img alt="{{ \App\Models\Setting::get('site_name', 'Website Name') }}" src="{{ asset(\App\Models\Setting::get('site_logo')) }}" /></a>
            
            <!-- navbar-toggler  -->
            <!-- aria-controls="headerNavMenu"
                aria-expanded="false"
                aria-label="Toggle Menu"
                data-target="#headerNavMenu"
                    data-toggle="collapse"
                    type="button" -->
            <button class="nav__toggle fa fa-bars fa-1x p-5 border bg-dark text-warning f-s-24 rounded">
                <!-- <i class="icon ion-md-menu"></i> -->
            </button>
            <div class="collapse navbar-collapse" id="headerNavMenu">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown icon-flex-wrapper ml-3" id="Trade_box">
                        <div class="nav-noicon"></div> <a aria-expanded="false" aria-haspopup="true"
                            class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">Trade </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-body">
                                <a class="dropdown-item" href="{{ url('trading/pro') }}">
                                    <div class="nav-icon">
                                        <i class="icon-size exchange-three"></i>
                                    </div>

                                    <div class="content text-white">Spot Professional <p class="featured">Hot</p> <span
                                            class="subtext">Tools for Pro</span> </div>
                                </a><a class="dropdown-item" href="{{ url('trading/spot') }}">
                                    <div class="nav-icon">
                                        <i class="icon-size send-to-back"></i>
                                    </div>

                                    <div class="content text-white">Spot Classic <span class="subtext">Trade on our
                                            award-winning platform</span> </div>
                                </a><a class="dropdown-item" href="{{ url('easy-convert') }}">
                                    <div class="nav-icon">
                                        <i class="icon-size exchange"></i>
                                    </div>

                                    <div class="content text-white">Easy Convert <p class="featured">New</p> <span
                                            class="subtext">Easy Trade</span> </div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown icon-flex-wrapper ml-3" id="Earn_box">
                        <div class="nav-noicon"></div> <a aria-expanded="false" aria-haspopup="true"
                            class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">Earn <span
                                class="featured">Hot</span></a>
                        <div class="dropdown-menu">
                            <div class="dropdown-body">
                                <a class="dropdown-item" href="Invest.html">
                                    <div class="nav-icon">
                                        <i class="icon-size broadcast-one"></i>
                                    </div>

                                    <div class="content text-white">Staking <span class="subtext">Stake and Get
                                            Rewards</span> </div>
                                </a><a class="dropdown-item" href="Airdrop.html">
                                    <div class="nav-icon">
                                        <i class="icon-size parachute"></i>
                                    </div>

                                    <div class="content text-white">Airdrop <span class="subtext">Earn Tokens</span>
                                    </div>
                                </a><a class="dropdown-item" href="Faucet.html">
                                    <div class="nav-icon">
                                        <i class="icon-size water"></i>
                                    </div>

                                    <div class="content text-white">Faucet <p class="featured">Grab</p> <span
                                            class="subtext">Free Coins and Tokens</span> </div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li id="Market_box" class="nav-item ml-3">
                        <a href="{{ url('markets') }}" class="nav-link icon-flex-wrapper">

                            <div class="nav-noicon"></div> Market
                        </a>
                    </li>
                    <li class="nav-item dropdown icon-flex-wrapper ml-3" id=" _box">
                        <div class="nav-icon">
                            <i class="icon-size system"></i>
                        </div>
                        <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                            data-toggle="dropdown" href="#" role="button"> </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-body">
                                <a class="dropdown-item" href="Pool.html">
                                    <div class="nav-icon">
                                        <i class="icon-size heavy-metal"></i>
                                    </div>

                                    <div class="content text-white">Mining <p class="featured">Mine to Earn</p> <span
                                            class="subtext">Rent mining machines</span> </div>
                                </a><a class="dropdown-item" href="Issue.html">
                                    <div class="nav-icon">
                                        <i class="icon-size flask"></i>
                                    </div>

                                    <div class="content text-white">Lab <p class="featured">Innovate</p> <span
                                            class="subtext">Blockchain Research and Investments</span> </div>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item header-custom-icon ml-3">
                        <a class="nav-link" href="#" id="clickFullscreen">
                            <i class="icon ion-md-expand"></i>
                        </a>
                    </li>

                    <!--li class="nav-item dropdown header-custom-icon ml-3">
        <a
          aria-expanded="false"
          aria-haspopup="true"
          class="nav-link dropdown-toggle"
          data-toggle="dropdown"
          href="#"
          role="button"
        >
          <i class="icon ion-md-download"></i
        ></a>
        <div class="dropdown-menu">
          <div class="dropdown-body">
            <img src="/Upload/public/5a2557a174748.png" class="img-fluid" />
          </div>
          <div
            class="dropdown-footer d-flex align-items-center justify-content-center f-s-12 text-center"
          >
            Scan to Download iOS and Android App
          </div>
        </div>
      </li-->
                    @auth

                    <li class="nav-item header-custom-icon login-menu ml-3">
                        <a class="cd-signup nav-link"
                            onclick="document.getElementById('walletPage').style.display = document.getElementById('walletPage').style.display=='block'?'none':'block';document.getElementById('profilePage').style.display='none';">
                            <i class="icon ion-ios-wallet"></i> wallet
                            <svg version="1.1" id="Caret-Down--Streamline-Carbon" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" viewBox="0 0 16 16"
                                xml:space="preserve" enable-background="new 0 0 32 32" height="16" width="16">
                                <desc>
                                    Caret Down Streamline Icon: https://streamlinehq.com
                                </desc>
                                <title>caret--down</title>
                                <path d="m12 6 -4 5 -4 -5z" fill="white" stroke-width="0.5"></path>
                                <path id="_Transparent_Rectangle_" d="M0 0h16v16H0Z" fill="none" stroke-width="0.5">
                                </path>
                            </svg>
                        </a>
                        <div id="walletPage" class="" style="position: absolute;z-index:99;display:none;">
                            <div class="dropdown-body">
                                <a class="dropdown-item logout_a" href="{{ url('wallet') }}"> <img
                                        src="{{ asset('uploads/icons/Wallet--Streamline-Plump.svg') }}"
                                        style="width:2.5ex; margin-right:2ex;" /> Spot wallet</a>
                                <a class="dropdown-item logout_a" href="{{ url('wallet/deposit') }}"> <img
                                        src="uploads/icons/Credit-Card-2--Streamline-Sharp-Neon.svg.svg"
                                        style="width:2.5ex; margin-right:2ex;" /> Deposit</a>
                                <a class="dropdown-item logout_a" href="{{ url('wallet/withdraw') }}"> <img
                                        src="uploads/icons/Money-Atm--Streamline-Kameleon.svg.svg"
                                        style="width:2.5ex; margin-right:2ex;" /> Withdraw</a>
                            </div>
                        </div>

                    </li>


                    

                    @else

                    <li class="nav-item header-custom-icon login-menu ml-3">
                        <a class="cd-signin nav-link" href="{{ url('login') }}">
                            <i class="icon ion-ios-log-in"></i> Sign In</a>
                    </li>
                    <li class="nav-item header-custom-icon login-menu ml-3">
                        <a class="cd-signup nav-link" href="{{ url('register') }}">
                            <i class="icon ion-ios-person-add"></i> Sign Up</a>
                    </li>

                    @endauth
                    



                    <li class="nav-item dropdown header-custom-icon langMenu ml-3">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <div id="langselection">
                                <img src="{{ asset('Public/assets/images/flags/en.png') }}" class="position-left"
                                    alt="">
                                <span class="cret-after-nav-item"></span>
                            </div>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-body">
                                <span class="dropdown-item" onclick="choose_lang('en')"> English</span>
                                <span class="dropdown-item" onclick="choose_lang('tr')"> Turkish</span>
                                <span class="dropdown-item" onclick="choose_lang('es')"> Spanish</span>
                                <span class="dropdown-item" onclick="choose_lang('nl')"> Dutch</span>
                                <span class="dropdown-item" onclick="choose_lang('il')"> Hebrew</span>
                                <span class="dropdown-item" onclick="choose_lang('de')"> German</span>
                                <span class="dropdown-item" onclick="choose_lang('ko')"> Korean</span>
                                <span class="dropdown-item" onclick="choose_lang('ja')"> Japanese</span>
                                <span class="dropdown-item" onclick="choose_lang('pt')"> Portuguese</span>
                                <span class="dropdown-item" onclick="choose_lang('ru')"> Русски</span>
                                <span class="dropdown-item" onclick="choose_lang('vi')"> Vietnamese</span>
                                <span class="dropdown-item" onclick="choose_lang('zhtw')"> Traditional</span>
                                <span class="dropdown-item" onclick="choose_lang('zhcn')"> Traditional</span>
                            </div>
                        </div>
                    </li>

                    @auth
                    <li class="nav-item dropdown userMenu header-custom-icon ml-3">
                    <a
                        aria-expanded="false"
                        aria-haspopup="true"
                        class="nav-link dropdown-toggle cret-after-nav-item"
                        data-toggle="dropdown"
                        href="#"
                        role="button"
                        >
                        {{ auth()->user()->name }}          

                    </a>
                    <div class="dropdown-menu">
                        <div
                        class="dropdown-header d-flex align-items-center justify-content-between"
                        >
                        <p class="font-weight-medium"><a href="mailto:{{ auth()->user()->email }}" class="__cf_email__" data-cfemail="5526203c2c3934213d153d3c677b3c3b">[email&#160;protected]</a></p>
                        </div>
                        <div class="dropdown-body">
                        <a class="dropdown-item" href="/User/index">
                            <span>User Center</span></a
                        >
                        <a class="dropdown-item" href="/User/authentication">
                            <span>KYC</span></a
                        >
                        <a class="dropdown-item" href="/User/log"
                            ><span>User Actions</span></a
                        >
                        <a class="dropdown-item" href="/Finance/mytj"
                            ><span>Invite a Friend</span></a
                        >
                        <a class="dropdown-item" href="/Transfer/giftcard"
                            ><span>Gift Card</span></a
                        >
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <input type="submit" class="" value="Logout">
                                    
                        </form>
                        </div>
                    </div>
                    </li>
                    @endauth

                    <li class="nav-item header-custom-icon ml-3">
                        <a class="nav-icon changeThemeLight bg-transparent" href="#!">
                            <i class="icon-size moon"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="mobile-mask"></div>
        <div class="nav-mobile pt-2">
            <span class="mob_dark_mode">
                <a class="nav-icon changeThemeLight bg-transparent" href="#!">
                    <i class="icon-size moon"></i>
                </a>
            </span>
            <div class="">
                <div class="nav__item">
                    <i class="fa fa-language" aria-hidden="true"></i>
                    <a class="nav-link dropdown-toggle pl0" aria-expanded="false" aria-haspopup="true"
                        data-toggle="dropdown" href="#" role="button">
                        English
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-body">
                            <a class="dropdown-item" href="#">English</a>
                            <a class="dropdown-item" href="#">Turkish</a>
                        </div>
                    </div>
                </div>

                @auth

                <p class="text-white w-100 text-center mt-2">
                    Welcome, <span class="text-white"> {{ auth()->user()->name }}</span>
                </p>
                <div class="nav__item">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    <a href="/account-setting/">Account settings</a>
                </div>
                <div class="nav__item WalletNav">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle pl0"
                        data-toggle="dropdown" href="#" role="button">Wallet</a>
                    <div class="dropdown-menu wallet_mob_dropdown">
                        <div class="dropdown-body">
                            <a class="dropdown-item" href="{{ url('wallet') }}">
                                <div class="icon">
                                    <i class="fa fa-history"></i>
                                </div>
                                <div class="content">
                                    Spot Wallet
                                </div>
                            </a>

                            <a class="dropdown-item" href="{{ url('wallet/deposit') }}">
                                <div class="icon">
                                    <i class="fa fa-download"></i>
                                </div>
                                <div class="content">
                                    Deposit </div>
                            </a>
                            <a class="dropdown-item" href="{{ url('wallet/withdraw') }}">
                                <div class="icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                                <div class="content">
                                    Withdraw
                                </div>
                            </a>

                        </div>
                    </div>
                </div> 
                <!--div class="nav__item">
                    <a
                    class="btn btn-outline-warning btn-sm text-dark"
                    href="/login/out"
                    >Logout</a
                    >
                </div-->
                <div class="d-flex justify-content-around align-items-center w-100">
                </div>

                @else

                <div class="d-flex justify-content-around align-items-center w-100">
                    <div class="nav__item">
                        <a class="btn btn-outline-warning btn-sm text-dark" href="{{ url('login') }}">Sign In</a>
                    </div>
                    <div class="nav__item">
                        <a class="btn btn-outline-warning btn-sm text-dark" href="{{ url('register') }}">Sign Up</a>
                    </div>
                </div>

                @endauth                
                
            </div>
            <div class="nav__item overview">
                <i class="fa fa-home" aria-hidden="true"></i>
                <a href="{{ url('/') }}">Home</a>
            </div>
            <div class="nav__item">
                <!--label class="nav__label lbl_dropdown">Trade</label-->
                <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown"
                    href="#" role="button">Trade</a>
                <div class="dropdown-menu">
                    <div class="dropdown-body">
                        <a class="dropdown-item" href="{{ url('trading/pro') }}">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Spot Professional </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                              <a href="../Trade/tradepro">
                            Spot Professional</a>
            </div--> <a class="dropdown-item" href="{{ url('trading/spot') }}">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Spot Classic </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Trade">              Spot Classic</a>
            </div--> <a class="dropdown-item" href="{{ url('easy-convert') }}">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Easy Convert </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Easy">              Easy Convert</a>
            </div-->
                    </div>
                </div>
            </div>
            <div class="nav__item">
                <!--label class="nav__label lbl_dropdown">Earn</label-->
                <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown"
                    href="#" role="button">Earn</a>
                <div class="dropdown-menu">
                    <div class="dropdown-body">
                        <a class="dropdown-item" href="Invest.html">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Staking </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Invest">              Staking</a>
            </div--> <a class="dropdown-item" href="Airdrop.html">
                            <div class="icon">
                                <i class="fa fa-parachute" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Airdrop </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-parachute" aria-hidden="true"></i>
                                              <a href="/Airdrop">              Airdrop</a>
            </div--> <a class="dropdown-item" href="Faucet.html">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Faucet </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Faucet">              Faucet</a>
            </div-->
                    </div>
                </div>
            </div>
            <div class="nav__item">
                <div class="nav__item elseifnav">
                    <a href="Content/market.html">Market</a>
                </div>
            </div>
            <div class="nav__item">
                <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle" data-toggle="dropdown"
                    href="#" role="button">More</a>
                <div class="dropdown-menu">
                    <div class="dropdown-body">
                        <a class="dropdown-item" href="Pool.html">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Mining </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Pool">              Mining</a>
            </div--> <a class="dropdown-item" href="Issue.html">
                            <div class="icon">
                                <i class="fa fa-" aria-hidden="true"></i>
                            </div>
                            <div class="content">
                                Lab </div>
                        </a>
                        <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Issue">              Lab</a>
            </div-->
                    </div>
                </div>
            </div>
            @auth
            <div class="nav__item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <input type="submit" class="btn btn-outline-warning btn-sm text-dark w-100" value="Logout">
                           
                    
                </form>
                
            </div>
            @endauth
        </div>
        <script>
            //display flyout mobile-menu
            $(".nav__toggle").on("click", function () {
                $(".nav-mobile, .mobile-mask").toggleClass("show");
            });

            $(".mobile-mask").on("click", function () {
                $(".nav-mobile, .mobile-mask").removeClass("show");
            });
        </script>

    </header>