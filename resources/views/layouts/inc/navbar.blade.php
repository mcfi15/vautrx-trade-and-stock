<header class="dark-bb">

    <head>
        <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/icon-park.css') }}" />
        <style>
            .icon {
                color: #1e90ff;
            }

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
                background-color: #1e90ff;
                height: 54px;
                margin-bottom: 12px;
            }

            .nav__item:hover {
                background-color: #1e90ff;
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


 <style>
        /* body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        } */
        .translator-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        /* .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        } */
        .logo {
            font-size: 28px;
            font-weight: bold;
        }
        .nav-item.dropdown .dropdown-toggle {
            cursor: pointer;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            background: rgba(255,255,255,0.1);
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-item.dropdown .dropdown-toggle:hover {
            background: rgba(255,255,255,0.2);
        }
        .nav-item.dropdown .dropdown-toggle img {
            width: 24px;
            height: 16px;
            margin-right: 8px;
        }
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: none;
            padding: 10px 0;
        }
        .dropdown-item {
            padding: 8px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        .dropdown-item:hover {
            background-color: #3498db;
            color: white;
            transform: translateX(5px);
        }
        .dropdown-item img {
            width: 20px;
            height: 15px;
            margin-right: 10px;
        }
        .content {
            padding: 40px;
        }
        .content-section {
            margin-bottom: 40px;
            opacity: 1;
            transform: translateY(0);
            transition: all 0.5s ease;
        }
        .content-section.fade-out {
            opacity: 0;
            transform: translateY(20px);
        }
        .content-section.fade-in {
            opacity: 1;
            transform: translateY(0);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 2.5rem;
        }
        p {
            line-height: 1.7;
            color: #555;
            font-size: 1.1rem;
        }
        .feature-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            transition: all 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-box h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        /* .footer {
            background: #34495e;
            color: #ecf0f1;
            text-align: center;
            padding: 20px;
        } */
        .language-indicator {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 10px;
        }
    </style>
    </head>

    <nav class="navbar navbar-expand-lg">
        {{-- <a class="navbar-brand" href="{{ url('/') }}"><img alt="Dectrx"
                src="Upload/public/65d5f5101e8de.png" /></a> --}}

        <a class="navbar-brand" href="{{ url('/') }}"><img
                alt="{{ \App\Models\Setting::get('site_name', 'Website Name') }}"
                src="{{ asset(\App\Models\Setting::get('site_logo')) }}" /></a>

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

                            <a class="dropdown-item" href="{{ url('/stock-market') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-random"></i>
                                </div>

                                <div class="content text-white">Stock Trading <p class="featured">Hot</p> <span
                                        class="subtext">Best Stock Trading Platform</span> </div>
                            </a>

                            {{-- <a class="dropdown-item" href="{{ url('trading/pro') }}">
                                <div class="nav-icon">
                                    <i class="icon-size exchange-three"></i>
                                </div>

                                <div class="content text-white">Spot Professional <p class="featured">Hot</p> <span
                                        class="subtext">Tools for Pro</span> </div>
                            </a> --}}

                            <a class="dropdown-item" href="{{ url('/trade/spot') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-send-o"></i>
                                </div>

                                <div class="content text-white">Spot Classic <span class="subtext">Trade on our
                                        award-winning platform</span> </div>
                            </a><a class="dropdown-item" href="{{ url('/easy-trade') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-exchange"></i>
                                </div>

                                <div class="content text-white">Easy Convert <p class="featured">New</p> <span
                                        class="subtext">Easy Trade</span> </div>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown icon-flex-wrapper ml-3" id="Earn_box">
                    <div class="nav-noicon">
                    </div>
                    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                        data-toggle="dropdown" href="#" role="button">Earn
                        <span class="featured">Hot</span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-body">
                            <a class="dropdown-item" href="{{ url('/staking') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-lock"></i>
                                </div>

                                <div class="content text-white">Staking <span class="subtext">Stake and Get
                                        Rewards</span> </div>
                            </a><a class="dropdown-item" href="{{ url('/airdrops') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-cloud-download"></i>
                                </div>

                                <div class="content text-white">Airdrop <span class="subtext">Earn Tokens</span>
                                </div>
                            </a><a class="dropdown-item" href="{{ url('/faucets') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-tint"></i>
                                </div>

                                <div class="content text-white">Faucet <p class="featured">Grab</p> <span
                                        class="subtext">Free Coins and Tokens</span> </div>
                            </a>
                        </div>
                    </div>
                </li>
                <li id="Market_box" class="nav-item ml-3">
                    <a href="{{ url('/markets') }}" class="nav-link icon-flex-wrapper">

                        <div class="nav-noicon"></div> Market
                    </a>
                </li>
                <li class="nav-item dropdown icon-flex-wrapper ml-3" id=" _box">
                    <div class="nav-icon text-colo">
                        <i class="icon fa fa-sliders "></i>
                    </div>
                    <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle"
                        data-toggle="dropdown" href="#" role="button"> </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-body">
                            <a class="dropdown-item" href="{{ url('/pool') }}">
                                <div class="nav-icon">
                                    <i class="icon fa fa-server"></i>
                                </div>

                                <div class="content text-white">Mining <p class="featured">Mine to Earn</p> <span
                                        class="subtext">Rent mining machines</span> </div>
                            </a>
                            {{-- <a class="dropdown-item" href="Issue.html">
                                <div class="nav-icon">
                                    <i class="icon fa fa-flask"></i>
                                </div>

                                <div class="content text-white">Lab <p class="featured">Innovate</p> <span
                                        class="subtext">Blockchain Research and Investments</span> </div>
                            </a> --}}
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item header-custom-icon ml-3">
                    <a class="nav-link" href="#" id="clickFullscreen">
                        <i style="color:#1e90ff;" class="fa fa-expand"></i>
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
                            <i style="color:#1e90ff;" class="fa fa-briefcase "></i> wallet
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
                                        src="{{ asset('uploads/icons/Credit-Card-2--Streamline-Sharp-Neon.svg.svg') }}"
                                        style="width:2.5ex; margin-right:2ex;" /> Deposit</a>
                                <a class="dropdown-item logout_a" href="{{ url('wallet/withdraw') }}"> <img
                                        src="{{ asset('uploads/icons/Money-Atm--Streamline-Kameleon.svg.svg') }}"
                                        style="width:2.5ex; margin-right:2ex;" /> Withdraw</a>
                            </div>
                        </div>

                    </li>




                @else

                    <li class="nav-item header-custom-icon login-menu ml-3">
                        <a class="cd-signin nav-link" href="{{ url('login') }}">
                            <i style="color:#1e90ff;" class="fa fa-sign-in"></i> Sign In</a>
                    </li>
                    <li class="nav-item header-custom-icon login-menu ml-3">
                        <a class="cd-signup nav-link" href="{{ url('register') }}">
                            <i style="color:#1e90ff;" class="fa fa-user-plus"></i> Sign Up</a>
                    </li>

                @endauth




                <li class="nav-item dropdown header-custom-icon langMenu ml-3">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <div id="langselection">
            <img src="https://flagcdn.com/w40/gb.png" class="position-left" alt="English">
            <span class="cret-after-nav-item">English</span>
        </div>
    </a>
    <div class="dropdown-menu">
        <div class="dropdown-body">

            <span class="dropdown-item" data-lang="en">
                <img src="https://flagcdn.com/w20/gb.png" alt=""> English
            </span>
            <span class="dropdown-item" data-lang="tr">
                <img src="https://flagcdn.com/w20/tr.png" alt=""> Turkish
            </span>
            <span class="dropdown-item" data-lang="es">
                <img src="https://flagcdn.com/w20/es.png" alt=""> Spanish
            </span>
            <span class="dropdown-item" data-lang="nl">
                <img src="https://flagcdn.com/w20/nl.png" alt=""> Dutch
            </span>
            <span class="dropdown-item" data-lang="il">
                <img src="https://flagcdn.com/w20/il.png" alt=""> Hebrew
            </span>
            <span class="dropdown-item" data-lang="de">
                <img src="https://flagcdn.com/w20/de.png" alt=""> German
            </span>
            <span class="dropdown-item" data-lang="ko">
                <img src="https://flagcdn.com/w20/kr.png" alt=""> Korean
            </span>
            <span class="dropdown-item" data-lang="ja">
                <img src="https://flagcdn.com/w20/jp.png" alt=""> Japanese
            </span>
            <span class="dropdown-item" data-lang="pt">
                <img src="https://flagcdn.com/w20/pt.png" alt=""> Portuguese
            </span>
            <span class="dropdown-item" data-lang="ru">
                <img src="https://flagcdn.com/w20/ru.png" alt=""> Russian
            </span>
            <span class="dropdown-item" data-lang="vi">
                <img src="https://flagcdn.com/w20/vn.png" alt=""> Vietnamese
            </span>
            <span class="dropdown-item" data-lang="zhtw">
                <img src="https://flagcdn.com/w20/tw.png" alt=""> Traditional Chinese
            </span>
            <span class="dropdown-item" data-lang="zhcn">
                <img src="https://flagcdn.com/w20/cn.png" alt=""> Simplified Chinese
            </span>

        </div>
    </div>
</li>


                @auth
                    <li class="nav-item dropdown userMenu header-custom-icon ml-3">
                        <a aria-expanded="false" aria-haspopup="true" class="nav-link dropdown-toggle cret-after-nav-item"
                            data-toggle="dropdown" href="#" role="button">
                            {{ auth()->user()->name }}

                        </a>
                        <div class="dropdown-menu">
                            {{-- <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <p class="font-weight-medium"><a href="mailto:{{ auth()->user()->email }}"
                                        class="__cf_email__"
                                        data-cfemail="5526203c2c3934213d153d3c677b3c3b">[email&#160;protected]</a></p>
                            </div> --}}
                            <div class="dropdown-body">
                                <a class="dropdown-item" href="{{ url('user-center') }}">
                                    <span>User Center</span></a>
                                <a class="dropdown-item" href="{{ url('kyc') }}">
                                    <span>KYC</span></a>
                                <a class="dropdown-item" href="{{ url('login-history') }}"><span>User Actions</span></a>
                                <a class="dropdown-item" href="{{ url('giftcard') }}"><span>Gift Card</span></a>
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
                        <i style="color:#1e90ff;" class="fa fa-moon-o "></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="mobile-mask"></div>
    <div class="nav-mobile pt-2">
        <span class="mob_dark_mode">
            <a class="nav-icon changeThemeLight bg-transparent" href="#!">
                <i style="color:#1e90ff;" class="fa fa-moon-o "></i>
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
                    <a href="{{ url('user-center') }}">Account settings</a>
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
                        <a class="btn btn-outline-primary btn-sm text-dark" href="{{ url('login') }}">Sign In</a>
                    </div>
                    <div class="nav__item">
                        <a class="btn btn-outline-primary btn-sm text-dark" href="{{ url('register') }}">Sign Up</a>
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
                    <a class="dropdown-item" href="{{ url('stock-market') }}">
                        <div class="icon">
                            <i class="fa fa-" aria-hidden="true"></i>
                        </div>
                        <div class="content">
                            Stock Trading </div>
                    </a>
                    <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                              <a href="../Trade/tradepro">
                            Spot Professional</a>
            </div--> <a class="dropdown-item" href="{{ url('trade/spot') }}">
                        <div class="icon">
                            <i class="fa fa-" aria-hidden="true"></i>
                        </div>
                        <div class="content">
                            Spot Classic </div>
                    </a>
                    <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Trade">              Spot Classic</a>
            </div--> <a class="dropdown-item" href="{{ url('easy-trade') }}">
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
                    <a class="dropdown-item" href="{{ url('staking') }}">
                        <div class="icon">
                            <i class="fa fa-" aria-hidden="true"></i>
                        </div>
                        <div class="content">
                            Staking </div>
                    </a>
                    <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Invest">              Staking</a>
            </div--> <a class="dropdown-item" href="{{ url('/airdrops') }}">
                        <div class="icon">
                            <i class="fa fa-parachute" aria-hidden="true"></i>
                        </div>
                        <div class="content">
                            Airdrop </div>
                    </a>
                    <!--div class="nav__item">
              <i class="fa fa-parachute" aria-hidden="true"></i>
                                              <a href="/Airdrops">              Airdrop</a>
            </div--> <a class="dropdown-item" href="{{ url('/faucets') }}">
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
                    <a class="dropdown-item" href="{{ url('/pool') }}">
                        <div class="icon">
                            <i class="fa fa-" aria-hidden="true"></i>
                        </div>
                        <div class="content">
                            Mining </div>
                    </a>
                    <!--div class="nav__item">
              <i class="fa fa-" aria-hidden="true"></i>
                                              <a href="/Pool">              Mining</a>
            </div-->
                    {{-- <a class="dropdown-item" href="Issue.html">
                        <div class="icon">
                            <i class="fa fa-" aria-hidden="true"></i>
                        </div>
                        <div class="content">
                            Lab </div>
                    </a> --}}
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
                    <input type="submit" class="btn btn-outline-primary btn-sm text-dark w-100" value="Logout">


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

