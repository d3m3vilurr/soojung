function fold_sidebar(objid) {
	document.getElementById(objid).className =
		(document.getElementById(objid).className ? '' : 'div_hide');
	return false;
}

function correct_bbcode_image() {
	objs = document.getElementsByTagName('IMG');
	for(i=0; i<objs.length; i++) {
		obj = objs[i];
		if(obj.className != 'bbcode') continue;
		img = new Image();
		img.src = obj.src;
		obj.width = img.width;
		obj.height = img.height;
	}
	return false;
}
