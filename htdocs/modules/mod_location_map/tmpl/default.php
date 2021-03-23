  <?php
  /**
   * @version 3.0
   * @package LocationMap
   * @copyright 2009 OrdaSoft
   * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
   * @description Location map for Joomla 3.0
  */
  //require_once(dirname(__FILE__)."/mod_location_map.php");
  defined( '_JEXEC' ) or die( 'Restricted access' );
  $pr = rand();

  $doc = JFactory::getDocument();

  if( modOSLocationHelper::checkJavaScriptIncluded("maps.googleapis.com") === false )   {

    $api_key = $params->get('map_api_key') ? ("key=" . 
      $params->get('map_api_key') ) : 
      JFactory::getApplication()->enqueueMessage(
        "<a target='_blank' href='//developers.google.com/maps/documentation/geocoding/get-api-key'>" . 
        "https://developers.google.com/maps/documentation/javascript/get-api-key" . "</a>", 
        "For use Google Map - you need set Google map key parameters in Location Map module");
    $doc->addScript("//maps.googleapis.com/maps/api/js?$api_key",'text/javascript', true,true);
    
  }

  ?>
  <style>
  #map_canvas<?php echo $pr; ?> img {
  max-width: none !important;
  }
  </style>
  <noscript>Javascript is required to use Google Maps location Joomla module <a href="http://ordasoft.com/location-map.html">Google Maps location module
  for Joomla </a>, 

  <a href="http://ordasoft.com/location-map.html">Google Maps location module
  for Joomla</a></noscript>
  <script type="text/javascript">
   function os_location_map_init() {
       var os_loc_map;
       var os_loc_marker = new Array();
       var os_loc_infowindow = new Array();
       var os_loc_options = {
         zoom: <?php echo $params->get('zoom'); ?>,
         center: new google.maps.LatLng(<?php echo $params->get('latitude'); ?>, <?php echo $params->get('longitude'); ?>),
         <?php if ($params->get('menu_map') == 0) echo "mapTypeControl: false,";?>
         <?php if ($params->get('control_map') == 0) echo "zoomControl: false, panControl: false, streetViewControl: false,";?>
         mapTypeId: google.maps.MapTypeId.HYBRID
       };
       var os_loc_map = new google.maps.Map(document.getElementById("os_loc_map_canvas<?php echo $pr; ?>"), os_loc_options);

       <?php
       $text = explode("\n", $params->get('messag'));
       for ($i = 0; $i < count($text); $i++)
       {
          $value = explode(";", $text[$i]);
             ?>
                os_loc_marker.push(new google.maps.Marker({
                position: new google.maps.LatLng(<?php echo trim($value[0]); ?>, <?php echo trim($value[1]); ?>),
                map: os_loc_map
          }));

          os_loc_infowindow.push(new google.maps.InfoWindow({
          content: "<?php echo trim($value[2]); ?>"
          }));
          
          google.maps.event.addListener(os_loc_marker[<?php echo $i; ?>], 'mouseover', function() {
          <?php
          for ($j = 0; $j < count($text); $j++)
          {
          ?>
              os_loc_infowindow[<?php echo $j; ?>].close(os_loc_map,os_loc_marker[<?php echo $j; ?>]);
          <?php
          }
          ?>
          os_loc_infowindow[<?php echo $i; ?>].open(os_loc_map,os_loc_marker[<?php echo $i; ?>]);
          });
       <?php
       }
       ?>
  };
  
  function os_loc_map_addLoadEvent(func) {
     var oldonload = window.onload;
     if (typeof window.onload != 'function') {
        window.onload = func;
      } else {
        window.onload = function() {
          if (oldonload) {
            oldonload();
          }
          func();
        }
      }
    }
  os_loc_map_addLoadEvent(os_location_map_init);
  
  </script>
  
<?php 
if ($params->get('moduleclass_sfx') != '') { ?>
    <div class="<?php echo $params->get('moduleclass_sfx'); ?>"> 
<?php } ?>
  <div id="os_loc_map_canvas<?php echo $pr; ?>" style=
      "width: <?php echo $params->get('map_width');?>px; height: <?php echo $params->get('map_height'); ?>px;
      border: 1px solid black; float: rigth;" >
  </div>
  <br>
  <div style="text-align: center;"><a href="http://ordasoft.com" style="font-size: 10px;">Powered by OrdaSoft!</a></div>
<?php if ($params->get('moduleclass_sfx') != '') { ?>
    </div> <?php
}?>


