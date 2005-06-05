function on_load(max_x) {
	objs = document.getElementsByTagName('IMG');
	for(i=0; i<objs.length; i++) {
		obj = objs[i];
		if(obj.className != 'bbcode') continue;
		img = new Image();
		img.src = obj.src;
		if(max_x<img.width){
			cut=img.width/max_x;
			obj.width = img.width/cut;
			obj.height = img.height/cut;
		}else{
			obj.width = img.width;
			obj.height = img.height;
		}
	}
	return false;
}

function seturltarget(link, target) {
	link.target = target;
	return true;
}

function moreless(obj) {
	obj.innerHTML=(obj.nextSibling.style.display=='none')?'LESS':'MORE';
	obj.nextSibling.style.display=(obj.nextSibling.style.display=='none')?'block':'none';
}

function copy_clip(copyText){
	if(window.clipboardData){ // IE
		window.clipboardData.setData('Text',copyText);
	}
	else if (window.netscape){ // Mozilla
		netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
          
		// Store support string in an object.
		var str = 
			Components.classes["@mozilla.org/supports-string;1"]
			.createInstance(Components.interfaces.nsISupportsString);
		if (!str) return false;
		str.data=copyText;
          
		// Make transferable.
		var trans = 
			Components.classes["@mozilla.org/widget/transferable;1"]
			.createInstance(Components.interfaces.nsITransferable);
		if (!trans) return false;
          
		// Specify what datatypes we want to obtain, which is text in this case.
		trans.addDataFlavor("text/unicode");
		trans.setTransferData("text/unicode",str,copyText.length*2);
          
		var clipid=Components.interfaces.nsIClipboard;
		var clip =
			Components.classes["@mozilla.org/widget/clipboard;1"]
			.getService(clipid);
		if (!clip) return false;
          
		clip.setData(trans,null,clipid.kGlobalClipboard);
	}
	alert("Copied into a ClipBoard");
	return false;
}

