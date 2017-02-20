<?php /* Template Name: Payment */ ?>

<?php get_header(); ?>

<!-- Main -->
<div id="page">
	<!-- <?php get_template_part( 'navigation', get_post_format() ); ?> -->

	<!-- Main -->
	<div id="main" class="container">
		<div class="row">
            <form name="ordersend" action="https://secure.rba.hr/ecgtesthr/enter" method="post">
                <p><input type="hidden" name="Version" value="1" /></p>
                <p><input type="hidden" name="MerchantID" value="1752081" /></p>
                <p><input type="hidden" name="TerminalID" value="E7879881" /></p>
                <p><INPUT type="hidden" name="TotalAmount" value="10000" /></p>
                <p><INPUT type="hidden" name="Currency" value="980" /></p>
                <p><INPUT type="hidden" name="Locale" value="en" /></p>
                <p><input type="hidden" name="SD" value="7248667" /></p>
                <p><input type="hidden" name="OrderID" value="AAAAA002" /></p>
                <p><input type="hidden" name="PurchaseTime" value="080826093914" /></p>
                <p><input type="hidden" name="PurchaseDesc" value="description" /></p>
                <p><input type="hidden" name="Signature" value="baNuGbnzOFh9H6unKTTyJw01ASO7+8Rwgwrm/URuAl1zNtJKJDox88MODpOZyxN5GebOVeyaTro7cgNiTZDEKonCaKkF4BcdQ6C/S2EsXmm0DaEAXZPxOh5b00N/H2hgeYYoLIC/zTpXNk/+cMwU8lLup8/hlTLBkpe/zblOvCY=" /></p>
                <p><input type="submit" value="submit" name="submit" /></p>
            </form>
		</div>
	</div>
	<!-- Main -->
</div>
<!-- /Main -->

<?php get_footer(); ?>

