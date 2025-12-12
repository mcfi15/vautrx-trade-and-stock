<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title', 'Vautrx | Vautrx | Secure &amp; Fast Cryptocurrency Exchange for Bitcoin, Ethereum &amp; Altcoins')
    </title>

    <meta charset="utf-8">
    <meta name="Keywords"
        content="{{ \App\Models\Setting::get('site_name', 'Website Name') }} | {{ \App\Models\Setting::get('site_name', 'Website Name') }}, {{ \App\Models\Setting::get('site_name', 'Website Name') }}.com, cryptocurrency exchange, crypto trading, buy Bitcoin, sell Ethereum, altcoin exchange, secure crypto platform, low-fee crypto trading, Bitcoin exchange, Ethereum trading, blockchain trading, digital asset exchange, crypto market, DeFi trading, instant crypto swaps, crypto wallet integration">
    <meta name="Description"
        content="{{ \App\Models\Setting::get('site_name', 'Website Name') }} | Trade securely and efficiently on {{ \App\Models\Setting::get('site_name', 'Website Name') }}, the leading cryptocurrency exchange platform. Buy, sell, and swap Bitcoin, Ethereum, and altcoins with low fees, advanced security, and real-time market data.">
    <meta name="author" content="static">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="copyright" content="{{ \App\Models\Setting::get('site_name', 'Website Name') }}">
    <link rel="shortcut icon" href="favicon.ico" />
    <link href="{{ asset(\App\Models\Setting::get('site_favicon')) }}" rel="icon" type="image/x-icon">


    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/style.css?v=1') }}">
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/add-style.css?v=2') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/bootstrap.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/6pp9tx5LPOzE.css?v=6') }}">
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/8pHr7IdzNTAX.css') }}">

    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/Fx80kkK9MFjp.css') }}">
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/RvcY6nkUjvCA.css') }}">

    <link href="{{ asset('Public/template/epsilon/css/style.css?v=3') }}" rel="stylesheet">

    <link href="{{ asset('Public/template/epsilon/css/custom.css?v=4') }}" rel="stylesheet">

    <link href="Public/template/common/common.css?v=5') }}" rel="stylesheet">
    <style>
        .live-market {
            padding: 20px;
            background-color: #1e1e1e;
            margin: 20px;
            border-radius: 8px;
        }

        .live-market h2 {
            text-align: center;
            color: #00d084;
            margin-bottom: 20px;
        }

        .market-table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }

        .market-table th,
        .market-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #333;
        }

        .market-table th {
            background-color: #333;
        }

        /*change this color from black to white if you still want to use the dark mode toggle*/
        .navbar-expand-lg .navbar-nav .nav-link,
        #dark .white-bg {
            color: white !important;
        }
    </style>

    <style>
        .graph-main-section {
            background-color: #131723;
            padding: 10px 0px;
            margin: 60px 0 20px;
        }

        .graph-item .graph-heading .rate {
            margin-top: -7px !important;
        }

        .read-more.ml-auto {
            position: absolute;
            right: 0;
            min-width: auto;
        }

        .container {
            width: 100%;
            max-width: 1260px;
            padding: 0 30px;
            margin: 0 auto;
        }

        #dark .home-markets .nav-pills .nav-link,
        #dark .swap-form-inner,
        #dark .swap-from label,
        #dark .announcements-block,
        #dark thead th,
        #dark .markets-pair-list .nav-link {
            color: rgba(197, 197, 197, 1);
            background: transparent;
            box-shadow: none;
            font-size: 18px;
        }

        #dark .table thead th {
            font-size: 14px;
            color: rgba(197, 197, 197, 1);
        }

        div#market-tab-box {
            border-bottom: 1px solid rgba(78, 78, 78, 1);
        }

        #dark,
        #dark .modal-content,
        #dark section.guide,
        #dark section.apps,
        #dark section.blog,
        #dark .swap-form-wrapper .swap-from,
        #dark .swap-form-inner,
        #dark .announcements-block,
        #dark .easytrade-form-wrapper .easytrade-form-inner {
            border: 0;
        }

        .announcements-block .icon {
            background-color: transparent;
            color: inherit;
        }

        i.ion.ion-md-megaphone:before {
            color: rgba(197, 197, 197, 1);
        }

        .read-more.ml-auto a {
            color: #1e90ff !important;
        }

        .slick-dots li button:before {
            color: #1e90ff;
        }

        #dark .slip-limit-container .limit-input,
        #dark .slip-limit-container .limit-button,
        #dark .card-header,
        #dark .card,
        #dark .home-markets>.tab-content {
            background-color: transparent;
            color: #fff;
            border: 0;
            box-shadow: none;
        }

        #dark .home-markets .nav.nav-pills .nav-link.current {
            background-color: transparent;
            border-bottom: 2px solid #1e90ff;
            color: #fff !important;
            box-shadow: none;
        }

        #dark .coin-pnl-modal-summary ul li,
        #dark .table td,
        #dark .table th {
            border: 0;
        }

        #dark #daily-top-winners .coin-list .btn-2,
        .codono-distribution-table.table .btn-2,
        .p2p-list-table .btn-2 {
            background-color: transparent;
            border-color: #1e90ff;
            color: #fff !important;
            border: 1px solid rgba(78, 78, 78, 1);
            border-radius: 6px;
        }
    </style>

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
            color: rgba(13, 13, 13, 1) !important;
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

        body {
            display: none;
        }

        /*#dark .h1, #dark .h2, #dark .h3, #dark .h4, #dark .h5, #dark .h6, #dark h1, #dark h2, #dark h3, #dark h4, #dark h5, #dark h6 {*/
        /*    color: white !important;*/
        /*}*/


        /*.main-block__advantage.main-block .wrap {*/

        /*}*/
        /*.btn {*/
        /*color: #212529 !important;*/
        /*}*/


        /*body {*/
        /*display: inline-table !important;*/
        /*}*/

        /*.dropdown-body .dropdown-item .content {*/
        /*    padding: 0px !important;*/
        /*}*/


        .home-markets>.tab-content {
            display: block;
        }

        header {
            background: white;
            color: white;
            border-bottom: 1px solid #ccc !important;
        }

        /*.text-white {*/
        /*    color: black !important;*/
        /*}*/

        /*header .navbar-nav.mr-auto .dropdown-menu, .dropdown-body {*/
        /*    background: white !important;*/
        /*}*/

        /*header .navbar-nav.mr-auto .dropdown-menu {*/
        /*    border: 1px solid #d9d9d9;*/
        /*}*/

        /*.wp-block-button__link {*/
        /*    color: white !important;*/
        /*}*/
        .explore-box img,
        .blog-grid .card img,
        .blog-grid img {
            filter: hue-rotate(200deg);
        }

        /*.yellow-bg {*/
        /*    background-color: #2d78ff !important;*/
        /*    color: white !important;*/
        /*}*/

        /*.css-hoxete{*/
        /*    background: #1e90ff;*/
        /* color: white !important; */
        /*    font-weight: 500;*/
        /*}*/

        /*.cta__row {*/
        /*    background: transparent;*/
        /*    border: 1px solid #80808038;*/
        /*    box-shadow: 1px 2px 4px -3px rgb(0 0 0 / 50%);*/
        /*}*/

        /*.blue {*/
        /*    color: #1e90ff;*/
        /*}*/


        @media screen and (min-width: 700px) {
            .banner-section .index_banner-image {
                width: 400px;
                height: 440px;
                object-fit: contain;
            }
        }


        /*.page-top-banner h1 {*/
        /*    color: white !important;*/
        /*}*/

        /*section.swap-page {*/
        /*    background: white !important;*/
        /*}*/

        /*.filter {*/
        /*    background: #080f24;*/
        /*    padding: 20px 0px;*/
        /*}*/
        /*.text-size-mini.text-muted.ml-3.text-white {*/
        /*    color: white !important;*/
        /*}*/
        /*.form-wrapper, .dark-bg {*/
        /*    background-color: white !important;*/
        /*}*/


        form .tab-content {
            display: block;
        }

        /*.nk-wrap .form-control, .tab-content .form-control {*/
        /*    background: transparent;*/
        /*    border: 1px solid black;*/
        /*    padding-left: 8px;*/
        /*}*/

        /*.tradingview-widget-container, .order-book, .market-pairs, .market-history, .market-order {*/
        /*    background: #131722;*/
        /*}*/

        /*.container-fluid.mtb15.no-fluid .white-bg, .container-fluid.mtb15.no-fluid, .market-pairs input, .market-pairs .nav, .market-trade .nav {*/
        /*    background-color: #131722 !important;*/
        /*    color: white !important;*/
        /*}*/

        /*.market-trade .btn {*/
        /*    color: white !important;*/
        /*}*/

        .market-trade .tab-content,
        #DealRecordTable,
        .market-order .tab-content {
            display: block;
        }

        /* .market-order .tab-content tr:hover td {*/
        /*     color: black !important;*/
        /* }*/

        /* .nk-wrap .form-wrapper .other-link a {*/
        /*         color: #5a84e3 !important;*/
        /*    text-decoration: underline;*/
        /* }*/

        .tab-content {
            display: block !important;
        }

        #dark .h1,
        #dark .h2,
        #dark .h3,
        #dark .h4,
        #dark .h5,
        #dark .h6,
        #dark h1,
        #dark h2,
        #dark h3,
        #dark h4,
        #dark h5,
        #dark h6 {
            color: white !important;
        }

        body {
            display: block;
            height: auto !important;
        }

        #dark .stake-page .stake-table .btn {
            color: white !important;
        }

        #dark .filter-option-inner-inner {
            color: white;
        }

        #dark .tab-pane,
        #dark .tradingview-widget-container {
            background: #0b0e11 !important;
        }

        #light form.needs-validation .tab-pane {
            background: #0b0e11 !important;
        }

        form.needs-validation .other-link a {
            color: #1e90ff !important;
        }

        form.needs-validation .nav-pills .nav-link[aria-selected=true].active span {
            color: black !important;
        }

        form.needs-validation .form-control {
            color: white !important;
            padding: 10px;
        }


        .form-wrapper .nav-pills .nav-link span {
            color: #1e90ff !important;
        }

        .form-wrapper .nav-pills .active span {
            color: black !important;
        }



        #dark .cta-trading-now {
            background-color: transparent !important;
        }

        #dark .ch__copy__heading {
            color: #111111 !important;
        }

        header {
            background-color: var(--dark-bg-color) !important;
        }

        #dark .wp-block-lazyblock-icon-grid .icon__grid {
            background: #000000 !important;
            color: white;
        }

        #light .wp-block-lazyblock-icon-grid .icon__grid {
            background: #f6f6f6 !important;
            color: white;
        }

        #dark .wp-block-lazyblock-icon-grid .icon__item__heading {
            color: white !important;
        }

        .blog-page .page-top-banner h1,
        .stake-page .page-top-banner h1 {
            color: white !important;
        }

        .cta__heading {
            color: white !important;
        }

        .dropdown-body .dropdown-item .content {
            padding: 0px !important;
        }

        #light header .navbar-nav.mr-auto .dropdown-menu,
        #light .dropdown-body {
            background: white !important;
            border-color: #e7e7e7;
        }

        #light .dropdown-body .dropdown-item .content {
            color: black !important;
        }

        body,
        body,
        button,
        input,
        select,
        optgroup,
        textarea,
        span,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        #light span,
        #dark span {
            font-family: "HarmonyOS_Sans_Regular", sans-serif !important;
        }

        /*.cex-ui-icon-button-secondary .cex-ui-icon *, .cex-ui-icon-button-secondary:hover:not([disabled]) .cex-ui-icon * {*/
        /*    fill: none !important;*/
        /*}*/

        .btn-4 {
            border: 1px solid transparent;
        }

        .btn-4:hover:after {
            left: 0px !important;
            background: #242424 !important;
        }

        footer h2 {
            color: white !important;
        }

        main.blog-page img.img-fluid,
        main.blog-page .articletext img {
            width: 50%;
        }

        main.blog-page .articletext h2 {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        @media screen and (max-width: 900px) {

            main.blog-page img.img-fluid,
            main.blog-page .articletext img {
                width: 100% !important;
            }
        }

        .tradingview-widget-container {
            padding: 0px !important;
        }

        .text-colo {
            color: #1e90ff;
        }
    </style>

    <script src="{{ asset('Public/template/epsilon/js/jquery-3.4.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Public/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Public/static/js/jquery.cookies.2.2.0.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Public/static/js/layer.js') }}"></script>
    <script src="{{ asset('Public/cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js') }}"
        type="text/javascript"></script>

    <script>

        $('body').hide();
        $(document).ready(function () {
            $('body').addClass('p-home');
            // $('body').attr('id', 'light');

            $('body').show();
        })
    </script>

    @stack('styles')
</head>




<body id="dark">
    <!-- Header Start -->
    @include('layouts.inc.navbar')
    <!-- Header ends -->



    <main class="wrapper grey-bg"></main>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"version":"2024.11.0","token":"e9cc9f2ea4dd45ae89519d10063abb12","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'979578cdb9897740',t:'MTc1NjkwNDg2NA=='};var a=document.createElement('script');a.src='cdn-cgi/challenge-platform/h/b/scripts/jsd/4710d66e8fda/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>

@yield('content')





<!-- Footer -->
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="footer-blocks">
                    <div class="block">
                        <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Cryptimize" class="img-fluid m-b-15"
                            width="152px">
                        <h4>Trade securely and efficiently on {{ \App\Models\Setting::get('site_name', 'Website Name') }}, the leading cryptocurrency exchange platform. Buy, sell,
                    and swap Bitcoin, Ethereum, and altcoins with low fees, advanced security, and real-time market
                    data.</h4>
                    </div>
                    <div class="block">
                        <h3>Trade</h3>

                        <ul>
                            <li>
                                <a class="slash" href="{{ url('/trade/spot') }}"> Spot </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('/easy-trade') }}">
                                    Easy Convert </a>
                            </li>
                        </ul>
                    </div>
                    <div class="block">
                        <h3>Earn</h3>

                        <ul>
                            <li>
                                <a class="noslash" href="{{ url('/staking') }}">
                                    Staking </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('/airdrops') }}">
                                    Airdrop </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('/faucets') }}">
                                    Faucet </a>
                            </li>
                        </ul>
                    </div>
                    <div class="block">
                        <h3>Platform</h3>

                        <ul>
                            <li>
                                <a class="noslash" href="{{ url('/about') }}">
                                    About Us </a>
                            </li>
                            {{-- <li>
                                <a class="noslash" href="/Dex">
                                    Decentralized </a>
                            </li> --}}
                            <li>
                                <a class="noslash" href="{{ url('/markets') }}">
                                    Market </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('/pool') }}">
                                    Mining </a>
                            </li>
                        </ul>
                    </div>
                    <div class="block">
                        <h3>Terms</h3>

                        <ul>
                            <li>
                                <a class="noslash" href="{{ url('article/1') }}">
                                    User Agreement </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('article/2') }}">
                                    Staking Terms </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('article/3') }}">
                                    Privacy Policy </a>
                            </li>
                            <li>
                                <a class="noslash" href="{{ url('article/4') }}">
                                    Cookie Policy </a>
                            </li>
                        </ul>
                    </div>
                    <div class="block">
                        <!--h3>Community</h3-->
                        <h3>Contact</h3>
                        <ul>
                            <li>
                                <a href="mailto:support@vautrx.com"
                                    style="white-space: nowrap; margin-bottom: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <path fill="#fff"
                                            d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                                    </svg>
                                    support@vautrx.com </a>
                            </li>
                            <li>
                                <a href="mailto:info@vautrx.com" style="white-space: nowrap; margin-bottom: 5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <path fill="#fff"
                                            d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                                    </svg>
                                    info@vautrx.com </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row f-s-12 m-t-30">
            <div class="col-12 m-b-15">
                <div class="col-12 col-sm-4 copyright">
                    Copyright 2025 Cryptocurrency Exchange Software </div>
            </div>
        </div>

    </div>
