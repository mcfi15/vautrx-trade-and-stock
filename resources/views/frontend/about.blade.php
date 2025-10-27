@extends('layouts.app')

@section('title', 'About Us')

@section('content')

<main>
	<section class="about-us-banner-section">
		<div class="about-us-inner-section">
			<div class="about-banner-image"
				style="background-image:url(Public/template/epsilon/img/redesign/about/hero_bg_dark.png)">
				<div class="container">
					<div class="banner-inner-boxes">
						<div class="banner-top-section">
							<div class="index_titleBox__5j_B3">
								<div class="about-headings">{{ \App\Models\Setting::get('site_name', 'Website Name') }}</div>
								<p>The Gold Standard in Cryptocurrency Trading
							</div>
						</div>
					</div>
					<div class="banner-mid-section">
						<div class="index_desBOX__4OhqS">
							<p>{{ \App\Models\Setting::get('site_name', 'Website Name') }} Exchange was established in 2018 and registered in Seychelles. Its
								operating headquarters are located in Dubai. It has operation centers in
								Singapore, Europe and other countries and regions, and its business
								covers the world.
							</p>
							<p style="margin:30px 0">The platform owns the global top-level domain www.{{ \App\Models\Setting::get('site_name', 'Website Name') }}.com,
								and currently has more than 6 million registered users, more than 500,000
								monthly active users, and more than 40 million users in the ecosystem.</p>
							<div class="index_flexCenter__ZDRhT">
								<p>{{ \App\Models\Setting::get('site_name', 'Website Name') }}.COM is a comprehensive trading platform that supports 800+ high-quality
									tokens and 1000+ trading pairs. It has a rich variety of tradings such as
									spot trading, futures trading, margin trading, OTC trading and
									buying cryptos with credit cards. We provide users with the safest, most
									efficient and professional digital asset investment services.
								<p>
							</div>
						</div>
					</div>
					<div class="banner-bottom-section">
						<div class="banner-bottom-list">
							<div class="index_top__XL9xj">1000+</div>
							<div class="index_bottom__e0gEN">Trading Pairs</div>
						</div>
						<div class="banner-bottom-list">
							<div class="index_top__XL9xj">6,000,000+</div>
							<div class="index_bottom__e0gEN">Registered Users</div>
						</div>
						<div class="banner-bottom-list">
							<div class="index_top__XL9xj">40,000,000+</div>
							<div class="index_bottom__e0gEN">{{ \App\Models\Setting::get('site_name', 'Website Name') }} Ecosystem Users</div>
						</div>
						<div class="banner-bottom-list">
							<div class="index_top__XL9xj">7/24</div>
							<div class="index_bottom__e0gEN">Customer Service and Support</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="Business-scope-section">
		<div class="Business-scope-inner-section">
			<div class="Business-scope-image Desktop_view"
				style="background-image:url(Public/template/epsilon/img/redesign/about/global_coverage_business_scope_dark.png)">
				<div class="container">
					<div class="index_titleAll__e7b9e">
						<div><span style="color: #ffbe40"> Global coverage </span> business scope</div>
					</div>
					<div class="index_desBOX__qYef9">
						<div>Headquartered in Dubai, {{ \App\Models\Setting::get('site_name', 'Website Name') }} set up operation centers in Singapore, Europe and other
							countries and regions</div>
						<div>with global coverage of business.</div>
					</div>
				</div>
			</div>

			<div class="Business-scope-image Mobile_view"
				style="background-image:url(Public/template/epsilon/img/redesign/about/global_coverage_business_scope_dark_mob.png)">
				<div class="container">
					<div class="index_titleAll__e7b9e">
						<div><span style="color: #ffbe40"> Global coverage </span> business scope</div>
					</div>
					<div class="index_desBOX__qYef9">
						<div>Headquartered in Dubai, {{ \App\Models\Setting::get('site_name', 'Website Name') }} set up operation centers in Singapore, Europe and other
							countries and regions</div>
						<div>with global coverage of business.</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<style>
	/*--------------ABOUT-US-CSS-START-------------- */
	#light .Business-scope-section .index_titleAll__e7b9e {
		padding-top: 0;
		color: black;
	}

	#light .Business-scope-section .Business-scope-image {
		background-image: url(Public/template/epsilon/img/redesign/about/global_coverage_business_scope_light.png) !important;
		background-position: bottom;
		background-color: white;
	}

	#light .about-banner-image {
		background-image: url(Public/template/epsilon/img/redesign/about/hero_bg_light.png) !important;
		background-color: white;
	}

	#light .banner-top-section .index_titleBox__5j_B3 .about-headings,
	#light .banner-top-section .index_titleBox__5j_B3 p,
	#light .banner-mid-section .index_desBOX__4OhqS,
	#light .Business-scope-section .index_desBOX__qYef9 {
		color: black;
	}

	#light .banner-bottom-section .index_top__XL9xj {
		color: #fcc968;
	}

	#light .index_bottom__e0gEN {
		font-weight: bold;
		color: black;
	}

	.about-banner-image {
		min-height: 800px;
		position: relative;
		clear: both;
		overflow: hidden;
		background: top no-repeat;
		background-color: #0c0c0e;
	}

	body#about-page button.dark-light-icon {
		display: none;
	}

	body#about-page main {
		padding-top: 30px;
	}

	.banner-top-section .index_titleBox__5j_B3 {
		font-size: 60px;
		text-align: center;
		color: #fff;
		line-height: 70px;
		margin: 100px 0 auto;
	}

	.banner-top-section .index_titleBox__5j_B3 p {
		margin-top: 30px;
	}

	.banner-top-section .index_titleBox__5j_B3 .about-headings {
		font-size: 70px;
	}

	.banner-mid-section .index_desBOX__4OhqS {
		font-size: 16px;
		text-align: center;
		color: #b9b9ba;
		line-height: 22px;
		margin-top: 70px;
		width: 100%;
		max-width: 1034px;
		margin-left: auto;
		margin-right: auto;
	}

	.banner-mid-section .index_flexCenter__ZDRhT {
		max-width: 644px;
		margin: 0 auto;
	}

	.banner-bottom-section {
		position: absolute;
		bottom: 100px;
		margin: 0 auto;
		display: flex;
		justify-content: space-between;
		text-align: center;
		max-width: 1200px;
		width: 100%;
		left: 0;
		right: 0;
	}

	.banner-bottom-section .index_top__XL9xj {
		height: 44px;
		font-size: 36px;
		font-weight: 700;
		color: #fff;
	}

	.Trading-Categories-section .banner-bottom-section .index_bottom__e0gEN {
		font-weight: 400;
		color: hsla(0, 0%, 100%, .63);
		line-height: 25px;
		font-size: 18px;
	}

	.Trading-Categories-section .index_titleBox__u4jkE {
		font-size: 36px;
		font-weight: 400;
		color: #fff;
		line-height: 50px;
		text-align: center;
	}

	.Trading-Categories-section .index_diversify__xFUaY {
		font-size: 16px;
		font-weight: 400;
		color: hsla(0, 0%, 100%, .63);
		line-height: 22px;
		text-align: center;
		margin-top: 21px;
	}

	.Trading-Categories-section .index_imgBox__foYwd {
		position: relative;
		width: 400px;
		height: 354px;
		color: #8f8e92;
		margin: 84px auto 0;
	}

	.Trading-Categories-section .index_center__2xKUH {
		color: #fff;
		width: 100%;
		height: 22px;
		position: absolute;
		text-align: center;
		top: 50%;
		margin-top: -11px;
	}

	.Trading-Categories-section .index_cointext__jh3Cv {
		width: 300px;
		height: 20px;
		position: absolute;
	}

	.Trading-Categories-section .index_cointext1__8Mz1w {
		text-align: right;
		top: 38px;
		left: -230px;
	}

	.Trading-Categories-section .index_cointext2__PS4su {
		top: 38px;
		left: 330px;
	}

	.Trading-Categories-section .index_cointext3__Qo8ub {
		text-align: right;
		top: 162px;
		left: -310px;
	}

	.Trading-Categories-section .index_cointext4__Rpbov {
		top: 162px;
		left: 413px;
	}

	.Trading-Categories-section .index_cointext5__DRbcG {
		text-align: right;
		top: 300px;
		left: -230px;
	}

	.Trading-Categories-section .index_cointext6__tBlRi {
		top: 300px;
		left: 340px;
	}

	.Trading-Categories-section {
		background-color: #000;
		padding: 120px 0 133px 0;
	}

	.Trading-Platform-section {
		padding: 120px 0 0 0;
		background-color: #111111;
	}

	.Trading-Platform-section .index_desAll__uatml {
		font-size: 16px;
		font-weight: 400;
		color: hsla(0, 0%, 100%, .63);
		line-height: 22px;
		text-align: center;
		margin-top: 21px;
	}

	.Trading-Platform-section .index_titleAll__13_cv {
		font-size: 36px;
		font-weight: 400;
		color: #fff;
		line-height: 50px;
		text-align: center;
	}

	.Trading-Platform-section .index_imgBox__5DvnW {
		width: 100%;
		height: 564px;
		margin: 21px auto 0;
		background: top no-repeat;
	}

	.Business-scope-section .index_titleAll__e7b9e {
		padding-top: 28px;
		font-size: 36px;
		font-weight: 400;
		color: #fff;
		line-height: 50px;
		text-align: center;
	}

	.Business-scope-section .index_desBOX__qYef9 {
		font-size: 16px;
		font-weight: 400;
		color: hsla(0, 0%, 100%, .63);
		line-height: 22px;
		text-align: center;
		margin-top: 21px;
	}

	.Business-scope-section .Business-scope-image {
		background: bottom no-repeat;
		background-color: #000;
		height: 800px;
	}

	/*--------------ABOUT-US-CSS-END-------------- */

	/*--------------MEDIA-START-------------- */

	@media(min-width: 767px) {
		.Mobile_view {
			display: none !important;
		}
	}

	@media(min-width: 1200px) {
		.footer-inner-section ul.accordion-list {
			display: none !IMPORTANT;
		}

		.index_app-navbar-mb__menu__3nPI2 {
			display: none;
		}

		.index_imgBox__foYwd.Categories-mobile_section {
			display: none !IMPORTANT;
		}
	}

	@media(min-width: 1299px) {
		footer.footer .accordion {
			display: none;
		}
	}

	@media(max-width: 1300px) {
		.graph-main-section .graphs {
			max-width: 180px;
		}

		.graph-main-section .graph-top-section h2 {
			font-size: 20px;
		}

		.graph-main-section .graph-bottom-section .bottom-left {
			margin-right: 12px;
		}

		.footer-inner-section .footer-grids {
			display: none !IMPORTANT;
		}

		.banner-bottom-section {
			max-width: 990px;
		}

		.about-banner-image {
			min-height: 990px;
		}

		.banner-top-section .index_titleBox__5j_B3 .about-headings {
			font-size: 60px;
		}

		.banner-top-section .index_titleBox__5j_B3 p {
			margin-top: 20px;
		}

		.banner-top-section .index_titleBox__5j_B3 {
			font-size: 50px;
			line-height: 60px;
			margin: 160px 0 auto;
		}

		.banner-mid-section .index_desBOX__4OhqS {
			margin-top: 60px;
		}
	}

	@media(max-width: 1199px) {
		.index_imgBox__foYwd.Categories-desktop_section {
			display: none !IMPORTANT;
		}

		.Trading-Categories-section .index_imgBox__foYwd.Categories-mobile_section .index_center__2xKUH {
			position: static;
			width: auto;
			margin-bottom: 10px;
		}

		.index_imgBox__foYwd.Categories-mobile_section {
			width: 100%;
			height: auto;
		}

		.index_imgBox__foYwd.Categories-mobile_section .all-categories .Categories {
			padding: 14px 10px;
			text-align: center;
		}

		.index_imgBox__foYwd.Categories-mobile_section .Categories-icon {
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.index_imgBox__foYwd.Categories-mobile_section .all-categories .Categories-title {
			text-align: center;
			font-size: 16px;
			margin-top: 14px;
		}

		.index_imgBox__foYwd.Categories-mobile_section .Categories-icon img {
			max-width: 60px;
		}

		.index_imgBox__foYwd.Categories-mobile_section .all-categories {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			align-items: center;
		}

		.menu-active {
			overflow: hidden;
			position: relative;
		}

		.mobile-menu-drawer {
			transform: translateX(100%);
			transition: 0.6s;
			position: fixed;
			top: 0;
			left: 0;
			background-color: rgba(27, 27, 27, .5);
			background: rgba(45, 45, 46, .3);
			-webkit-backdrop-filter: blur(27.1828px);
			backdrop-filter: blur(27.1828px);
			width: 60%;
			height: 100%;
			right: 0;
			padding: 28px;
			overflow-y: auto;
			margin-left: auto;
			z-index: 9999;
		}

		.menu-active:before {
			background-color: rgba(27, 27, 27, .5);
			z-index: 9999;
			position: absolute;
			top: 0;
			bottom: 0;
			width: 100%;
			height: 100%;
			left: 0;
			right: 0;
			content: '';
		}

		.index_app-navbar-mb__menu__3nPI2 .index_iGroup__PreE8 a {
			height: 42px;
			display: flex;
			align-items: center;
			width: 100%;
		}

		.index_app-navbar-mb__menu__3nPI2 .index_iGroup__PreE8 {
			border-top: 1px solid hsla(0, 0%, 93%, .1);
			margin-top: 14px;
			padding-top: 14px;
		}



		.menu-active .mobile-menu-drawer {
			transform: translateX(0%);
		}

		.index_app-navbar-mb__menu__3nPI2 .index_iBtn__sYXo9 {
			font-size: 14px;
			color: #ffbe40;
			border: 1px solid #ffbe40;
			display: block;
			width: 80%;
			margin: 0 auto;
			text-align: center;
			border-radius: 3px;
			padding: 5px;
			min-height: 33px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.index_app-navbar-mb__menu__3nPI2 .index_iBtn__sYXo9.index_iBtnFull__M2TVN {
			color: #212121;
			background: #ffbe40;
			margin-top: 12px;
		}

		.index_app-navbar-mb__menu__3nPI2 .index_iGroup__PreE8 svg {
			max-width: 16px;
			height: auto;
			margin-right: 5px;
		}



		.heder-inner-section.Mobile_view {
			display: flex !important;
			padding: 0 15px;
		}

		.heder-inner-section .left-menu-bar a.logo {
			padding-left: 0;
		}

		.heder-inner-section .list-menu.Mobile-menus {
			display: none;
		}

		.heder-inner-section.Mobile_view {
			display: flex !important;
		}

		.heder-inner-section .right-header-bar .sign_up-btn {
			margin: 0 17px 0 10px;
		}

		.heder-inner-section.Desktop_view {
			display: none;
		}

		.explore-box {
			padding: 40px 0 0 14px;
		}

		.graph-main-section .graphs {
			max-width: 150px;
		}

		.graph-main-section .graph-top-section h2 {
			font-size: 16px;
		}

		.graph-main-section .red-txt {
			font-size: 11px;
		}

		.currency-main-section .Main_headings {
			margin-bottom: 25px;
		}

		.Main_headings {
			font-size: 34px;
			line-height: 50px;
		}

		.currency-main-section ul li,
		.currency-main-section p {
			line-height: 36px;
		}

		.home-guide-container-step .home-guide-section:nth-child(even) .image-section {
			margin-left: 80px;
		}

		.home-guide-container-step .home-guide-section .image-section {
			margin-right: 80px;
		}

		.app-download-container .app-lists li {
			font-size: 16px;
			padding-left: 44px;
		}

		.banner-section .Main-heading {
			font-size: 54px;
			line-height: 64px;
			width: 100%;
		}

		.banner-section .index_banner-image {
			width: 100%;
			max-width: 250px;
			object-fit: cover;
			height: auto;
		}

		.banner-section .banner-inner-section {
			width: 100%;
		}

		.banner-section .banner-left-section {
			padding-right: 40px;
		}

		.explore-box p {
			font-size: 24px;
			line-height: 36px;
			padding-right: 0;
		}

		.explore-box-bottom img {
			width: 70px;
			height: 70px;
		}

		.app-download-container .index_qcode-drop__XGiQE .scanner-img svg {
			max-width: 100px;
		}

		.app-download-container .index_download-item a {
			padding: 10px 22px;
		}

		footer.footer {
			padding: 50px 0;
		}

		.start-journey-section {
			padding-top: 60px;
			padding-bottom: 80px;
		}

		.app-download-container {
			padding: 0 0 80px 0;
		}

		.Crypto-Lending-section {
			padding: 0 0 80px 0;
		}

		.explore-our-products {
			padding-top: 88px;
			padding-bottom: 80px;
		}

		.currency-main-section {
			padding-top: 80px;
		}

		.scrolling-text .slick-vertical .slick-slide a {
			margin-right: 40px;
		}

		.banner-bottom-section {
			max-width: 860px;
		}

		.banner-bottom-section .index_top__XL9xj {
			height: 40px;
			font-size: 30px;
		}

		.banner-top-section .index_titleBox__5j_B3 .about-headings {
			font-size: 50px;
		}

		.banner-top-section .index_titleBox__5j_B3 {
			font-size: 40px;
			line-height: 50px;
			margin: 130px 0 auto;
		}

		.about-banner-image {
			min-height: 790px;
		}

		.Trading-Categories-section {
			padding: 90px 0 103px 0;
		}

		.Trading-Categories-section .index_imgBox__foYwd {
			margin: 50px auto 0;
		}

		.Trading-Platform-section {
			padding: 90px 0 0 0;
		}

		.Business-scope-section .index_titleAll__e7b9e {
			padding-top: 90px;
		}
	}

	@media(max-width: 989px) {
		.banner-email {
			width: 100%;
		}

		.index_quickRegTool-box__CSWGE {
			width: unset;
		}

		.index_quick-reg-wrapper__AGLkO {
			height: 52px;
			border-radius: 8.35px;
		}

		.index_quick-reg-wrapper__AGLkO .ant-input-group {
			height: 250px;
			display: flex;
			flex-direction: column;
		}

		.index_quick-reg-wrapper__AGLkO .ant-input-group .ant-input {
			height: 52px;
			font-size: 18px;
			line-height: 18px;
			border-radius: 6px;
			padding: 14px 20px;
		}

		.index_quick-reg-wrapper__AGLkO .ant-input-group-addon {
			margin-top: 16px;
			height: 52px;
			width: 100%;
			border-radius: 6px;
			font-weight: 500;
		}

		.index_quick-reg-wrapper__AGLkO .ant-input-search>.ant-input-group>.ant-input-group-addon:last-child .ant-input-search-button {
			border-radius: 6px;
			position: unset;
			width: 100%;
			font-size: 16px;
		}

		.banner-section .index_banner-image {
			display: none;
		}

		.banner-section .banner-left-section {
			padding-right: 0;
			width: 100%;
		}

		.banner-section .banner-inner-section {
			flex-direction: column;
			padding: 0;
		}

		.banner-section {
			padding: 34px 0 100px;
			height: unset;
		}

		.graph-main-section .graphs {
			max-width: 130px;
		}

		.graph-main-section .graph-top-section h2 {
			font-size: 14px;
			margin-right: 8px;
		}

		.Crypto-Lending-section .earning-section {
			grid-template-columns: 1fr;
		}

		.app-download-container .app-download-inner-container {
			grid-template-columns: 1fr;
		}

		.start-journey-section .start-journey-boxes .index_step__bix_X img {
			width: 60px;
			height: 60px;
			margin-bottom: 20px;
		}

		.start-journey-section .start-journey-boxes p {
			font-size: 16px;
			line-height: 20px;
			text-align: center;
		}

		.footer-grids {
			grid-gap: 10px;
		}

		.footer-blocks .footer-social-links a span svg {
			max-width: 22px;
			height: auto;
		}

		.Trading-Platform-section .index_titleAll__13_cv {
			font-size: 30px;
			line-height: 40px;
		}

		.banner-top-section .index_titleBox__5j_B3 .about-headings {
			font-size: 40px;
		}

		.banner-top-section .index_titleBox__5j_B3 {
			font-size: 30px;
			line-height: 40px;
			margin: 110px 0 auto;
		}

		.banner-bottom-section {
			width: 100%;
			position: static;
			margin-top: 40px;
			display: block;
			margin-left: 0;
			padding-bottom: 20px;
		}

		.banner-bottom-section .banner-bottom-list {
			margin-bottom: 24px;
		}

		.Business-scope-section .index_titleAll__e7b9e {
			padding-top: 60px;
		}

		.Trading-Platform-section {
			padding: 60px 0 0 0;
			background-color: #111111;
		}

		.banner-mid-section .index_desBOX__4OhqS {
			margin-top: 40px;
		}

		.Trading-Categories-section {
			padding: 60px 0;
		}
	}

	@media(max-width: 768px) {
		.Desktop_view {
			display: none !important;
		}

		.banner-section .Main-heading {
			font-size: 36px;
			line-height: 40px;
			max-width: 290px;
			margin: 0 0 20px 0;
		}

		.banner-section p {
			margin-bottom: 20px;
			font-size: 20px;
			line-height: 24px;
		}

		.slider-dots-box {
			margin-top: 10px;
		}

		.select_wrap .default_option li {
			padding: 8px 20px;
			max-height: 44px;
		}

		.select_wrap .select_ul li {
			padding: 6px 20px;

		}

		.currency-input-main .default_option-top li {
			padding: 8px 20px;
			max-height: 44px;
		}

		form.currency-selector-form input,
		.currency-input-main .currency-input-filed {
			height: 48px;
		}

		.currency-input-filed label {
			top: -5px;
			font-size: 11px !IMPORTANT;
		}

		.select_wrap .select_ul {
			top: 46px;
			padding: 8px 0;
		}

		.select_wrap .option p {
			font-size: 14px;
			line-height: 22px;
		}

		.tabcontent-main-section .tabbings_list-header li:first-child {
			justify-content: flex-start;
		}

		.tabcontent-main-section .tabbings_list-header li:nth-child(2),
		.tabcontent-main-section .tabbings_list-header li:nth-child(3) {
			justify-content: flex-end;
		}

		li.index_last-price-li__J2Qs2,
		.tabcontent-main-section ul li:nth-child(3) {
			justify-content: flex-end;
		}

		.tabcontent-main-section li:last-child {
			display: none;
		}

		.index_list-content-li-trade__uACOd {
			display: none;
		}

		.image-width-text-section {
			padding-top: 40px;
		}

		.graph-main-section .graph-inner-section.Mobile_view .owl-item.active~.active {
			border-right: 0;
		}

		.graph-main-section .graph-inner-section.Mobile_view .owl-item.active {
			border-right: 1px solid #525252;
		}

		.graph-main-section .graphs {
			border-right: 0;
			padding: 0px 13px;
		}

		.graph-main-section .graph-inner-section.Mobile_view {
			display: block !important;
		}

		.home-slider-section .slick-slide span img {
			border-radius: 5px;
		}

		.slider-box .slick-arrow {
			display: none !important;
		}

		form.currency-selector-form ul.border-section {
			margin: 32px 0;
		}

		.home-slider-section .slick-slide span {
			margin: 0;
		}

		.container {
			padding: 0 15px;
		}

		.graph-main-section .graphs {
			max-width: 100%;
		}

		.graph-main-section {
			padding: 26px 15px;
			margin: 30px 0;
		}

		span.index_more-text__8lFEb {
			display: none;
		}

		.scrolling-text .slick-vertical .slick-slide span {
			font-size: 12px;
			display: flex;
			flex: none;
		}

		.scrolling-text .slick-slider {
			padding-left: 20px;
			width: calc(100% - 30px);
		}

		.scrolling-text .slick-vertical .slick-slide {
			display: flex;
			align-items: center;
		}

		.scrolling-text .slick-vertical .slick-slide a {
			margin-right: 76px;
			font-size: 12px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		a.index_news-more___5zY9 {
			height: 100%;
			font-size: 12px;
			right: 0;
		}

		.tabcontent-main-section li:nth-child(4),
		.tabcontent-main-section li:nth-child(5) {
			display: none;
		}

		.tabbing-main-section .tab {
			padding-left: 15px;
		}

		.tabcontent-main-section ul {
			padding: 0;
		}

		.tabcontent-main-section .tabbings_list-header {
			padding: 0;
		}

		.tabcontent-main-section li img {
			width: 20px;
			height: 20px;
		}

		ul.index_list-content-ul__3ZsrS li:nth-child(3),
		ul.index_list-content-ul__3ZsrS li:nth-child(2) {
			justify-content: flex-end;
		}

		.index_viewmore-wrapper {
			font-size: 14px;
			margin-top: 18px;
		}

		.tabbing-main-section .tablinks {
			margin-right: 30px;
			font-size: 16px;
			line-height: 16px;
		}

		.currency-main-section {
			padding-top: 30px;
		}

		.currency-inner-section {
			grid-template-columns: 1fr;
			grid-gap: 30px;
		}

		.Main_headings {
			font-size: 24px;
			line-height: 30px;
		}

		form.currency-selector-form {
			padding: 14px;
		}

		.currency-main-section ul li,
		.currency-main-section p {
			line-height: 28px;
			font-size: 16px;
			margin-bottom: 8px;
		}

		.home-guide-container-step .home-guide-section {
			flex-direction: column !IMPORTANT;
		}

		.home-guide-container-step .home-guide-section .image-section img {
			max-width: 100%;
			width: 100%;
		}

		.home-guide-container-step .home-guide-section .image-section {
			background: #181819;
			padding: 30px 35px;
			margin: 0 0 10px 0 !important;
		}

		.light-mode .home-guide-container-step .home-guide-section .image-section {
			background: rgba(0, 0, 0, .03);
		}

		.home-guide-container-step .home-guide-section {
			margin-bottom: 20px;
		}

		.home-guide-container-step .home-guide-section:nth-child(even) {
			padding: 0;
		}

		.explore-our-products {
			padding-top: 30px;
			padding-bottom: 40px;
		}

		.explore-our-products .Main_headings {
			margin-bottom: 30px;
		}

		.explore-box-main-section {
			display: grid;
			grid-template-columns: 1fr;
			grid-gap: 20px;
		}

		.Crypto-Lending-inner-section .top-section .Main_headings {
			text-align: left;
			margin-bottom: 10px;
		}

		.Crypto-Lending-inner-section .top-section p {
			text-align: left;
			font-size: 16px;
			line-height: 22px;
			margin: 0;
		}

		.Crypto-Lending-section .pick-plans-section {
			flex-direction: column;
			justify-content: flex-start;
			position: relative;
		}

		.Crypto-Lending-section .pick-plans-section:before {
			content: '';
			position: absolute;
			width: 1px;
			height: 70%;
			background-color: #4E4E4E;
			left: 22px;
		}

		.Crypto-Lending-section .pick-plans-section .plans {
			flex-direction: row;
			padding: 0;
			justify-content: flex-start;
			margin-bottom: 50px;
		}

		.Crypto-Lending-section .pick-plans-section .plans p:before,
		.Crypto-Lending-section .pick-plans-section .plans p:after,
		.Crypto-Lending-section .pick-plans-section:after {
			display: none;
		}

		.Crypto-Lending-section .pick-plans-section:before {
			content: '';
			position: absolute;
			width: 1px;
			height: 63%;
			background-color: #4E4E4E;
			top: 50%;
			transform: translate(0, -50%);
			left: 20px;
			z-index: -1;
		}

		.plans-svg:after {
			content: '';
			background-color: #4E4E4E;
			width: 9px;
			height: 9px;
			position: absolute;
			border-radius: 50%;
			top: 66px;
			left: 16px;
		}

		.Crypto-Lending-section .pick-plans-section .plans:last-child .plans-svg::after {
			display: none;
		}

		.Crypto-Lending-section .pick-plans-section .plans svg {
			max-width: 45px;
			height: auto;
		}

		.plans-svg {
			position: relative;
			margin-right: 24px;
		}

		.Crypto-Lending-section .pick-plans-section .plans p {
			font-size: 14px;
			max-width: 100%;
			text-align: left;
			padding: 0;
		}

		.earning-top-section p {
			font-size: 22px;
			line-height: 20px;
		}

		.Crypto-Lending-section .earning-section .earning {
			padding: 20px 10px;
		}

		.radio-button-form label,
		.radio-button-forms label {
			margin: 0 10px 13px 0;
		}

		.radio-button-form .days span,
		.radio-button-forms .days span {
			font-size: 18px;
		}

		.radio-button-form .days,
		.radio-button-forms .days {
			padding: 7px 8px;
		}

		.Crypto-Lending-section .pick-plans-section .plans:last-child {
			margin-bottom: 0;
		}

		.app-download-container .app-lists li:before {
			width: 18px;
			height: 18px;
		}

		.app-download-container .app-lists li {
			font-size: 16px;
			line-height: 22px;
			padding-left: 40px;
			margin-bottom: 10px;
		}

		.app-download-container .index_qcode-drop__XGiQE {
			flex-direction: column;
		}

		.app-download-container .app-store-lists {
			margin-left: 0;
			width: 100%;
			max-width: 100%;
			display: grid;
			align-items: center;
			grid-template-columns: 1fr 1fr;
			grid-gap: 12px;
		}

		.app-download-container .index_download-item a {
			padding: 10px 16px;
			margin: 0 0 0 0;
		}

		.app-download-container .index_download-item .index_download-text-bottom {
			margin-left: 8px;
		}

		.scanner-img {
			margin-bottom: 20px;
		}

		.app-download-container .index_qcode-drop__XGiQE .scanner-img svg {
			max-width: 151px;
			height: auto;
		}

		.app-download-container {
			padding: 0 0 30px 0;
		}

		.start-journey-section {
			padding-top: 20px;
			padding-bottom: 40px;
		}

		.start-journey-section .index_action__IhrM5 {
			display: none;
		}

		.start-journey-section .start-journey-boxes {
			margin-bottom: 0;
			flex-direction: column;
			align-items: center;
			text-align: center;
			justify-content: center;
		}

		.start-journey-section .start-journey-boxes .index_step__bix_X img {
			margin-bottom: 16px;
		}

		.start-journey-section .index_step-line__Z7ooH {
			display: none;
		}

		.start-journey-section .start-journey-boxes .index_step__bix_X {
			width: 100%;
			margin-bottom: 65px;
		}

		.start-journey-section .start-journey-boxes .index_step__bix_X:last-child {
			margin-bottom: 0;
		}

		.start-journey-section .Main_headings {
			margin-bottom: 30px;
		}

		.banner-bottom-section {
			margin-top: 40px;
		}

		.about-banner-image {
			min-height: 740px;
		}

		.banner-top-section .index_titleBox__5j_B3 {
			font-size: 22px;
			line-height: 32px;
			margin: 80px 0 auto;
		}

		.banner-top-section .index_titleBox__5j_B3 .about-headings {
			font-size: 32px;
		}

		.banner-top-section .index_titleBox__5j_B3 p {
			margin-top: 12px;
		}

		.banner-mid-section .index_desBOX__4OhqS {
			margin-top: 20px;
		}

		.banner-bottom-section .index_top__XL9xj {
			line-height: 44px;
			font-size: 26px;
			height: auto;
		}

		.banner-bottom-section .banner-bottom-list {
			margin-bottom: 16px;
		}

		.Trading-Platform-section {
			padding: 40px 0 0 0;
		}

		.Trading-Platform-section .index_titleAll__13_cv {
			font-size: 24px;
			line-height: 30px;
		}

		.Trading-Platform-section .index_desAll__uatml {
			margin-top: 12px;
		}

		.Trading-Platform-section .index_imgBox__5DvnW {
			width: 100%;
			height: 200px;
			margin: 21px auto 0;
			background-repeat: no-repeat;
			background-size: contain;
			background-position: center;
		}

		.Business-scope-section .index_titleAll__e7b9e {
			font-size: 24px;
			line-height: 30px;
			padding-top: 40px;
		}

		.Trading-Categories-section .index_titleBox__u4jkE {
			font-size: 24px;
			line-height: 30px;
		}

		.Trading-Categories-section .index_diversify__xFUaY {
			margin-top: 12px;
		}

		.Trading-Categories-section {
			padding: 40px 0;
		}

		.Business-scope-section .Business-scope-image {
			height: 400px;
			background-position: bottom;
		}

		.Business-scope-section .index_desBOX__qYef9 {
			margin-top: 12px;
		}

		#light .Business-scope-section .Business-scope-image.Mobile_view {
			background-image: url(Public/template/epsilon/img/redesign/about/global_coverage_business_scope_light_mob.png) !important;
		}
	}


	/*--------------MEDIA-START-------------- */
</style>

@endsection