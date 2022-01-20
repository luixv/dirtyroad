
<?php

/**
 * Members Directory Map Item Template - For Item Popup on Map
 *
 * You can copy this file to your-theme/buddypress/members/
 * and then edit the layout.
 *
 * For an example of adding another field to this template,
 * please see: bp-maps-for-members/readme-add-popup-field.txt
 *
 */

?>

<div class="members-map-pin-popup">

	<?php if ( isset( $avatar ) ) echo $avatar; ?>

	<?php if ( isset( $title ) ) echo $title; ?>

	<?php if ( isset( $address ) ) echo "<p>" . $guests . " " . $address . "</p>" ?>
		
	<?php if ( isset( $available ) ) {
			if (substr_count($available, 'Yes') > 0) {			
				if ( isset( $guests ) ) {				
					echo "<p style='color:LimeGreen; font-weight: bolder' >" . $available . ", " . $guests . " guest(s)</p>"; 
				}	else {
					echo "<p style='color:LimeGreen; font-weight: bolder' >" . $available . "</p>";
				}				
			} else {
					echo "<p style='color:crimson; font-weight: bolder' >" . $available . "</p>";
			}				
	}
	?>
</div>