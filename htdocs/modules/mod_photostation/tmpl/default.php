<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<link rel="stylesheet" type="text/css" href="media/mod_photostation/css/photostation.css">
<script src="media/mod_photostation/js/photostation.js"></script>
<script>
ModPhotoStationUtil.photostation_id = '<?php echo $data['id']; ?>';
ModPhotoStationUtil.photostation_share = '<?php echo $data['share']; ?>';
ModPhotoStationUtil.target = '<?php echo $data['target']; ?>';
ModPhotoStationUtil.lang = '<?php echo $data['lang']; ?>';
</script>
<?php
if (JFactory::getUser()->authorise('core.admin')) {
?>
<input type="button" id="photostation-button-chooser" class="photostation-button-chooser" value="Photo Station Selector" onclick="ModPhotoStationUtil.showChooser();" disabled>
<?php
}
?>
<div id="photostation-div-slideshow" class="photostation-div-slideshow">
	<div>Connecting to <?php echo $data['target']; ?>...
		<iframe id="photostation-iframe-slideshow" class="photostation-iframe-slideshow" src="about:blank"></iframe>
	</div>
</div>
<script>
(function () {
	var lightbox = document.createElement('iframe');
	lightbox.id = 'photostation-iframe-lightbox';
	lightbox.className = 'photostation-iframe-lightbox';
	lightbox.src = 'about:blank';
	document.body.appendChild(lightbox);

	var div = document.createElement('div');
	div.id = 'photostation-div-chooser';
	div.className = 'photostation-div-chooser';

	var chooser = document.createElement('iframe');
	chooser.src = 'about:blank';
	div.appendChild(chooser);
	document.body.appendChild(div);

	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (this.readyState !== this.DONE) {
			return;
		}
		try {
			var resp = JSON.parse(this.responseText);
			if (resp.boot_done && resp.success) {
				var t = setInterval(function () {
					if (document.querySelector('#photostation-div-chooser')
							&& document.querySelector('#photostation-div-slideshow')
							&& document.querySelector('#photostation-iframe-lightbox')) {
						clearInterval(t);
						ModPhotoStationUtil.initChooser();
						ModPhotoStationUtil.initSlideShow();
						ModPhotoStationUtil.initLightBox();
					}
				}, 100);
			} else {
				throw -1;
			}
		} catch (e) {
			document.querySelector('#photostation-div-slideshow').children[0].innerHTML = 'Invalid DiskStation: ' + ModPhotoStationUtil.target;
		}
	};
	xhr.open('GET', ModPhotoStationUtil.target + '/webman/pingpong.php?action=cors');
	xhr.send();
})();
</script>