</footer>
<section>
    <div class="panel-group pt-3 pb-3" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading20">
                <p class="panel-title" style="margin: 0;">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-20"
                        aria-expanded="true" aria-controls="collapseOne">
                        Trade </a>
                </p>
            </div>
            <div id="collapse-20" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading20">
                <ul class="link-wrapper">
                    <li>
                        <a href="{{ url('/trade/spot') }}">
                            Spot </a>
                    </li>
                    <li>
                        <a href="{{ url('/easy-trade') }}">Easy Convert</a> </a>
                    </li>
                </ul>
            </div>
            <ul>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading31">
                <p class="panel-title" style="margin: 0;">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-31"
                        aria-expanded="true" aria-controls="collapseOne">
                        Earn </a>
                </p>
            </div>
            <div id="collapse-31" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading31">
                <ul class="link-wrapper">
                    <li>
                        <a href="{{ url('/staking') }}">
                            Staking </a>
                    </li>
                    <li>
                        <a href="{{ url('/airdrops') }}">
                            Airdrop </a>
                    </li>
                    <li>
                        <a href="{{ url('/faucets') }}">
                            Faucet </a>
                    </li>
                </ul>
            </div>
            <ul>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading24">
                <p class="panel-title" style="margin: 0;">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-24"
                        aria-expanded="true" aria-controls="collapseOne">
                        Platform </a>
                </p>
            </div>
            <div id="collapse-24" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading24">
                <ul class="link-wrapper">
                    <li>
                        <a href="{{ url('/about') }}">
                            About Us </a>
                    </li>
                    {{-- <li>
                        <a href="/Dex">
                            Decentralized </a>
                    </li> --}}
                    <li>
                        <a href="{{ url('/markets') }}">
                            Market </a>
                    </li>
                    <li>
                        <a href="{{ url('/pool') }}">
                            Mining </a>
                    </li>
                </ul>
            </div>
            <ul>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="heading32">
                <p class="panel-title" style="margin: 0;">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-32"
                        aria-expanded="true" aria-controls="collapseOne">
                        Terms </a>
                </p>
            </div>
            <div id="collapse-32" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading32">
                <ul class="link-wrapper">
                    <li>
                        <a href="{{ url('article/1') }}">
                            User Agreement </a>
                    </li>
                    <li>
                        <a href="{{ url('article/2') }}">
                            Staking Terms </a>
                    </li>
                    <li>
                        <a href="{{ url('article/3') }}">
                            Privacy Policy </a>
                    </li>
                    <li>
                        <a href="{{ url('article/4') }}">
                            Cookie Policy </a>
                    </li>
                </ul>
            </div>
            <ul>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <p class="panel-title" style="margin: 0;">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                        href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Community </a>
                </p>
            </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <ul class="link-wrapper">
                    <li>
                        <a href="mailto:support@vautrx.com" style="white-space: nowrap; margin-bottom: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path fill="#fff"
                                    d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                            </svg>
                            support@vautrx.com </a>
                    </li>
                    <li>
                        <a href="mailto:info@vautrx.com" style="white-space: nowrap; margin-bottom: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                <path fill="#fff"
                                    d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                            </svg>
                            info@vautrx.com </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel-group">
        <ul class="footerul link-wrapper d-flex justify-content-center w-100 align-items-center">
            <li class="text-white">Copyright 2025 Cryptocurrency Exchange Software</li>
            <li class="ml-2 text-white">All rights reserved.</li>
        </ul>

    </div>
