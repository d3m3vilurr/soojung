function go(loc) {
	window.location.href = loc;
}

function appendLink(link)
{
	if(window.opener) {
		var target = window.opener.document.postForm.body;
		target.value += link;
	}
	return false;
}
