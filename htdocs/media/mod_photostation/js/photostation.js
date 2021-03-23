ModPhotoStationUtil = {
	showChooser: function () {
		document.body.style.overflow = 'hidden';
		document.querySelector('#photostation-div-chooser').style.visibility = 'visible';
	},
	origin: (function () {
		var origin;

		if (!window.location.origin) {
			origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
		} else {
			origin = window.location.origin;
		}

		return origin;
	})(),
	initChooser: function() {
		var elm = document.querySelector('#photostation-div-chooser').children[0], query = '';

		if (!elm) {
			return;
		}

		query = 'origin=' + window.encodeURIComponent(ModPhotoStationUtil.origin);
		query += '&lang=' + window.encodeURIComponent(ModPhotoStationUtil.lang);
		query += '&t=1';

		elm.src = ModPhotoStationUtil.target + '/photo/cms/ImageChooser.php?' + query;
	},
	initSlideShow: function () {
		var elm = document.querySelector('#photostation-iframe-slideshow'), query = '';

		if (!elm) {
			return;
		}

		var div = document.querySelector('#photostation-div-slideshow').children[0];
		div.innerHTML = '';
		div.appendChild(elm);


		query = 'origin=' + window.encodeURIComponent(ModPhotoStationUtil.origin);
		query += '&id=' + window.encodeURIComponent(ModPhotoStationUtil.photostation_id);

		elm.src = ModPhotoStationUtil.target + '/photo/cms/slideshow.php?' + query;
	},
	initLightBox: function () {
		var elm = document.querySelector('#photostation-iframe-lightbox'), query = '';

		if (!elm) {
			return;
		}

		query = 'origin=' + window.encodeURIComponent(ModPhotoStationUtil.origin);
		query += '&id=' + window.encodeURIComponent(ModPhotoStationUtil.photostation_id);
		query += '&target=' + window.encodeURIComponent(ModPhotoStationUtil.target);
		query += '&lang=' + window.encodeURIComponent(ModPhotoStationUtil.lang);
		query += '&lang=' + window.encodeURIComponent(ModPhotoStationUtil.lang);
		query += '&share=' + window.encodeURIComponent(ModPhotoStationUtil.photostation_share);

		elm.src = ModPhotoStationUtil.target + '/photo/cms/lightbox.php?' + query;
	}
};

window.addEventListener('message', function (event) {
	switch (event.data.cls) {
		case 'lightbox': {
			switch (event.data.act) {
				case 'ready': {
					ModPhotoStationUtil.originLightbox = event.data.data.origin;
					break;
				}
				case 'show': {
					var lightbox = document.querySelector('#photostation-iframe-lightbox');
					lightbox.contentWindow.postMessage(event.data.data, ModPhotoStationUtil.originLightbox);
					lightbox.style.visibility = 'visible';
					break;
				}
				case 'hide': {
					document.querySelector('#photostation-iframe-lightbox').style.visibility = 'hidden';
					break;
				}
				default: {
					console.log('Unknown action for lightbox: ' + event.data.act);
					break;
				}
			}
			break;
		}
		case 'slideshow': {
			switch (event.data.act) {
				case 'show': {
					document.querySelector('#photostation-iframe-slideshow').style.visibility = 'visible';
					break;
				}
				default: {
					console.log('Unknown action for slideshow: ' + event.data.act);
					break;
				}
			}
			break;
		}
		case 'chooser': {
			switch (event.data.act) {
				case 'ready': {
					document.querySelector('#photostation-button-chooser').disabled = false;
					break;
				}
				case 'insert': {
					var slideshow = document.querySelector('#photostation-iframe-slideshow');
					slideshow.style.visibility = 'hidden';
					slideshow.src = ModPhotoStationUtil.target + '/photo/cms/slideshow.php?origin=' + ModPhotoStationUtil.origin + '&id=' + event.data.data.id;
					document.querySelector('#photostation-iframe-lightbox').src = ModPhotoStationUtil.target + '/photo/cms/lightbox.php?origin=' + ModPhotoStationUtil.origin + '&id=' + event.data.data.id + '&target=' + encodeURIComponent(ModPhotoStationUtil.target) + '&lang=' + ModPhotoStationUtil.lang + '&share=' + event.data.share;

					/*
					var data = new FormData();
					data.append('option', 'com_ajax');
					data.append('module', 'photostation');
					data.append('format', 'raw');
					data.append('method', 'set');
					data.append('id', event.data.data.record.id);
					data.append('share', event.data.data.share);
					*/
					var xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function () {
						if (this.readyState !== this.DONE) {
							return;
						}
					};
					xhr.open('GET', 'index.php?option=com_ajax&module=photostation&format=raw&method=set&id=' + event.data.data.id + '&share=' + event.data.share);
					xhr.send();
					/* falls through */
				}
				case 'hide': {
					document.querySelector('#photostation-div-chooser').style.visibility = 'hidden';
					document.body.style.overflow = 'scroll';
					break;
				}
				default: {
					console.log('Unknown action for chooser: ' + event.data.act);
					break;
				}
			}
			break;
		}
		default: {
			console.log('Unknown message class: ' + event.data.cls);
			break;
		}
	}
});
