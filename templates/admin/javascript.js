function go(loc) {
	window.location.href = loc;
}

function appendLink(link) {
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