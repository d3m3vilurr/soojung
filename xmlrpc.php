<?php
include("libs/xmlrpcs.inc");
include("libs/xmlrpc.inc");

include("soojung.php");

function _error($errcode, $errstring) {
  global $xmlrpcerruser;
  return new xmlrpcresp(0, $xmlrpcerruser + $errcode, $errstring);
}

function _login($su, $sp) {
  if (isset($su) == false || ($su->scalartyp() != "string") ||
      isset($sp) == false || ($sp->scalartyp() != "string")) {
    return _error(3, "Incorrect parameters");
  }

  global $blog_baseurl, $admin_name, $admin_email, $admin_password;
  $username = $su->scalarval();
  $password = $sp->scalarval();

  if ($username != $admin_name || md5($password) != $admin_password) {
    return _error(6, "Login failed");
  }
  return TRUE;
}

function _blogger_extractTitle($body) {
  return _blogger_matchTag('title',$body);
}

function _blogger_extractCategory($body) {
  return _blogger_matchTag('category',$body);
}	
	
function _blogger_matchTag($tag, $body) {
  if (preg_match("/<" . $tag .">(.+?)<\/".$tag.">/is",$body,$match)) 
    return $match[1];
  else
    return "";
}

function _blogger_removeSpecialTags($body) {
  $body = preg_replace("/<title>(.+?)<\/title>/","",$body);
  $body = preg_replace("/<category>(.+?)<\/category>/","",$body);
  return trim($body);
}

function _blogger_specialTags($entry) {
  $result = "<title>". $entry['title']."</title>";
  $result .= "<category>".$entry['category']."</category>";
  return $result;
}

function blogger_newPost($params) {
  $su = $params->getParam(2);
  $sp = $params->getParam(3);
  $sc = $params->getParam(4);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $content = $sc->scalarval();
  $title = _blogger_extractTitle($content);
  $category = _blogger_extractCategory($content);
  $body = _blogger_removeSpeicalTags($content);

  $blogid = entry_new($title, $body, time(), $category);

  return new xmlrpcresp(new xmlrpcval($blogid, "string"));
}

function blogger_editPost($params) {
  $sid = $params->getParam(1);
  $su = $params->getParam(2);
  $sp = $params->getParam(3);
  $sc = $params->getParam(4);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $content = $sc->scalarval();
  $title = _blogger_extractTitle($content);
  $category = _blogger_extractCategory($content);
  $body = _blogger_removeSpeicalTags($content);

  $blogid = $sid->scalarval();
  $e = get_entry($blogid);
  $date = $e["date"];

  entry_eidt($blogid, $title, $body, $date, $category);

  return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}

function blogger_deletePost($params) {
  $sid = $params->getParam(1);
  $su = $params->getParam(2);
  $sp = $params->getParam(3);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $blogid = $sid->scalarval();
  entry_delete($blogid);

  return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}

function blogger_getRecentPosts($params) {
  $su = $params->getParam(2);
  $sp = $params->getParam(3);
  $sn = $params->getParam(4);
  $num = $sn->scalarval();

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $structarray = array();
  $entries = get_recnet_entries($num);
  foreach($entries as $e) {
    $content = _blogger_specialTags($e) . $e['body'];
    $entrystruct = new xmlrpcval(array("dateCreated" => new xmlrpcval(iso8601_encode(strtotime($e['date'])), "dateTime.iso8601"),
				       "userid" => new xmlrpcval($admin_name, "string"),
				       "postid" => new xmlrpcval($e['id'], "string"),
				       "content" => new xmlrpcval($content, "string")
				       ), "struct");
    array_push($structarray, $entrystruct);
  }
  return new xmlrpcresp(new xmlrpcval($sturctarray, "array"));
}

function blogger_getUsersBlogs($params) {
  $su = $params->getParam(1);
  $sp = $params->getParam(2);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  global $blog_baseurl, $blog_name;

  $structarray = array();
  $blogstruct = new xmlrpcval(array("url" => new xmlrpcval($blog_baseurl, "string"),
				    "blogid" => new xmlrpcval($blog_name, "string"),
				    "blogName" => new xmlrpcval($blog_name, "string")
				    ), "struct");
  array_push($structarray, $blogstruct);
  return new xmlrpcresp(new xmlrpcval($sturctarray, "array"));
}

function blogger_getUserInfo($params) {
  $su = $params->getParam(1);
  $sp = $params->getParam(2);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }
  
  global $blog_baseurl, $admin_name, $admin_email, $admin_password;
  $result_struct = new xmlrpcval(array("nickname" => new xmlrpcval($admin_name, "string"),
			   "userid" => new xmlrpcval($admin_name, "string"),
			   "url" => new xmlrpcval($blog_baseurl, "string"),
			   "email" => new xmlrpcval($admin_email, "string"),
			   "lastname" => new xmlrpcval("", "string"),
			   "firstname" => new xmlrpcval($admin_name, "string"),
			   ),"struct");
  return new xmlrpcresp($result_struct);
}

$s = new xmlrpc_server(array("blogger.newPost" => array("function" => "blogger_newPost"),
			     "blogger.editPost" => array("function" => "blogger_editPost"),
			     "blogger.deletePost" => array("function" => "blogger_deletePost"),
			     "blogger.getRecentPosts" => array("function" => "blogger_getRecentPosts"),
			     "blogger.getUsersBlogs" => array("function" => "blogger_getUsersBlogs"),
			     "blogger.getUserInfo" => array("function" => "blogger_getUserInfo")));
?>