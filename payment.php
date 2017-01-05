<?php /* Template Name: Payment */ ?>

<?php get_header(); ?>

<!-- Main -->
<div id="page">
	<?php get_template_part( 'navigation', get_post_format() ); ?>

	<!-- Main -->
	<div id="main" class="container">
		<div class="row">
            <form name="ordersend" action="https://secure.rba.hr/ecgtesthr/enter" method="post">
                <p><input type="hidden" name="Version" value="1" >
                <p><input name="MerchantID" value="1752081"  >
                <p><input name="TerminalID" value="E7879881"  >
                <p><INPUT name="TotalAmount" value="10000">
                <p><INPUT name="Currency" value="980" >
                <p><INPUT name="Locale" value="en">
                <p><input name="SD" value="7248667" >
                <p><input name="OrderID" value="AAAAA002"  >
                <p><input name="PurchaseTime" value="080826093914">  
                <p><input name="PurchaseDesc" value="description" >
                <p><input name="Signature" value="baNuGbnzOFh9H6unKTTyJw01ASO7+8Rwgwrm/URuAl1zNtJKJDox88MODpOZyxN5GebOVeyaTro7cgNiTZDEKonCaKkF4BcdQ6C/S2EsXmm0DaEAXZPxOh5b00N/H2hgeYYoLIC/zTpXNk/+cMwU8lLup8/hlTLBkpe/zblOvCY=" >
                <p><input type="submit" value="submit" name="submit" />
            </form>
		</div>
	</div>
	<!-- Main -->
</div>
<!-- /Main -->

<?php get_footer(); ?>