</section>
<div class="gtranslate_wrapper" id="gt-wrapper-16411826"></div>

<style>
    .panel-group ul.link-wrapper li a {
        color: rgba(197, 197, 197, 1);
    }

    @media(max-width: 425px) {
        .footerul {
            display: block !important;
            text-align: center;
            font-size: 12px;
            padding: 4px 8px 8px 8px !important;
        }

        .footerul li:last-child {
            margin: 0 !important;
        }

        #accordion.panel-group ul li {
            margin-bottom: 16px;
        }
    }
</style>
<style>
    @media only screen and (max-width: 991px) {
        footer {
            display: none;
        }

        .panel-group {
            display: block !important;
        }
    }

    .panel-group {
        display: none;
        background-color: var(--box-bg-dark)
    }

    .link-wrapper {
        background-color: var(--box-bg-dark) color: gray;
        padding: 20px;
    }

    .link-wrapper ul li a {
        font-weight: 600;
        font-size: 14px;
    }

    .panel-default>.panel-heading {

        color: #333;
        background-color: var(--box-bg-dark);
        /* border-color: #e4e5e7; */
        padding: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

    }

    .panel-default>.panel-heading a {
        display: block;
        padding: 10px 15px;
        color: white;
    }

    .panel-default>.panel-heading a:after {
        content: "";
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        float: right;
        transition: transform .25s linear;
        -webkit-transition: -webkit-transform .25s linear;
    }

    .panel-default>.panel-heading a[aria-expanded="true"] {
        color: white;
    }

    .panel-default>.panel-heading a[aria-expanded="true"]:after {
        content: "\2212";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-default>.panel-heading a[aria-expanded="false"]:after {
        content: "\002b";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }
</style>


{{-- <style>
    footer {
        display: block !important;
    }

    footer a {
        opacity: 1 !important;
    }

    .subscription-form-wrapper {
        padding-bottom: 20px;
    }

    .zsiq-float.zsiq-flexM {
        margin-bottom: 15px;
    }
</style> --}}

<script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') }}"></script>

<script src="//code.tidio.co/uqbpiq2aat8nk1xcocsltwkbetan75qy.js" async></script>


{{-- <style>
    @media only screen and (max-width: 991px) {
        footer {
            display: none;
        }

        .panel-group {
            display: block !important;
        }
    }

    .panel-group {
        display: none;
        background-color: var(--box-bg-dark)
    }

    .link-wrapper {
        background-color: var(--box-bg-dark) color: gray;
        padding: 20px;
    }

    .link-wrapper ul li a {
        font-weight: 600;
        font-size: 14px;
    }

    .panel-default>.panel-heading {

        color: #333;
        background-color: var(--box-bg-dark);
        /* border-color: #e4e5e7; */
        padding: 0;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

    }

    .panel-default>.panel-heading a {
        display: block;
        padding: 10px 15px;
        color: white;
    }

    .panel-default>.panel-heading a:after {
        content: "";
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        float: right;
        transition: transform .25s linear;
        -webkit-transition: -webkit-transform .25s linear;
    }

    .panel-default>.panel-heading a[aria-expanded="true"] {
        color: white;
    }

    .panel-default>.panel-heading a[aria-expanded="true"]:after {
        content: "\2212";
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .panel-default>.panel-heading a[aria-expanded="false"]:after {
        content: "\002b";
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }
</style> --}}



<script>
    function choose_lang(lang) {
        $.cookies.set("lang", lang);
        window.location.reload();
    }
</script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Chart.js -->
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/popper.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/bootstrap-selec.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/locales/bootstrap-datepicker.tr.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/jquery-birthday-picker.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/amcharts-core.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/amcharts.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/apexcharts.min.js') }}"></script>


<script src="{{ asset('Public/template/epsilon/js/jquery.peity.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/slick.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/icheck.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('Public/assets/js/js.cookie.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/jquery.mCustomScrollbar.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/intlTelInput-jquery.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/utils.js') }}"></script>
<script src="{{ asset('Public/template/epsilon/js/custom.js?v=1') }}"></script>
<script>

</script>
<!-- Scripts End -->

<script type="text/javascript" id="gt_widget_script_16411826-js-before">
    /* <![CDATA[ */
    window.gtranslateSettings = /* document.write */ window.gtranslateSettings || {}; window.gtranslateSettings['16411826'] = { "default_language": "en", "languages": ["af", "sq", "am", "ar", "hy", "az", "eu", "be", "bn", "bs", "bg", "ca", "ceb", "ny", "zh-CN", "zh-TW", "co", "hr", "cs", "da", "nl", "en", "eo", "et", "tl", "fi", "fr", "fy", "gl", "ka", "de", "el", "gu", "ht", "ha", "haw", "iw", "hi", "hmn", "hu", "is", "ig", "id", "ga", "it", "ja", "jw", "kn", "kk", "km", "ko", "ku", "ky", "lo", "la", "lv", "lt", "lb", "mk", "mg", "ms", "ml", "mt", "mi", "mr", "mn", "my", "ne", "no", "ps", "fa", "pl", "pt", "pa", "ro", "ru", "sm", "gd", "sr", "st", "sn", "sd", "si", "sk", "sl", "so", "es", "su", "sw", "sv", "tg", "ta", "te", "th", "tr", "uk", "ur", "uz", "vi", "cy", "xh", "yi", "yo", "zu"], "url_structure": "none", "flag_style": "3d", "flag_size": 16, "wrapper_selector": "#gt-wrapper-16411826", "alt_flags": [], "switcher_open_direction": "top", "switcher_horizontal_position": "left", "switcher_vertical_position": "bottom", "switcher_text_color": "#666", "switcher_arrow_color": "#666", "switcher_border_color": "#ccc", "switcher_background_color": "#fff", "switcher_background_shadow_color": "#efefef", "switcher_background_hover_color": "#fff", "dropdown_text_color": "#000", "dropdown_hover_color": "#fff", "dropdown_background_color": "#eee", "flags_location": "https://cdn.gtranslate.net/flags/" };
    /* ]]> */
</script>
<script src="{{ asset('Public/gtranslate/js/dwf84fc.js?ver=6.4.3') }}" data-no-optimize="1" data-no-minify="1"
    data-gt-orig-url="/" data-gt-orig-domain="http://127.0.0.1:8000/" data-gt-widget-id="16411826" defer></script>

@stack('scripts')
</body>

</html>