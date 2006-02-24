function go(loc) {
	window.location.href = loc;
}

function appendLink(baseurl, path, name) {
	var splitted = path.split('.');
	var ext = splitted[splitted.length - 1].toLowerCase();
	var img = Array("jpe", "jpeg", "png", "gif");
	var isImage = false;
	var i;

	for (i = 0; i < img.length; i++) {
		if (img[i] == ext) {
			isImage = true;
			break;
		} else {
			isImage = false;
		}
	}
	
	if (window.opener.document.postForm.format[0].checked ) {
		/* plain */
		if (isImage) {
			link = '<img src="' + baseurl + '/' + path + '" alt="' +
				name + '" />'
		} else {
			link = '<a href="' + baseurl + '/' + path + '">' +
				name + '</a>';
		}
	}
	else if (window.opener.document.postForm.format[1].checked ) {
		/* html */
		link = baseurl + '/' + path;
	}
	else if ( window.opener.document.postForm.format[2].checked ) {
		/* bbcode */
		link = '[url]' + baseurl + '/' + path + '[/url]';
	}
	else if (window.opener.document.postForm.format[3].checked) {
		/* moniwiki */
		link = "attachment:" + escape(name);
	}
	else {
		link = escape(baseurl + '/' + path);
	}

	if(window.opener) {
		var target = window.opener.document.postForm.body;
		target.value += link;
	}
	return false;
}

function switchDiv(field) {
	selected = field.options[field.selectedIndex].value;
	for(i=0; i<field.length; i++) {
		value = field.options[i].value;
		if(value) {
			div = document.getElementById(value);
			if(value == selected) {
				div.className = '';
			} else {
				div.className = 'hide';
			}
		}
	}
	return false;
}

function changeCategory($category) {
	location.href = "admin.php?mode=list&flag=" + $category.value;
}
