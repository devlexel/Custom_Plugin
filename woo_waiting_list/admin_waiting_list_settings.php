<?php 
/* waiting list settings
this includes product id, text label and button name
store in wp_options
*/

if(isset($_POST['submit'])){

$wlproductId = $_POST['wlproductId'];
$wlproductLabel = $_POST['wlproductLabel'];
$wlproductButton = $_POST['wlproductButton'];
add_option( 'wlproductId', $wlproductId, '', 'yes' );
add_option( 'wlproductLabel', $wlproductLabel, '', 'yes' );
add_option( 'wlproductButton', $wlproductButton, '', 'yes' );
update_option( 'wlproductId', $wlproductId );
update_option( 'wlproductLabel', $wlproductLabel );
update_option( 'wlproductButton', $wlproductButton );	
}
$swlProductId ='';
$swlProductLabel ='';
$swlProductButton ='';
if(get_option( 'wlproductId' )){ $swlProductId = get_option( 'wlproductId' ); }
if(get_option( 'wlproductLabel' )){ $swlProductLabel = get_option( 'wlproductLabel' ); }
if(get_option( 'wlproductButton' )){ $swlProductButton = get_option( 'wlproductButton' ); }
?>
<div id="wpbody">
	<h3>Settings</h3>
	<form method="post" id="waitingfmr">
		<p><label>Product ID</label><span><input type="number" name="wlproductId" value="<?php echo esc_html($swlProductId); ?>" required></span></p>
		<p><label>Product Label</label><span><input type="text" name="wlproductLabel" value="<?php echo esc_html($swlProductLabel); ?>" required></span></p>
		<p><label>Product Button</label><span><input type="text" name="wlproductButton" value="<?php echo esc_html($swlProductButton); ?>" required></span></p>
		<p><input type="submit" name="submit" value="Save Settings"></p>
	</form>
</div>
