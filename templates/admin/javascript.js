function go(loc) {
window.location.href = loc;
}


function appendLink(link)
{
var target = opener.document.postForm.body;
target.value += link;
}
