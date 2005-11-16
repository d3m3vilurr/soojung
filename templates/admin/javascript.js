function go(loc) {
	window.location.href = loc;
}

function appendLink(baseurl, path, name) {

	if (window.opener.document.postForm.format[0].checked ) {
		/* plain */
		link = '<a href="' + baseurl + '/' + path + '">' + name + '</a>';
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
