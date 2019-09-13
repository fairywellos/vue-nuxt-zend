<template>
    <Header v-if="$device.isDesktop"/>
    <HeaderMobile v-if="$device.isMobileOrTablet"/>
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="banner_content text-center">
                <h2>Turn what you have, into<br/> what you want.</h2>
                <a href="https://tradelist.net/">Get started</a>
            </div>
        </div>
    </section>

  
    <section id="why-us" class="wow fadeIn">
      <div class="container">
        <header class="section-header">
          <h3>Why choose us?</h3>
		  <img src="~assets/img/thread.png" class="thread-divider">
          <p>Tradelist is your partner. A free resource that is only successful if you are successful. We're interested in connecting people and driving commerce in any form. Be sure to link your preferred e-commerce account to your listings and profiles to drive traffic back to your own site. We're happy to help!</p>
        </header>

        <div class="row row-eq-height justify-content-center">

          <div class="col-lg-4 mb-4">
            <div class="card wow bounceInUp">
                <i class="fa fa-user-o"></i>
              <div class="card-body">
                <p class="card-text">Create an Account</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 mb-4">
            <div class="card wow bounceInUp">
                <i class="fa fa-file-text-o"></i>
              <div class="card-body">
                <p class="card-text">Create as many listings as you like</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 mb-4">
            <div class="card wow bounceInUp">
                <i class="fa fa-briefcase"></i>
              <div class="card-body">
                <p class="card-text">Tradelist will begin marketing your business and listings free of charge.</p>
              </div>
            </div>
          </div>

        </div>
		
		<div class="row">
			<div class="col-lg-12 text-center">
				<a href="https://tradelist.net/"><img src="~assets/img/tradelistad.gif" class="add_image wow fadeInUp"></a>
			</div>
		</div>

        

      </div>
    </section>
    <section id="about">
      <div class="container">

        <header class="section-header">
          <h3>Benefits</h3>
		  <img src="~assets/img/thread.png" class="thread-divider">
          <p>Simply create a listing, browse for other user's listings, and make 
a trade or cash offer.</p>
        </header>

        <div class="row about-container">

          <div class="col-lg-6 content order-lg-1 order-2">
            <p>
              Tradelist was created as a network that empowers people. A network that supports and improves human relations, without relying on money. It’s a trading and bartering platform that enables people to create value, network, solve problems and to ultimately be successful.
            </p>

            <div class="icon-box wow fadeInUp">
              <div class="icon"><i class="fa fa-money"></i></div>
              <h4 class="title"><a href="">Negotiate cash and trade transactions</a></h4>
              <p class="description">Tradelist values your services and goods more than the dollar, so you can make trades and exchanges for free.</p>
            </div>

            <div class="icon-box wow fadeInUp" data-wow-delay="0.2s">
              <div class="icon"><i class="fa fa-exchange"></i></div>
              <h4 class="title"><a href="">Complete cash transactions on your preferred site</a></h4>
              <p class="description">Tradelist is where Etsy, Craigslist, Airbnb, VRBO, Poshmark, Facebook Marketplace and more become one. Anything you search for on those sites, you can find here.</p>
            </div>

            <div class="icon-box wow fadeInUp" data-wow-delay="0.4s">
              <div class="icon"><i class="fa fa-users"></i></div>
              <h4 class="title"><a href="">Network with industry professionals</a></h4>
              <p class="description">The easiest way to build trade networks with multiple sellers is with our trade circles feature. When you select something you like, Tradelist will match you with potential trade partners and suggest a trade circle.</p>
            </div>

          </div>

          <div class="col-lg-6 background order-lg-2 order-1 wow fadeInUp">
           <img src="~assets/img/about-extra-2.svg" class="img-fluid" alt="">
          </div>
        </div>

        

      </div>
    </section>
    
  </template>

  <script>
	import Header from "~/components/Header.vue";
	import HeaderMobile from "~/components/HeaderMobile.vue";
	
	export default {
		components: {
			Header, HeaderMobile
		},
		head() {
			return {
				title: "Signup",
				meta: [
					{
						hid: "signup",
						name: "signup",
						content: "Signup description"
					}
				]
			};
		},
		methods: {
			homeModal() {
				this.$nuxt.$emit('toSignUp', true);
			}
		},
	};
</script>


<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	@import "~assets/scss/components/_informational-pages.scss";
</style>
<style>
    @import "https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700";
    @import "~assets/lib/bootstrap/css/bootstrap.min.css";
    @import "~assets/lib/font-awesome/css/font-awesome.min.css";
    @import "~assets/lib/animate/animate.min.css";
    @import "~assets/lib/ionicons/css/ionicons.min.css";
    @import "~assets/lib/owlcarousel/assets/owl.carousel.min.css";
    @import "~assets/lib/lightbox/css/lightbox.min.css";
    @import "~assets/css/style.css";
</style>