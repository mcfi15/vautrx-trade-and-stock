@extends('layouts.app')

{{-- @section('title', 'Dashboard') --}}

@section('content')
    <!-- Home Slider Start -->
    <section class="banner-section">
        <div class="container">
            <div class="banner-inner-section">
                <div class="banner-left-section">
                    <div class="index_banner-title__Ueyv2" data-wow-delay="0.2s">
                        <h1 class="Main-heading">Empowering the Future of Crypto Trading</h1>
                        <p>Seamlessly Buy, Sell, Trade, and Hold Cryptocurrencies on
                            {{ \App\Models\Setting::get('site_name', 'Website Name') }}.com</p>
                    </div>


                    <div class="banner-email">
                        <div class="index_quick-reg-wrapper__AGLkO">
                            @guest
                                <div class="col-md-8 col-sm-12">
                                    <a href="{{ route('auth.google') }}" class="btn white-bg btn-block btn-lg common-text"
                                        data-onsuccess="onSignIn">

                                        <span><img src='{{ asset('Public/template/epsilon/img/redesign/google-icon.svg') }}' />
                                            Signin with
                                            Google</span></a>
                                    <div class="hr-box">
                                        <div class="hr-line"></div>
                                        <div data-bn-type="text" class="hr-text">or continue with </div>
                                        <div class="hr-line"></div>
                                    </div>

                                    <a href="{{ url('register') }}" class="btn yellow-bg btn-block btn-lg">Sign up using
                                        Email</a>

                                </div>
                            @endguest
                        </div>
                    </div>
                </div>


                <img src="{{ asset('Public/template/epsilon/img/new/buy-and-sell-banner.png') }}"
                    class="index_banner-image Light__mode">
                <img src="{{ asset('Public/template/epsilon/img/new/buy-and-sell-banner.png') }}"
                    class="index_banner-image Dark__mode">
            </div>
        </div>
    </section>
    <style>
        .banner-email .dropdown-menu img {
            width: 24px;
            vertical-align: bottom;
        }

        .banner-email .dropdown-menu {
            max-height: inherit !important;
            min-height: auto !important;
            height: 200px !important;
        }

        .Dark__mode {
            display: none;
        }

        body#light {
            background-color: #FFFFFF;
            color: rgba(25, 25, 25, 1);
        }

        body#dark {
            background-color: #111111;
            color: rgba(197, 197, 197, 1);
        }

        .banner-section .container {
            width: 100%;
            max-width: 1260px;
            padding: 0 30px;
            margin: 0 auto;
        }

        .banner-section .index_banner-image {
            width: 330px;
            height: 440px;
        }

        .banner-inner-section {
            display: flex;
            width: 1200px;
            padding-right: 80px;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .banner-section {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            /*overflow: hidden;*/
            background-color: #000001;
            padding-top: 60px;
            width: 100%;
            height: 500px;
            background: transparent;
            background-size: cover;
            background-position: 50%;
            background-image: url(Public/template/epsilon/img/redesign/slider/Banner-background-image-dark.png);
        }

        #light .banner-section {
            background-image: url(Public/template/epsilon/img/redesign/slider/Banner-background-image-light.png);
        }

        #light .index_list-content-ul__3ZsrS:hover {
            background-color: rgba(254, 190, 64, .08) !important;
        }

        #light .Main-heading {
            color: rgba(13, 13, 13, 1);
        }

        #light .graph-main-section {
            background-color: rgba(246, 246, 246, 1);
            border: 0;
        }

        #light .home-markets .nav.nav-pills .nav-link {
            box-shadow: none;
        }

        #light .home-markets .nav.nav-pills .nav-link.current {
            border-bottom: 2px solid;
        }

        #light .home-markets .nav.nav-pills .nav-link.current a.active {
            color: black;
        }

        #light .home-markets tr:nth-of-type(odd),
        #light .home-markets tr:nth-of-type(odd) {
            background: #fff !important;
        }

        #light h1,
        #light h2,
        #light h3,
        #light h4,
        #light h5,
        #light h6,
        #light span {
            color: rgba(13, 13, 13, 1);
        }

        #light .grey-bg {
            background: white;
        }

        #light form.currency-selector-form {
            background: rgba(246, 246, 246, 1);
        }

        #light .explore-box {
            background-color: rgba(246, 246, 246, 1);
        }

        #light .Dark__mode {
            display: block;
        }

        #light .Light__mode {
            display: none;
        }

        #light .Crypto-Lending-section .pick-plans-section .plans p:after,
        #light .radio-button-form .days,
        #light .radio-button-forms .days {
            background-color: #fff;
        }

        #light .start-journey-section,
        #light .Crypto-Lending-section .earning-section .earning,
        #light .currency-main-section .currency-box {
            background-color: #F6F6F6;
        }

        #light section.apps {
            background: transparent;
            border: 0;
            color: black;
        }

        #light section.blog {
            background: white;
        }

        .footer-blocks h4,
        .footer-blocks h3 {
            color: white !important;
        }

        .banner-section .Main-heading {
            text-align: left;
            margin-bottom: 30px;
            width: 713px;
            font-weight: 700;
            font-size: 62px;
            line-height: 74px;
            color: rgba(255, 255, 255, 1);
        }

        .banner-section p {
            margin-bottom: 58px;
            font-weight: 400;
            font-size: 20px;
            line-height: 20px;
            text-align: left;
            color: rgba(197, 197, 197, 1);
        }

        #light .banner-section p {
            color: black;
        }

        .banner-email {
            width: 600px;
        }

        .slick-dots li button:before {
            background-color: #1e90ff !important;
        }

        .index_quickRegTool-box__CSWGE {
            width: 600px;
        }

        @media (max-width: 768px) {
            .home-guide-container-step .home-guide-section .image-section {
                margin-left: 0 !important;
            }
        }

        @media(max-width: 1199px) {
            .explore-box {
                padding: 40px 0 0 14px !important;
            }

            .graph-main-section .graphs {
                max-width: 150px !important;
            }

            .graph-main-section .graph-top-section h2 {
                font-size: 16px !important;
            }

            .graph-main-section .red-txt {
                font-size: 11px !important;
            }

            .currency-main-section .Main_headings {
                margin-bottom: 25px !important;
            }

            .Main_headings {
                font-size: 34px !important;
                line-height: 50px !important;
            }

            .currency-main-section ul li,
            .currency-main-section p {
                line-height: 36px !important;
            }

            .home-guide-container-step .home-guide-section:nth-child(even) .image-section {
                margin-left: 80px !important;
            }

            .home-guide-container-step .home-guide-section .image-section {
                margin-right: 80px !important;
            }

            .app-download-container .app-lists li {
                font-size: 16px !important;
                padding-left: 44px !important;
            }

            .banner-section .Main-heading {
                font-size: 52px !important;
                line-height: 64px !important;
                width: 100% !important;
            }

            .banner-section .index_banner-image {
                width: 100% !important;
                max-width: 250px !important;
                object-fit: cover !important;
                height: auto !important;
            }

            .banner-section .banner-inner-section {
                width: 100% !important;
            }

            .banner-section .banner-left-section {
                padding-right: 40px !important;
            }

            .explore-box p {
                font-size: 24px !important;
                line-height: 36px !important;
                padding-right: 0 !important;
            }

            .explore-box-bottom img {
                width: 70px !important;
                height: 70px !important;
            }

            .app-download-container .index_qcode-drop__XGiQE .scanner-img svg {
                max-width: 100px !important;
            }

            .app-download-container .index_download-item a {
                padding: 10px 22px !important;
            }

            .start-journey-section {
                padding-top: 60px !important;
                padding-bottom: 80px !important;
            }

            .Crypto-Lending-section {
                padding: 0 0 80px 0 !important;
            }

            .explore-our-products {
                padding-top: 88px !important;
                padding-bottom: 80px !important;
            }

            .currency-main-section {
                padding-top: 20px !important;
            }

            .scrolling-text .slick-vertical .slick-slide a {
                margin-right: 40px !important;
            }

            .banner-bottom-section {
                max-width: 860px !important;
            }

            .banner-bottom-section .index_top__XL9xj {
                height: 40px !important;
                font-size: 30px !important;
            }

            .banner-top-section .index_titleBox__5j_B3 .about-headings {
                font-size: 50px !important;
            }

            .banner-top-section .index_titleBox__5j_B3 {
                font-size: 40px !important;
                line-height: 50px !important;
                margin: 130px 0 auto !important;
            }

            .about-banner-image {
                min-height: 790px !important;
            }

            .Trading-Categories-section {
                padding: 90px 0 103px 0 !important;
            }

            .Trading-Categories-section .index_imgBox__foYwd {
                margin: 50px auto 0 !important;
            }

            .Trading-Platform-section {
                padding: 90px 0 0 0 !important;
            }

            .Business-scope-section .index_titleAll__e7b9e {
                padding-top: 90px !important;
            }

            .image-width-text-section {
                padding-top: 50px !important;
            }
        }

        @media(max-width: 989px) {
            .banner-section .index_banner-image {
                display: none !important;
            }

            .banner-section .banner-left-section {
                padding-right: 0 !important;
                width: 100% !important;
            }

            .banner-section .banner-inner-section {
                flex-direction: column !important;
                padding: 0 !important;
            }

            .banner-section {
                padding: 34px 0 100px !important;
                height: unset !important;
            }

            .graph-main-section .graphs {
                max-width: 130px !important;
            }

            .graph-main-section .graph-top-section h2 {
                font-size: 14px !important;
                margin-right: 8px !important;
            }

            .Crypto-Lending-section .earning-section {
                grid-template-columns: 1fr !important;
            }

            .start-journey-section .start-journey-boxes .index_step__bix_X img {
                width: 60px !important;
                height: 60px !important;
                margin-bottom: 20px !important;
            }

            .start-journey-section .start-journey-boxes p {
                font-size: 16px !important;
                line-height: 20px !important;
                text-align: center !important;
            }
        }

        @media(max-width: 768px) {
            .banner-email {
                width: 100% !important;
            }

            .banner-section .Main-heading {
                font-size: 36px !important;
                line-height: 40px !important;
                max-width: 290px !important;
                margin: 0 0 20px 0 !important;
            }

            .banner-section p {
                margin-bottom: 20px !important;
                font-size: 20px !important;
                line-height: 24px !important;
            }

            .select_wrap .default_option li {
                padding: 8px 20px !important;
                max-height: 44px !important;
            }

            .select_wrap .select_ul li {
                padding: 6px 20px !important;
            }

            .currency-input-main .default_option-top li {
                padding: 8px 20px !important;
                max-height: 44px !important;
            }

            form.currency-selector-form input,
            .currency-input-main .currency-input-filed {
                height: 48px !important;
            }

            .currency-input-filed label {
                top: -5px !important;
                font-size: 11px !IMPORTANT;
            }

            .select_wrap .select_ul {
                top: 46px !important;
                padding: 8px 0 !important;
            }

            .select_wrap .option p {
                font-size: 14px !important;
                line-height: 22px !important;
            }

            .image-width-text-section {
                padding-top: 40px !important;
            }

            .graph-main-section .graph-inner-section.Mobile_view .owl-item.active~.active {
                border-right: 0 !important;
            }

            .graph-main-section .graph-inner-section.Mobile_view .owl-item.active {
                border-right: 1px solid #525252 !important;
            }

            .graph-main-section .graphs {
                border-right: 0 !important;
                padding: 0px 13px !important;
            }

            .graph-main-section .graph-inner-section.Mobile_view {
                display: block !important;
            }

            form.currency-selector-form ul.border-section {
                margin: 32px 0 !important;
            }

            .container {
                padding: 0 15px !important;
            }

            .graph-main-section .graphs {
                max-width: 100% !important;
            }

            .graph-main-section {
                padding: 26px 15px !important;
                margin: 30px 0 !important;
            }

            .currency-main-section {
                padding-top: 30px !important;
            }

            .currency-inner-section {
                grid-template-columns: 1fr !important;
                grid-gap: 30px !important;
            }

            .Main_headings {
                font-size: 24px !important;
                line-height: 30px !important;
            }

            form.currency-selector-form {
                padding: 14px !important;
            }

            .currency-main-section ul li,
            .currency-main-section p {
                line-height: 28px !important;
                font-size: 16px !important;
                margin-bottom: 8px !important;
            }

            .home-guide-container-step .home-guide-section {
                flex-direction: column !IMPORTANT;
            }

            .home-guide-container-step .home-guide-section .image-section img {
                max-width: 100% !important;
                width: 100% !important;
            }

            .home-guide-container-step .home-guide-section .image-section {
                background: #181819 !important;
                padding: 30px 35px !important;
                margin: 0 0 10px 0 !important;
            }

            #light .home-guide-container-step .home-guide-section .image-section {
                background: rgba(0, 0, 0, .03) !important;
            }

            .home-guide-container-step .home-guide-section {
                margin-bottom: 20px !important;
            }

            .home-guide-container-step .home-guide-section:nth-child(even) {
                padding: 0 !important;
            }

            .explore-our-products {
                padding-top: 30px !important;
                padding-bottom: 40px !important;
            }

            .explore-our-products .Main_headings {
                margin-bottom: 30px !important;
            }

            .explore-box-main-section {
                display: grid !important;
                grid-template-columns: 1fr !important;
                grid-gap: 20px !important;
            }

            .Crypto-Lending-inner-section .top-section .Main_headings {
                text-align: left !important;
                margin-bottom: 10px !important;
            }

            .Crypto-Lending-inner-section .top-section p {
                text-align: left !important;
                font-size: 16px !important;
                line-height: 22px !important;
                margin: 0 !important;
            }

            .Crypto-Lending-section .pick-plans-section {
                flex-direction: column !important;
                justify-content: flex-start !important;
                position: relative !important;
            }

            .Crypto-Lending-section .pick-plans-section:before {
                content: '' !important;
                position: absolute !important;
                width: 1px !important;
                height: 70% !important;
                background-color: #4E4E4E !important;
                left: 22px !important;
            }

            .Crypto-Lending-section .pick-plans-section .plans {
                flex-direction: row !important;
                padding: 0 !important;
                justify-content: flex-start !important;
                margin-bottom: 50px !important;
            }

            .Crypto-Lending-section .pick-plans-section .plans p:before,
            .Crypto-Lending-section .pick-plans-section .plans p:after,
            .Crypto-Lending-section .pick-plans-section:after {
                display: none !important;
            }

            .Crypto-Lending-section .pick-plans-section:before {
                content: '' !important;
                position: absolute !important;
                width: 1px !important;
                height: 63% !important;
                background-color: #4E4E4E !important;
                top: 50% !important;
                transform: translate(0, -50%) !important;
                left: 20px !important;
                z-index: -1 !important;
            }

            .plans-svg:after {
                content: '' !important;
                background-color: #4E4E4E !important;
                width: 9px !important;
                height: 9px !important;
                position: absolute !important;
                border-radius: 50% !important;
                top: 66px !important;
                left: 16px !important;
            }

            .Crypto-Lending-section .pick-plans-section .plans:last-child .plans-svg::after {
                display: none !important;
            }

            .Crypto-Lending-section .pick-plans-section .plans svg {
                max-width: 45px !important;
                height: auto !important;
            }

            .plans-svg {
                position: relative !important;
                margin-right: 24px !important;
            }

            .Crypto-Lending-section .pick-plans-section .plans p {
                font-size: 14px !important;
                max-width: 100% !important;
                text-align: left !important;
                padding: 0 !important;
            }

            .earning-top-section p {
                font-size: 22px !important;
                line-height: 20px !important;
            }

            .Crypto-Lending-section .earning-section .earning {
                padding: 20px 10px !important;
            }

            .radio-button-form label,
            .radio-button-forms label {
                margin: 0 10px 13px 0 !important;
            }

            .radio-button-form .days span,
            .radio-button-forms .days span {
                font-size: 18px !important;
            }

            .radio-button-form .days,
            .radio-button-forms .days {
                padding: 7px 8px !important;
            }

            .Crypto-Lending-section .pick-plans-section .plans:last-child {
                margin-bottom: 0 !important;
            }

            .start-journey-section {
                padding-top: 20px !important;
                padding-bottom: 40px !important;
            }

            .start-journey-section .index_action__IhrM5 {
                display: none !important;
            }

            .start-journey-section .start-journey-boxes {
                margin-bottom: 0 !important;
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                justify-content: center !important;
            }

            .start-journey-section .start-journey-boxes .index_step__bix_X img {
                margin-bottom: 16px !important;
            }

            .start-journey-section .index_step-line__Z7ooH {
                display: none !important;
            }

            .start-journey-section .start-journey-boxes .index_step__bix_X {
                width: 100% !important;
                margin-bottom: 65px !important;
            }

            .start-journey-section .start-journey-boxes .index_step__bix_X:last-child {
                margin-bottom: 0 !important;
            }

            .start-journey-section .Main_headings {
                margin-bottom: 30px !important;
            }

            .Main_headings {
                line-height: 30px !important;
            }

            section.apps {
                padding: 0 !important;
            }
        }

        #dark .blog .blog-grid .card .card-body {
            background-color: #393b41;
        }

        #dark .blog-grid .card .card-body .card-title,
        #dark .blog .card .card-body .card-text,
        .blog .card .card-body .date {
            color: white !important;
        }

        #light .select_wrap .default_option {
            background-color: #f6f6f6 !important;
        }

        @media(max-width: 425px) {
            .banner-section .Main-heading {
                font-size: 24px !important;
                text-align: center;
                max-width: 100% !important;
            }

            .banner-section p {
                text-align: center;
            }

            .banner-section {
                padding: 34px 0 40px !important;
            }

            .slick-slide {
                font-size: 12px;
            }

            .announcements-block .icon {
                padding: 0 !important;
            }

            .read-more.ml-auto {
                top: 32px;
            }

            tbody#price_today_ul tr td {
                font-size: 12px;
            }

            .currency-main-section {
                padding-top: 0px !important;
            }

            .Main_headings {
                line-height: inherit !important;
                font-size: 20px !important;
            }

            .currency-main-section .Main_headings {
                margin-bottom: 10px !important;
                line-height: inherit !important;
            }

            .currency-input-filed label,
            .currency-input-main .currency-input-filed .give-text {
                left: 10px !important;
                line-height: 22px !important;
            }

            form.currency-selector-form input,
            .currency-input-main .currency-input-filed {
                top: 50% !important;
            }

            .currency-main-section ul li,
            .currency-main-section p,
            .explore-our-products .Main_headings {
                margin-bottom: 0 !important;
            }

            .home-guide-container-step .home-guide-section:nth-child(even) .image-section {
                margin-left: 0 !important;
            }

            .explore-box {
                padding: 14px 0 0 14px !important;
            }

            .explore-box p {
                font-size: 20px !important;
                line-height: 30px !important;
                margin-bottom: 0 !important;
            }

            .earning-top-section p {
                font-size: 18px !important;
                line-height: inherit !important;
                margin-bottom: 0;
            }

            .earning-left-section svg {
                width: 36px;
                height: 36px;
            }

            .radio-button-form label,
            .radio-button-forms label {
                margin: 0 5px 10px 0 !important;
            }

            .radio-button-form .days,
            .radio-button-forms .days {
                padding: 3px 4px !important;
            }

            .radio-button-form .days span,
            .radio-button-forms .days span {
                font-size: 14px !important;
            }

            .site-btn,
            .currency-main-section .site-btn {
                height: 40px !important;
                font-size: 16px !important;
            }

            .Crypto-Lending-section {
                padding: 0 0 30px 0 !important;
            }

            section.apps .appss-content h2 {
                font-size: 24px;
                text-align: center;
            }

            section.apps .appss-content .apps-property li,
            .app-page .app-banner li {
                font-size: 16px;
                line-height: 24px;
            }

            section.apps .appss-content {
                margin-bottom: 0;
            }

            section.blog {
                padding-top: 0;
            }

            .section-title {
                font-size: 28px;
                margin-bottom: 10px !important;
            }

            .home-markets .nav.nav-pills .nav-link {
                padding: 14px 10px;
                font-size: 13px !important;
            }
        }

        @media(max-width: 390px) {

            .radio-button-form,
            .radio-button-forms {
                flex-wrap: wrap !important;
            }

            .banner-email {
                width: 100% !important;
            }

            section.apps {
                padding: 0 !important;
            }
        }
    </style>
    <!-- Home Slider End -->


    <br>
    <!-- Announcements Start -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="announcements-block d-flex align-items-center">
                    <div class="icon">
                        <i class="ion ion-md-megaphone"></i>
                    </div>
                    <div class="announcements-single-item">

                        <div>
                            <a href="{{ url('article/13') }}">Successfully Finalizes Extensive Database Enhancement for
                                Enhanced Exchange Performance...</a>
                        </div>
                        <div>
                            <a href="{{ url('article/11') }}">Notice About Unauthorized Text Message Activity...</a>
                        </div>
                        <div>
                            <a href="{{ url('article/10') }}">We care about you, your funds are secure with us!...</a>
                        </div>
                    </div>
                    <!--div class="read-more ml-auto">
                            <a href="/Index/Article">More <i class="ion ion-md-arrow-forward"></i></a>
                        </div-->
                </div>
            </div>
        </div>
    </div>
    <!-- Announcements End -->
    <!--Home Market Start -->
    <section class="home-market">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="home-markets">

                        <div class="d-flex justify-content-between align-items-center" id="market-tab-box">
                            <ul class="nav nav-pills trade_qu_list" role="tablist">

                                <li class=" nav-link trade_moshi trade_qu_pai current" data="0.html"
                                    onclick="switchMarket('USDT')">
                                    <a href="#highlighted-justified-tab1" class="active" data-toggle="tab">USDT</a>
                                </li>
                                <li class=" nav-link trade_moshi trade_qu_pai " data="1.html" onclick="switchMarket('BTC')">
                                    <a href="#highlighted-justified-tab1" class="" data-toggle="tab">BTC</a>
                                </li>
                                <li class=" nav-link trade_moshi trade_qu_pai " data="2.html" onclick="switchMarket('ETH')">
                                    <a href="#highlighted-justified-tab1" class="" data-toggle="tab">ETH</a>
                                </li>
                                <li class=" nav-link trade_moshi trade_qu_pai " data="3.html" onclick="switchMarket('EUR')">
                                    <a href="#highlighted-justified-tab1" class="" data-toggle="tab">EUR</a>
                                </li>
                            </ul>

                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" style="min-height: 200px !important;"
                                id="daily-top-winners" role="tabpanel">
                                <div class="table-responsive">

                                    <table class="table coin-list">
                                        <thead class="price_today_ull">
                                            <tr>
                                                <th scope="col" data-sort="0" style="cursor: default;">PAIR</th>
                                                <th scope="col" class="click-sort" data-sort="1" data-flaglist="0"
                                                    data-toggle="0">Price </th>

                                                <th scope="col" class="click-sort" data-sort="2" data-flaglist="0"
                                                    data-toggle="0">Buy</th>
                                                <th scope="col" class="click-sort" data-sort="3" data-flaglist="0"
                                                    data-toggle="0">Sell</th>
                                                <th scope="col" class="click-sort" data-sort="6" data-flaglist="0"
                                                    data-toggle="0">24H Vol</th>
                                                <th scope="col" class="click-sort" data-sort="4" data-flaglist="0"
                                                    data-toggle="0">24H Total</th>
                                                <th scope="col" class="click-sort" data-sort="7" data-flaglist="0"
                                                    data-toggle="0">Change</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="market-table-body">

                                            {{-- Default load USDT --}}
                                            @foreach($markets['USDT'] as $pair)
                                                @include('frontend.partials.home-market', ['pair' => $pair])
                                            @endforeach

                                        </tbody>



                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <a href="{{ url('markets') }}" class="btn yellow-bg btn-lg viewall_btn">View all</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Home Market End -->
    {{-- <style>
        .viewall_btn {
            font-size: 20px;
        }
    </style>

    <script>
        async function fetchMarketData() {
            const response = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&amp;order=market_cap_desc&amp;per_page=10&amp;page=1&amp;sparkline=true');
            const data = await response.json();
            const marketData = document.getElementById('market-data');
            const labels = [];
            const prices = [];

            data.forEach(coin => {
                labels.push(coin.name);
                prices.push(coin.current_price);

                const row = document.createElement('tr');
                row.innerHTML = `
                        <td>${coin.name}</td>
                        <td>$${coin.current_price}</td>
                        <td>${coin.price_change_percentage_24h.toFixed(2)}%</td>
                        <td>${coin.total_volume}</td>
                        <td>$${coin.high_24h}</td>
                        <td>$${coin.low_24h}</td>
                        <td><button style="background-color: #00d084; color: #fff; border: none; padding: 5px 10px; border-radius: 5px;">Trade</button></td>
                    `;
                marketData.appendChild(row);
            });
        }

        // fetchMarketData();
    </script>
    <script>
        $('.price_today_ull > .click-sort').each(function () {
            $(this).click(function () {
                click_sortList(this);
            })
        });
        function allcoin_callback(priceTmp) {
            for (var i in priceTmp) {
                var c = priceTmp[i][8];
                if (typeof (trends[c]['data']) && typeof (trends[c]['data']) != 'null') {
                    if (typeof (trends[c]) != 'undefined' && typeof (trends[c]['data']) != 'undefined') {

                    }
                }
            }
        }
        function click_sortList(sortdata) {
            var a = $(sortdata).attr('data-toggle');
            var b = $(sortdata).attr('data-sort');
            $(".price_today_ull > th").find('.fa-angle-up').css('border-bottom-color', '#848484');
            $(".price_today_ull > th").find('.fa-angle-down').css('border-top-color', '#848484');
            $(".price_today_ull > th").attr('data-flaglist', 0).attr('data-toggle', 0);
            $(".price_today_ull > th").css('color', '');
            $(sortdata).css('color', '#1a81d6');
            if (a == 0) {
                priceTmp = priceTmp.sort(sortcoinList('dec', b));
                $(sortdata).find('.fa-angle-down').css('border-top-color', '#1a81d6');
                $(sortdata).find('.fa-angle-up').css('border-bottom-color', '#848484');
                $(sortdata).attr('data-flaglist', 1).attr('data-toggle', 1)
            }
            else if (a == 1) {
                $(sortdata).attr('data-toggle', 0).attr('data-flaglist', 2);
                $(sortdata).find('.fa-angle-up').css('border-bottom-color', '#1a81d6');
                $(sortdata).find('.fa-angle-down').css('border-top-color', '#848484');
                priceTmp = priceTmp.sort(sortcoinList('asc', b));
            }
            renderPage(priceTmp);
            change_line_bg('price_today_ul', 'li');
            //	allcoin_callback(priceTmp);
        }
        function trends() {
            $.getJSON("/Ajax/top_coin_menu?t=" + rd(), function (d) {
                trends = d;
                allcoin();
            });
        }
        function allcoin(cb) {
            var trade_qu_id = $('.trade_qu_list .current').attr('data');
            $.get("/Ajax/allcoin_a/id/" + trade_qu_id + '?t=' + rd(), cb ? cb : function (data) {
                var d;
                if (data.status == 1) {
                    d = data.url;
                }
                ALLCOIN = d;
                var t = 0;
                var img = '';
                priceTmp = [];
                for (var x in d) {
                    if (typeof (trends[x]) != 'undefined' && parseFloat(trends[x]['yprice']) > 0) {
                        rise1 = (((parseFloat(d[x][4]) + parseFloat(d[x][5])) / 2 - parseFloat(trends[x]['yprice'])) * 100) / parseFloat(trends[x]['yprice']);
                        rise1 = rise1.toFixed(2);
                    } else {
                        rise1 = 0;
                    }
                    img = d[x].pop();
                    d[x].push(rise1);
                    d[x].push(x);
                    d[x].push(img);
                    priceTmp.push(d[x]);
                }
                $('.price_today_ull > .click-sort').each(function () {
                    var listId = $(this).attr('data-sort');
                    if ($(this).attr('data-flaglist') == 1 && $(this).attr('data-sort') !== 0) {
                        priceTmp = priceTmp.sort(sortcoinList('dec', listId))
                    } else if ($(this).attr('data-flaglist') == 2 && $(this).attr('data-sort') !== 0) {
                        priceTmp = priceTmp.sort(sortcoinList('asc', listId))
                    }
                });
                renderPage(priceTmp);
                //	allcoin_callback(priceTmp);
                change_line_bg('price_today_ul', 'li');
                //t = setTimeout('allcoin()', 5000);
            }, 'json');
        }
        function rd() {
            return Math.random()
        }
        function renderPage(ary) {
            var html = '';
            console.log(ary.length);
            for (var i in ary) {
                if (i < 7) {
                    var coinfinance = 0;
                    if (typeof FINANCE == 'object') coinfinance = parseFloat(FINANCE.data[ary[i][8] + '_balance']);
                    let total_24 = ary[i][4] * ary[i][6];
                    html += '<tr id="m_' + ary[i][8] + '"><td><a href="/trade/index/market/' + ary[i][8] + '/">' + ary[i][12] + '</a></td><td class="tc-warning">' + ary[i][1] + '</td><td >' + ary[i][2] + '</td><td >' + ary[i][3] + '</td><td >' + formatCount(ary[i][6]) + '</td><td >' + formatCount(total_24) + '</td><td class="' + (ary[i][7] >= 0 ? 'green' : 'red') + '" >' + (parseFloat(ary[i][7]) < 0 ? '' : '') + ((parseFloat(ary[i][7]) < 0.01 && parseFloat(ary[i][7]) > -0.01) ? "0.00" : (parseFloat(ary[i][7])).toFixed(2)) + '%</td><td class="text-right"><a href=\'/trade/index/market/' + ary[i][8] + '/\'" class="btn-2"> Trade</a></td></tr>'
                }
            }
            $('#price_today_ul').html(html);
        }
        function formatCount(count) {
            count = parseFloat(count.toFixed(8));
            var countokuu = (count / 1000000000).toFixed(3);
            var countwan = (count / 1000000).toFixed(3);
            if (count > 1000000000)
                return countokuu.substring(0, countokuu.lastIndexOf('.') + 3) + ' bl';
            if (count > 1000000)
                return countwan.substring(0, countwan.lastIndexOf('.') + 3) + ' ml';
            else
                return count;

        }
        function change_line_bg(id, tag, nobg) {
            var oCoin_list = $('#' + id);
            var oC_li = oCoin_list.find(tag);
            var oInp = oCoin_list.find('input');
            var oldCol = null;
            var newCol = null;
            if (!nobg) {
                for (var i = 0; i < oC_li.length; i++) {
                    oC_li.eq(i).css('background-color', i % 2 ? '#f8f8f8' : '#fff');
                }
            }
            oCoin_list.find(tag).hover(function () {
                oldCol = $(this).css('backgroundColor');
                $(this).css('background-color', '#eaedf4');
            }, function () {
                $(this).css('background-color', oldCol);
            })
        }
        function sortcoinList(order, sortBy) {
            var ordAlpah = (order == 'asc') ? '>' : '<';
            var sortFun = new Function('a', 'b', 'return parseFloat(a[' + sortBy + '])' + ordAlpah + 'parseFloat(b[' + sortBy + '])? 1:-1');
            return sortFun;
        }
        function trade_qu(o) {
            $('.trade_qu_pai').removeClass('current');
            $(o).addClass('current');
            allcoin();
        }
        trends();
    </script> --}}


    <div class="ics ics--keep wp-block-lazyblock-section lazyblock-section-6aWzL">
        <div class="block-section">
            <div class="container container--medium ">
                <div class="wp-container-7 wp-block-columns are-vertically-aligned-center">
                    <div class="wp-container-5 wp-block-column is-vertically-aligned-center ics__copy">
                        <h2 class="hide-mobile wow animate__fadeInUp" id="keep-your-crypto-secure"
                            style="visibility: visible; animation-name: fadeInUp;">World-leading Crypto
                            Security</h2>
                        <div class="wow animate__fadeInUp wp-block-lazyblock-icon-list lazyblock-icon-list-Z2svB4C"
                            style="visibility: visible; animation-name: fadeInUp;">
                            <div class="ics__icon__list">
                                <div class="ics__icon__item">
                                    <div class="ics__icon__item__icon">
                                        <img width="640" height="640"
                                            src="{{ asset('includes/uploads/2021/11/icon-funds-stored.svg') }}"
                                            class="attachment-large size-large" alt="" loading="lazy">
                                    </div>
                                    <h4 class="ics__icon__item__heading">98% of Funds stored in Multi-sig Cold Wallets </h4>
                                </div>
                                <div class="ics__icon__item">
                                    <div class="ics__icon__item__icon">
                                        <img width="640" height="640"
                                            src="{{ asset('includes/uploads/2021/11/icon-multi-stage-security-protocol.svg') }}"
                                            class="attachment-large size-large" alt="" loading="lazy">
                                    </div>
                                    <h4 class="ics__icon__item__heading">Multi-stage Security Protocol</h4>
                                </div>
                                <div class="ics__icon__item">
                                    <div class="ics__icon__item__icon">
                                        <img width="640" height="640"
                                            src="{{ asset('includes/uploads/2021/11/icon-100-insurance-protection.svg') }}"
                                            class="attachment-large size-large" alt="" loading="lazy">
                                    </div>
                                    <h4 class="ics__icon__item__heading">Insurance Protection</h4>
                                </div>
                            </div>
                        </div>
                        <div class="wp-container-4 wp-block-buttons wow animate__fadeInUp"
                            style="visibility: visible; animation-name: fadeInUp;">
                            <div class="wp-block-button"><a class="wp-block-button__link"
                                    href="{{ url('article/1') }}">Know
                                    more</a></div>
                        </div>
                    </div>
                    <div class="wp-container-6 wp-block-column is-vertically-aligned-center ics__image">
                        <h2 class="has-text-align-center visible-mobile wow animate__fadeInUp animate__animated"
                            id="keep-your-crypto-secure" style="visibility: visible; animation-name: fadeInUp;">
                            Keep your Crypto Secure </h2>
                        <figure class="wp-block-image size-full wow animate__fadeInUp crypto-img"
                            style="visibility: visible; animation-name: fadeInUp;"><img loading="lazy" width="404"
                                height="391" src="{{ asset('includes/uploads/2021/11/Keep-your-Crypto-Secure-03.png') }}"
                                alt="" class="wp-image-1485"
                                srcset="{{ asset('includes/uploads/2021/11/Keep-your-Crypto-Secure-03.png 404w') }}, {{ asset('includes/uploads/2021/11/Keep-your-Crypto-Secure-03-300x290.png') }} 300w"
                                sizes="(max-width: 404px) 100vw, 404px"></figure>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="explore-our-products">
        <div class="container">
            <div class="explore-our-products-inner-section">
                <h2 class="Main_headings">Start trading in 3 easy steps</h2><br />
                <div class="explore-box-main-section">
                    <div class="explore-box">
                        <p class="explore-box-top">Set up your account in less than 2 minutes with our simplified KYC
                            process</p>
                        <div class="explore-box-bottom">
                            <div class="index_action-text">
                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3536 4.60355C13.5488 4.40829 13.5488 4.09171 13.3536 3.89645L10.1716 0.714465C9.97631 0.519203 9.65973 0.519203 9.46447 0.714465C9.2692 0.909727 9.2692 1.22631 9.46447 1.42157L12.2929 4.25L9.46447 7.07843C9.2692 7.27369 9.2692 7.59027 9.46447 7.78553C9.65973 7.9808 9.97631 7.9808 10.1716 7.78553L13.3536 4.60355ZM4.37114e-08 4.75L13 4.75L13 3.75L-4.37114e-08 3.75L4.37114e-08 4.75Z"
                                        fill="#FFBE40"></path>
                                </svg>
                                @auth
                                    <a href="{{ url('dashboard') }}">Dashboard</a>
                                @else
                                    <a href="{{ url('register') }}">Register</a>
                                @endauth

                            </div><img
                                src="{{ asset('Public/template/epsilon/img/redesign/homepage/spot_dark-image-3.png') }}">
                        </div>
                    </div>
                    <div class="explore-box">
                        <p class="explore-box-top">Add funds to your account using your preferred deposit method</p>
                        <div class="explore-box-bottom">
                            <div class="index_action-text">


                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3536 4.60355C13.5488 4.40829 13.5488 4.09171 13.3536 3.89645L10.1716 0.714465C9.97631 0.519203 9.65973 0.519203 9.46447 0.714465C9.2692 0.909727 9.2692 1.22631 9.46447 1.42157L12.2929 4.25L9.46447 7.07843C9.2692 7.27369 9.2692 7.59027 9.46447 7.78553C9.65973 7.9808 9.97631 7.9808 10.1716 7.78553L13.3536 4.60355ZM4.37114e-08 4.75L13 4.75L13 3.75L-4.37114e-08 3.75L4.37114e-08 4.75Z"
                                        fill="#FFBE40"></path>
                                </svg>
                                <a href="{{ url('easy-convert') }}">Deposit</a>
                            </div><img
                                src="{{ asset('Public/template/epsilon/img/redesign/homepage/spot_dark-image-2.png') }}">
                        </div>
                    </div>
                    <div class="explore-box">
                        <p class="explore-box-top">Buy & sell
                            100+ cryptos in second</p>
                        <div class="explore-box-bottom">
                            <div class="index_action-text">

                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.3536 4.60355C13.5488 4.40829 13.5488 4.09171 13.3536 3.89645L10.1716 0.714465C9.97631 0.519203 9.65973 0.519203 9.46447 0.714465C9.2692 0.909727 9.2692 1.22631 9.46447 1.42157L12.2929 4.25L9.46447 7.07843C9.2692 7.27369 9.2692 7.59027 9.46447 7.78553C9.65973 7.9808 9.97631 7.9808 10.1716 7.78553L13.3536 4.60355ZM4.37114e-08 4.75L13 4.75L13 3.75L-4.37114e-08 3.75L4.37114e-08 4.75Z"
                                        fill="#FFBE40"></path>
                                </svg>
                                <a href="{{ url('trade/spot') }}">Trade</a>
                            </div><img
                                src="{{ asset('Public/template/epsilon/img/redesign/homepage/spot_dark-image-1.png') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cg__sec wp-block-lazyblock-section lazyblock-section-ZfwMI8">
        <div class="block-section">
            <div class="container container--full ">
                <h2 class="has-text-align-center wow animate__fadeInUp" id="take-your-crypto-gameto-the-next-level"
                    style="visibility: visible; animation-name: fadeInUp;">Take
                    your crypto game<br>to the next level</h2>
                <div class="wp-container-14 wp-block-columns cg">
                    <div class="wp-container-12 wp-block-column cg__left">
                        <div class="wow animate__fadeInUp wp-block-lazyblock-card-big lazyblock-card-big-Z1tOiR7"
                            style="visibility: visible; animation-name: fadeInUp;">
                            <div class="ch__row light-gary">
                                <div class="ch__copy__tag ch__copy__tag--desktop">The Z BLOG</div>
                                <div class="ch__img ch__img--">
                                    <img width="378" height="207"
                                        src="{{ asset('includes/uploads/2022/01/stay-updated-on-latest_02.png') }}"
                                        class="attachment-large size-large" alt="" loading="lazy"
                                        srcset="{{ asset('includes/uploads/2022/01/stay-updated-on-latest_02.png') }} 378w, {{ asset('includes/uploads/2022/01/stay-updated-on-latest_02-300x164.png') }} 300w"
                                        sizes="(max-width: 378px) 100vw, 378px">
                                </div>
                                <div class="ch__copy">
                                    <div class="ch__copy__tag ch__copy__tag--mobile">The Z BLOG</div>
                                    <h5 class="ch__copy__heading">Stay updated on the latest crypto news and trends</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wp-container-13 wp-block-column cg__right">
                        <div class="wow animate__fadeInUp wp-block-lazyblock-card-horizontal lazyblock-card-horizontal-M3suo"
                            style="visibility: visible; animation-name: fadeInUp;">
                            <div class="ch__row light-lavender">
                                <div class="ch__img ch__img--bottom">
                                    <img width="180" height="200" src="{{ asset('includes/uploads/2021/09/card1.png') }}"
                                        class="attachment-large size-large" alt="" loading="lazy">
                                </div>
                                <div class="ch__copy">
                                    <div class="ch__copy__tag">LEARN</div>
                                    <h5 class="ch__copy__heading">Specialised courses for you to up your crypto game</h5>
                                </div>
                            </div>
                        </div>
                        <div class="wow animate__fadeInUp wp-block-lazyblock-card-horizontal lazyblock-card-horizontal-Z8C7yF"
                            style="visibility: visible; animation-name: fadeInUp;">
                            <div class="ch__row pink">
                                <div class="ch__img ch__img--middle">
                                    <img width="180" height="200" src="{{ asset('includes/uploads/2021/09/card2.png') }}"
                                        class="attachment-large size-large" alt="" loading="lazy">
                                </div>
                                <div class="ch__copy">
                                    <div class="ch__copy__tag">MARKETS</div>
                                    <h5 class="ch__copy__heading">Analyse markets in real-time with our simple yet powerful
                                        dashboard </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .explore-our-products {
            padding-top: 108px;
            padding-bottom: 100px;
        }

        .explore-box-main-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-gap: 25px;
        }

        .explore-box {
            background-color: rgba(24, 24, 25, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-right: 10px;
            padding: 80px 0 0 32px;
        }

        .explore-box p {
            font-size: 27px;
            line-height: 38px;
            padding-right: 18px;
            text-align: left;
            margin-bottom: 1em;
        }

        .explore-box-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .explore-box p span {
            font-weight: 800;
        }

        .explore-box-bottom img {
            width: 112px;
            height: 112px;
        }

        .index_action-text {
            display: flex;
            align-items: center;
            padding-bottom: 30px;
            cursor: pointer;
        }

        .index_action-text svg {
            height: 10px;
            margin-right: 9px;
            display: flex;
            align-items: center;
            margin-top: 3px;
        }

        .index_action-text a {
            color: #FFBE40;
            font-size: 20px;
            font-weight: 700;
            line-height: 20px;
        }

        .explore-our-products .Main_headings {
            margin-bottom: 55px;
        }
    </style>



    <section class="css-bc8mf0">
        <div class="MuiContainer-root MuiContainer-maxWidthLg css-ioxqx">
            <div class="css-ixjpqj">
                <h2 class="MuiTypography-root MuiTypography-h2 css-11j9rw4">Become a member of a
                    global community</h2>
                <h6 class="MuiTypography-root MuiTypography-subtitle1 css-3pebqp">More than six
                    million crypto enthusiasts around the world have accessed our award-winning
                    ecosystem.</h6>
            </div>
            <div class="css-10iytbc">
                <div class="css-gybc1s"><img alt="member-world-map-icon" loading="lazy" width="632" height="406"
                        class="css-6i4ykg"
                        src="{{ asset('Public/template/epsilon/img/new/member-world-map.2484dbb7.svg') }}"
                        style="color: transparent;"><img alt="member-world-map-hover-icon" loading="lazy" width="632"
                        height="406" decoding="async" data-nimg="1" class="member-world-map-hover-icon css-z227pk"
                        src="{{ asset('Public/template/epsilon/img/new/member-world-map-hover.36771a4b.svg') }}"
                        style="color: transparent;"></div>
                <div class="css-17bew9p">
                    <div class="css-12mvi98"><img alt="member-rating-icon" loading="lazy" width="163" height="32"
                            decoding="async" data-nimg="1" class="css-15fm97m"
                            src="{{ asset('Public/template/epsilon/img/new/member-rating.48a998f9.svg') }}"
                            style="color: transparent;">
                        <p class="MuiTypography-root MuiTypography-h4 css-13zz80p">The trading fees are competitive, and
                            the platform offers a wide range of cryptocurrencies, allowing me to diversify my portfolio
                            effortlessly. I also appreciate the advanced security measures they have in place, giving me
                            peace of mind when trading or holding assets.</p>
                        <p class="MuiTypography-root MuiTypography-h4 css-1v9uqpt">— Mark </p>
                        @auth
                            <a class="MuiButtonBase-root MuiButton-root MuiButton-contained MuiButton-containedPrimary MuiButton-sizeLarge MuiButton-containedSizeLarge MuiButton-root MuiButton-contained MuiButton-containedPrimary MuiButton-sizeLarge MuiButton-containedSizeLarge homepage-member-button css-hoxete"
                                tabindex="0" href="{{ url('dashboard') }}">Dashboard<span
                                    class="MuiTouchRipple-root css-w0pj6f"></span></a>
                        @else
                            <a class="MuiButtonBase-root MuiButton-root MuiButton-contained MuiButton-containedPrimary MuiButton-sizeLarge MuiButton-containedSizeLarge MuiButton-root MuiButton-contained MuiButton-containedPrimary MuiButton-sizeLarge MuiButton-containedSizeLarge homepage-member-button css-hoxete"
                                tabindex="0" href="{{ url('login') }}">Join
                                {{ \App\Models\Setting::get('site_name', 'Website Name') }}<span
                                    class="MuiTouchRipple-root css-w0pj6f"></span></a>
                        @endauth

                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="main-block__advantage main-block">
        <div class="wrap">

            <div class="h1">
                Advantages of Using {{ \App\Models\Setting::get('site_name', 'Website Name') }} to Buy and Sell Crypto
            </div>
            <p>
                {{ \App\Models\Setting::get('site_name', 'Website Name') }} is an easy-to-use platform that offers a
                simplified and superior
                crypto trading experience that is unmatched by comparable platforms. As the
                saying goes, time is money. {{ \App\Models\Setting::get('site_name', 'Website Name') }} provides users fast
                bank withdrawals
                without passing on the costs to the end-user. American users have the added
                advantage of using ACH for instant deposits so they can buy cryptocurrency
                faster.
            </p>
            <div class="h1">
                If You’re New to Bitcoin Exchange - {{ \App\Models\Setting::get('site_name', 'Website Name') }} is Here to
                Help!
            </div>
            <p>
                The process of using a Bitcoin Exchange Platform for the first time can be
                intimidating, especially when you’re trying to balance public addresses,
                different cryptocurrencies, and the concept of the blockchain itself. Crypto
                Exchange makes the process simple by providing a Feature-packed Trading API and
                a user interface that is easy to use for beginners and experienced
                cryptocurrency traders. Cryptocurrency exchange platforms are home to lots of
                trading and other transactions, and in order to have smooth transactions, Crypto
                Exchange provides a 24/7 support center staffed with experienced customer
                service teams. If it’s the first time a user is looking to buy Bitcoin or sell
                Crypto, {{ \App\Models\Setting::get('site_name', 'Website Name') }}’s learning center has abundant
                information on crypto
                trades.
            </p>
            <div class="h1">
                Don’t just Trade Bitcoin!
            </div>
            <p>
                Bitcoin is just one of the many unique digital currencies that can revolutionize
                the world of payments. {{ \App\Models\Setting::get('site_name', 'Website Name') }}’s API leverages several
                easy-to-use
                features that bring the most functionality to the crypto trading platform. Users
                can also buy crypto pairs, including USD to Ethereum, XRP, and Litecoin, all
                under one roof.
            </p>
            <div class="h1">
                Trade Crypto Everywhere and Anywhere!
            </div>
            <p>
                {{ \App\Models\Setting::get('site_name', 'Website Name') }} allows users to take advantage of low fees from
                anywhere in the
                world. It is not uncommon for platforms to restrict availability based on
                region. Fortunately, {{ \App\Models\Setting::get('site_name', 'Website Name') }} offers extensive services
                to users
                regardless of their point of transaction. With an optimized web and mobile
                interface, users can pay anyone in the world with just their cryptocurrency
                address.
            </p>

        </div>
    </div>
    <!-- Blog Start -->
    <section class="blog">
        <div class="container">

            <h2 class="section-title Main_headings text-center">{{ \App\Models\Setting::get('site_name', 'Website Name') }}
                Blog</h2>

            <div class="blog-single-item arrow-black">
                <div>
                    <div class="blog-grid">
                        <a href="{{ url('article/27') }}" class="card">
                            <figure>

                                <img class="img-fluid" src="{{ asset('Upload/article/6787e4782c20a.jpg') }}"
                                    alt="Exploring NFTs: Beyond Digital Art and into the Future">
                            </figure>
                            <div class="card-body">
                                <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                                <h4 class="card-title">Exploring NFTs: Beyond Digital Art and into the Future...</h4>
                                <!-- <p class="card-text">
        Exploring NFTs: Beyond Digital Art and into the Future


        Non-Fungible Tokens (NFTs) have evolved ...</p> -->
                                <!--span class="date">2025-01-15 16:38:28</span-->
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="blog-grid">
                        <a href="{{ url('article/26') }}" class="card">
                            <figure>

                                <img class="img-fluid" src="{{ asset('Upload/article/6787e33cb17aa.jpg') }}"
                                    alt="Decentralized Finance (DeFi): Revolutionizing the Financial Landscape">
                            </figure>
                            <div class="card-body">
                                <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                                <h4 class="card-title">Decentralized Finance (DeFi): Revolutionizing the Financial
                                    Landscape...</h4>
                                <!-- <p class="card-text">
        Decentralized Finance (DeFi): Revolutionizing the Financial Landscape


        Decentralized Finance (De...</p> -->
                                <!--span class="date">2025-01-15 16:34:53</span-->
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="blog-grid">
                        <a href="{{ url('article/25') }}" class="card">
                            <figure>

                                <img class="img-fluid" src="{{ asset('Upload/article/6787e179ee977.jpg') }}"
                                    alt="Understanding Web3">
                            </figure>
                            <div class="card-body">
                                <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                                <h4 class="card-title">Understanding Web3...</h4>
                                <!-- <p class="card-text">
        Understanding Web3: The Next Evolution of the Internet


        Web3 is the next phase of the internet, ...</p> -->
                                <!--span class="date">2025-01-15 16:26:38</span-->
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="blog-grid">
                        <a href="{{ url('article/24') }}" class="card">
                            <figure>

                                <img class="img-fluid" src="{{ asset('Upload/article/6787dfe23969d.jpg') }}"
                                    alt="The Rise of Decentralized Exchanges (DEX): A New Era in Cryptocurrency Trading">
                            </figure>
                            <div class="card-body">
                                <!--div class="blog-categorie"><span>Bitcoin</span><span>News</span></div-->
                                <h4 class="card-title">The Rise of Decentralized Exchanges (DEX): A New Era in
                                    Cryptocurrency Trading...</h4>
                                <!-- <p class="card-text">
        The Rise of Decentralized Exchanges (DEX): A New Era in Cryptocurrency Trading


        Cryptocurrency h...</p> -->
                                <!--span class="date">2025-01-15 16:20:09</span-->
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Guide & Blog End -->


    <div class="cta-trading-now wow animate__fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
        <div class="block-section">
            <div class="container container--medium">
                <h2 class="has-text-align-center">Start <span class="blue">Trading</span> Now</h2>
                <!-- <form class="trade-form" id="trade-form">
              <input class="phone" id="fphone" type="text" placeholder="Enter Mobile No." autocomplete="off">
              <button type="submit" class=""><img src="assets/assets/images/icons/btn-arrow.svg" alt=""></button>
            </form> -->
                <div class="wp-block-lazyblock-country-based-content lazyblock-country-based-content-ZVukOf">
                    <div class="block-section">
                        <div class="container container--medium ">
                            <div class="cta-section wow animate__fadeInUp wp-block-lazyblock-cta lazyblock-cta-1FASAQ"
                                style="visibility: visible; animation-name: fadeInUp;">
                                <div class="cta">
                                    <div class="container container--medium">
                                        <div class="cta__row">
                                            @auth
                                                <h3 class="cta__heading">
                                                    Go To Dashboard to Continue Trading<strong>Us</strong> </h3>
                                                <div class="cta__btn">
                                                    <a class="btn btn--fill" target="_blank"
                                                        href="{{ url('dashboard') }}">Dashboard</a>
                                                </div>
                                            @else
                                                <h3 class="cta__heading">
                                                    Register now to begin trading with <strong>Us</strong> </h3>
                                                <div class="cta__btn">
                                                    <a class="btn btn--fill" target="_blank" href="{{ url('register') }}">Signup
                                                        Now</a>
                                                </div>
                                            @endauth

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const markets = @json($markets);

        function switchMarket(symbol) {
            let tableBody = document.getElementById("market-table-body");
            tableBody.innerHTML = "";

            markets[symbol].forEach(pair => {
                tableBody.innerHTML += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${pair.base_currency.logo_url}" width="30" class="rounded-circle me-2">
                        <div>
                            <strong>${pair.base_currency.symbol}/${pair.quote_currency.symbol}</strong>
                            <div class="small text-muted">${pair.base_currency.name}</div>
                        </div>
                    </div>
                </td>

                <td>${Number(pair.base_currency.current_price).toFixed(6)}</td>
                <td>${Number(pair.base_currency.current_price).toFixed(6)}</td>
                <td>${Number(pair.base_currency.current_price).toFixed(6)}</td>

                <td>${Number(pair.base_currency.volume_24h).toLocaleString()}</td>
                <td>${Number(pair.base_currency.market_cap).toLocaleString()}</td>

                <td>
                    <span style="color:${pair.base_currency.price_change_24h >= 0 ? 'green' : 'red'}">
                        ${Number(pair.base_currency.price_change_24h).toFixed(2)}%
                    </span>
                </td>

                <td><a href="/trade/${pair.id}" class="btn-2">Trade</a></td>
            </tr>`;
            });
        }
    </script>

@endsection