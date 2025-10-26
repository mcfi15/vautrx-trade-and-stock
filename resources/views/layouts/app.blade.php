<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dectrx.com/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 03 Sep 2025 13:07:46 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vautrx | Vautrx | Secure &amp; Fast Cryptocurrency Exchange for Bitcoin, Ethereum &amp; Altcoins')</title>
    
    <meta charset="utf-8">
    <meta name="Keywords"
        content="Dectrx | Dectrx, Dectrx.com, cryptocurrency exchange, crypto trading, buy Bitcoin, sell Ethereum, altcoin exchange, secure crypto platform, low-fee crypto trading, Bitcoin exchange, Ethereum trading, blockchain trading, digital asset exchange, crypto market, DeFi trading, instant crypto swaps, crypto wallet integration">
    <meta name="Description"
        content="Dectrx | Trade securely and efficiently on Dectrx, the leading cryptocurrency exchange platform. Buy, sell, and swap Bitcoin, Ethereum, and altcoins with low fees, advanced security, and real-time market data.">
    <meta name="author" content="static">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="copyright" content="Dectrx">
    <link rel="shortcut icon" href="favicon.ico" />
    <link href="Public/template/epsilon/img/favicon.png" rel="icon" type="image/x-icon">


    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/style.css') }}">
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/add-style.css') }}">
    <!--<link rel="stylesheet" href="/{{ asset('Public/template/epsilon/css/custom/bootstarp.css') }}">-->
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/6pp9tx5LPOzE.css') }}">
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/8pHr7IdzNTAX.css') }}">

    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/Fx80kkK9MFjp.css') }}">
    <link rel="stylesheet" href="{{ asset('Public/template/epsilon/css/custom/RvcY6nkUjvCA.css') }}">

    <link href="{{ asset('Public/template/epsilon/css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('Public/template/epsilon/css/custom.css') }}" rel="stylesheet">

    <link href="Public/template/common/common.css') }}" rel="stylesheet">
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
            color: #ffbe40 !important;
        }

        .slick-dots li button:before {
            color: #ffbe40;
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
            border-bottom: 2px solid #ffbe40;
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
            border-color: #ffbe40;
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
            background-color: rgba(255, 190, 64, .8) !important;
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
        /*    background: #ffbe40;*/
        /* color: white !important; */
        /*    font-weight: 500;*/
        /*}*/

        /*.cta__row {*/
        /*    background: transparent;*/
        /*    border: 1px solid #80808038;*/
        /*    box-shadow: 1px 2px 4px -3px rgb(0 0 0 / 50%);*/
        /*}*/

        /*.blue {*/
        /*    color: #ffbe40;*/
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
            color: #ffbe40 !important;
        }

        form.needs-validation .nav-pills .nav-link[aria-selected=true].active span {
            color: black !important;
        }

        form.needs-validation .form-control {
            color: white !important;
            padding: 10px;
        }


        .form-wrapper .nav-pills .nav-link span {
            color: #ffbe40 !important;
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
    </style>

    <script src="{{ asset('Public/template/epsilon/js/jquery-3.4.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Public/assets/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Public/static/js/jquery.cookies.2.2.0.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Public/static/js/layer.js') }}"></script>
    <script src="../cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js') }}"
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

    <!-- Flash Messages -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <i class="fas fa-times"></i>
            </span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="max-w-7xl mx-auto mt-4 px-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <i class="fas fa-times"></i>
            </span>
        </div>
    </div>
    @endif

    <main class="wrapper grey-bg"></main>
    <script>(function () { function c() { var b = a.contentDocument || a.contentWindow.document; if (b) { var d = b.createElement('script'); d.innerHTML = "window.__CF$cv$params={r:'979578cdb9897740',t:'MTc1NjkwNDg2NA=='};var a=document.createElement('script');a.src='cdn-cgi/challenge-platform/h/b/scripts/jsd/4710d66e8fda/maind41d.js';document.getElementsByTagName('head')[0].appendChild(a);"; b.getElementsByTagName('head')[0].appendChild(d) } } if (document.body) { var a = document.createElement('iframe'); a.height = 1; a.width = 1; a.style.position = 'absolute'; a.style.top = 0; a.style.left = 0; a.style.border = 'none'; a.style.visibility = 'hidden'; document.body.appendChild(a); if ('loading' !== document.readyState) c(); else if (window.addEventListener) document.addEventListener('DOMContentLoaded', c); else { var e = document.onreadystatechange || function () { }; document.onreadystatechange = function (b) { e(b); 'loading' !== document.readyState && (document.onreadystatechange = e, c()) } } } })();</script>
</body>

@yield('content')

<script>
    function easytrade() {
        var coin = $("select#easycoin").find("option:selected").attr("data-value");
        var amount = $("input#easyamount").val();
        var url = '/Easy/index';
        if (!coin) {
            layer.tips('Please select coin!', "#easycoin", { tips: 3 });
            return false;
        }
        if (!amount) {
            layer.tips('Please enter amount', "#easyamount", { tips: 3 });
            return false;
        }
        coin = coin.toUpperCase();
        url = url + "?coin=" + coin + "&amount=" + amount;
        console.log(url);
        window.location = url;
    }
</script>

<!-- Footer -->
<footer class="cex-ui-footer">
    <div class="cex-ui-footer-content">
        <div class="cex-ui-footer-content-menu cex-ui-footer-content-menu--grid">
            <div class="subscription-form-wrapper">
                <img src="Upload/public/65d5f518818ac.png" alt="Dectrx" class="img-fluid m-b-15" width="152px">
                <div>Trade securely and efficiently on Dectrx, the leading cryptocurrency exchange platform. Buy, sell,
                    and swap Bitcoin, Ethereum, and altcoins with low fees, advanced security, and real-time market
                    data.</div>
            </div>


            <div class="links-columns">
                <div class="cex-ui-footer-content-menu-column">
                    <h2 class="cex-ui-title cex-ui-title-m cex-ui-title-bold cex-ui-title-left" title="">
                        Trade</h2>

                    <div class="cex-ui-services">
                        <a href="Trade.html" class="cex-ui-text cex-ui-text-small" id="">Spot</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Easy.html" class="cex-ui-text cex-ui-text-small" id="">Easy Convert</a>
                    </div>
                </div>
                <div class="cex-ui-footer-content-menu-column">
                    <h2 class="cex-ui-title cex-ui-title-m cex-ui-title-bold cex-ui-title-left" title="">
                        Earn</h2>

                    <div class="cex-ui-services">
                        <a href="Invest.html" class="cex-ui-text cex-ui-text-small" id="">Staking</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Airdrop.html" class="cex-ui-text cex-ui-text-small" id="">Airdrop</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Faucet.html" class="cex-ui-text cex-ui-text-small" id="">Faucet</a>
                    </div>
                </div>
                <div class="cex-ui-footer-content-menu-column">
                    <h2 class="cex-ui-title cex-ui-title-m cex-ui-title-bold cex-ui-title-left" title="">
                        Platform</h2>

                    <div class="cex-ui-services">
                        <a href="{{ url('about') }}" class="cex-ui-text cex-ui-text-small" id="">About Us</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Dex.html" class="cex-ui-text cex-ui-text-small" id="">Decentralized</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="{{ url('markets') }}" class="cex-ui-text cex-ui-text-small" id="">Market</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Pool.html" class="cex-ui-text cex-ui-text-small" id="">Mining</a>
                    </div>
                </div>
                <div class="cex-ui-footer-content-menu-column">
                    <h2 class="cex-ui-title cex-ui-title-m cex-ui-title-bold cex-ui-title-left" title="">
                        Terms</h2>

                    <div class="cex-ui-services">
                        <a href="Article/detail/id/1.html" class="cex-ui-text cex-ui-text-small" id="">User
                            Agreement</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Article/detail/id/2.html" class="cex-ui-text cex-ui-text-small" id="">Staking Terms</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Article/detail/id/3.html" class="cex-ui-text cex-ui-text-small" id="">Privacy
                            Policy</a>
                    </div>
                    <div class="cex-ui-services">
                        <a href="Article/detail/id/4.html" class="cex-ui-text cex-ui-text-small" id="">Cookie Policy</a>
                    </div>
                </div>
            </div>

            <div class="cex-ui-footer-content-menu-column">
                <h2 class="cex-ui-title cex-ui-title-m cex-ui-title-bold cex-ui-title-left" title="">
                    Contact</h2>

                <div class="cex-ui-services">


                    <a href="cdn-cgi/l/email-protection.html#4b383e3b3b24393f0b2f2e283f393365282426"
                        class="cex-ui-text cex-ui-text-small" style="white-space: nowrap; margin-bottom: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path fill="#fff"
                                d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                        </svg>
                        <span class="__cf_email__"
                            data-cfemail="b8cbcdc8c8d7caccf8dcdddbcccac096dbd7d5">[email&#160;protected]</span> </a>

                    <a href="cdn-cgi/l/email-protection.html#375e5951587753525443454f1954585a"
                        class="cex-ui-text cex-ui-text-small" style="white-space: nowrap; margin-bottom: 5px;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path fill="#fff"
                                d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z" />
                        </svg>
                        <span class="__cf_email__"
                            data-cfemail="adc4c3cbc2edc9c8ced9dfd583cec2c0">[email&#160;protected]</span> </a>
                </div>
            </div>
            <!--            <div class="cex-ui-footer-content-menu-column">-->
            <!--                <h2 class="cex-ui-title cex-ui-title-m cex-ui-title-bold cex-ui-title-left" title="">Follow</h2>-->
            <!--                <div class="cex-ui-social">-->
            <!--                    <div class="cex-ui-messengers">-->
            <!-- X (Twitter) -->
            <!--                        <a href="https://twitter.com/dec_trx"-->
            <!--                            class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--                            target="_blank">-->
            <!--                            <svg viewBox="0 0 24 24" class="cex-ui-icon">-->
            <!--                                <path-->
            <!--                                    d="M23.954 4.569c-.885.392-1.83.656-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.564-2.005.974-3.127 1.195-.897-.959-2.178-1.558-3.594-1.558-2.723 0-4.928 2.206-4.928 4.928 0 .386.045.762.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.422.722-.666 1.561-.666 2.475 0 1.708.869 3.213 2.188 4.096-.807-.026-1.566-.248-2.229-.616v.062c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.6 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.179 1.394 4.768 2.21 7.557 2.21 9.054 0 14-7.496 14-13.986 0-.21-.006-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"-->
            <!--                                    fill="#0C87F2"></path>-->
            <!--                            </svg>-->
            <!--                        </a>-->

            <!-- YouTube -->
            <!--                        <a href="https://www.youtube.com/@dectrx"-->
            <!--                            class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--                            target="_blank">-->
            <!--                            <svg viewBox="0 0 24 24" class="cex-ui-icon">-->
            <!--                                <path-->
            <!--                                    d="M23.498 6.186c-.275-1.037-1.089-1.853-2.127-2.128C19.396 3.5 12 3.5 12 3.5s-7.396 0-9.371.558C1.591 4.334.776 5.15.501 6.187.001 8.18 0 12 0 12s.001 3.82.501 5.814c.275 1.037 1.09 1.853 2.128 2.128 1.975.558 9.371.558 9.371.558s7.396 0 9.371-.558c1.038-.275 1.852-1.091 2.127-2.128.501-1.994.501-5.814.501-5.814s0-3.82-.501-5.814zM9.546 15.568V8.432L15.818 12l-6.272 3.568z"-->
            <!--                                    fill="#FF0000"></path>-->
            <!--                            </svg>-->
            <!--                        </a>-->

            <!-- Facebook -->
            <!--                        <a href="https://www.facebook.com/dectrx"-->
            <!--                            class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--                            target="_blank">-->
            <!--                            <svg viewBox="0 0 24 24" class="cex-ui-icon">-->
            <!--                                <path-->
            <!--                                    d="M22.675 0h-21.35C.6 0 0 .6 0 1.337v21.326C0 23.4.6 24 1.337 24H12.82v-9.294H9.692V11.21h3.127V8.413c0-3.1 1.894-4.788 4.659-4.788 1.325 0 2.463.1 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.495h-3.12V24h6.116C23.4 24 24 23.4 24 22.663V1.337C24 .6 23.4 0 22.675 0z"-->
            <!--                                    fill="#1877F2"></path>-->
            <!--                            </svg>-->
            <!--                        </a>-->

            <!-- Telegram -->
            <!--                        <a href="https://t.me/dec_trx"-->
            <!--                            class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--                            target="_blank">-->
            <!--                            <svg viewBox="0 0 24 24" class="cex-ui-icon">-->
            <!--                                <path-->
            <!--                                    d="M9.353 15.862l-.398 5.618c.57 0 .815-.245 1.116-.54l2.67-2.496 5.53 4.03c1.013.557 1.73.264 1.985-.935L23.972 1.47c.303-1.226-.422-1.705-1.43-1.35L1.56 9.17c-1.21.47-1.198 1.137-.21 1.437l5.775 1.803 13.391-8.43-10.164 12.43z"-->
            <!--                                    fill="#0088cc"></path>-->
            <!--                            </svg>-->
            <!--                        </a>-->

            <!-- Instagram -->
            <!--                        <a href="https://www.instagram.com/dec_trx"-->
            <!--                            class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--                            target="_blank">-->
            <!--                            <svg viewBox="0 0 24 24" class="cex-ui-icon">-->
            <!--                                <path-->
            <!--                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.322 3.608 1.297.975.975 1.235 2.242 1.297 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.322 2.633-1.297 3.608-.975.975-2.242 1.235-3.608 1.297-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.322-3.608-1.297-.975-.975-1.235-2.242-1.297-3.608C2.175 15.647 2.163 15.267 2.163 12s.012-3.584.07-4.85c.062-1.366.322-2.633 1.297-3.608C4.505 2.485 5.772 2.225 7.138 2.163 8.404 2.105 8.784 2.163 12 2.163z"-->
            <!--                                    fill="#E4405F"></path>-->
            <!--                            </svg>-->
            <!--                        </a>-->

            <!--                        <a href="https://medium.com/@dectrx"-->
            <!--                            class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--                            target="_blank">-->
            <!--                            <svg viewBox="0 0 1043.63 592.71" class="cex-ui-icon">-->
            <!--                                <path-->
            <!--                                    d="M588.67 296.35c0 163.66-131.82 296.35-294.33 296.35S0 460.01 0 296.35 131.82 0 294.33 0s294.34 132.69 294.34 296.35zM741.61 296.35c0 147.74-65.74 267.58-146.84 267.58s-146.84-119.84-146.84-267.58 65.74-267.58 146.84-267.58 146.84 119.84 146.84 267.58zM1043.63 296.35c0 133.71-29.43 242.1-65.74 242.1s-65.73-108.39-65.73-242.1 29.43-242.1 65.73-242.1 65.74 108.39 65.74 242.1z"-->
            <!--                                    fill="#000000"></path>-->
            <!--                            </svg>-->
            <!--                        </a>-->

            <!--                        <a href="https://www.linkedin.com/company/dectrx"-->
            <!--   class="cex-ui-icon-button cex-ui-icon-button-big cex-ui-icon-button-secondary"-->
            <!--   target="_blank">-->
            <!--   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 34" class="cex-ui-icon">-->
            <!--       <title>LinkedIn</title>-->
            <!--       <g>-->
            <!--           <path d="M34,2.52V31.48A2.52,2.52,0,0,1,31.48,34H2.52A2.52,2.52,0,0,1,0,31.48V2.52A2.52,2.52,0,0,1,2.52,0H31.48A2.52,2.52,0,0,1,34,2.52Z" fill="#0077B5"/>-->
            <!--           <path d="M5,12.79h5.06V29H5ZM7.52,4.94A2.94,2.94,0,1,1,4.58,7.88,2.93,2.93,0,0,1,7.52,4.94Zm6.17,7.85h4.85v2.2h.07a5.32,5.32,0,0,1,4.78-2.63c5.11,0,6.05,3.36,6.05,7.72V29H24.24V21.44c0-1.8,0-4.11-2.5-4.11s-2.88,1.95-2.88,3.97V29H14.19Z" fill="#ffffff"/>-->
            <!--       </g>-->
            <!--   </svg>-->
            <!--</a>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->

        </div>

        <span style="padding: 10px; display: block; font-size: 12px;"> Copyright 2025 Cryptocurrency Exchange
            Software</span>
    </div>
</footer>

<style>
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
</style>

<script data-cfasync="false" src="cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') }}"></script>
<script>window.$zoho = window.$zoho || {}; $zoho.salesiq = $zoho.salesiq || { ready: function () { } }</script>
<script id="zsiqscript"
    src="https://salesiq.zohopublic.com/widget?wc=siqc39ef4e4296674bd7777095ed2e835ec5cd224cf3ba652fbb6c09f55b1433961"
    defer></script>
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

<script>
    function choose_lang(lang) {
        $.cookies.set("lang", lang);
        window.location.reload();
    }
</script>
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
<script src="{{ asset('Public/template/epsilon/js/custom.js') }}"></script>
<script>

</script>
<!-- Scripts End -->

@stack('scripts')
</body>

</html>