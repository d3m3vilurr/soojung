<?php
include_once("libs/xmlrpcs.inc");
include_once("libs/xmlrpc.inc");

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

  writewrite($params);
  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $content = $sc->scalarval();
  $title = _blogger_extractTitle($content);
  $category = _blogger_extractCategory($content);
  $body = _blogger_removeSpecialTags($content);

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
  $body = _blogger_removeSpecialTags($content);

  $blogid = $sid->scalarval();
  $e = get_entry($blogid);
  $date = $e["date"];

  entry_edit($blogid, $title, $body, $date, $category);

  return new xmlrpcresp(new xmlrpcval(1, "boolean"));
}

function blogger_getPost($params) {
  $sid = $params->getParam(1);
  $su = $params->getParam(2);
  $sp = $params->getParam(3);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $blogid = $sid->scalarval();
  $entry = get_entry($blogid);
  
  $content = _blogger_specialTags($e) . $e['body'];
  $entrystruct = new xmlrpcval(array("content" => new xmlrpcval($content, "string"),
				     "userid" => new xmlrpcval($admin_name, "string"),
				     "postid" => new xmlrpcval($e['id'], "string"),
				     "dateCreated" => new xmlrpcval(iso8601_encode(strtotime($e['date'])), "dateTime.iso8601")
				     ), "struct");

  return $entrystruct;
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
    $entrystruct = new xmlrpcval(array("content" => new xmlrpcval($content, "string"),
				       "userid" => new xmlrpcval($admin_name, "string"),
				       "postid" => new xmlrpcval($e['id'], "string"),
				       "dateCreated" => new xmlrpcval(iso8601_encode(strtotime($e['date'])), "dateTime.iso8601")
				       ), "struct");
    $structarray[] = $entrystruct;
  }
  return new xmlrpcresp(new xmlrpcval($structarray, "array"));
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
  $structarray[] = $blogstruct;
  return new xmlrpcresp(new xmlrpcval($structarray, "array"));
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

function metaWeblog_newPost($params) {
  // $sb = $params->getParam(1);
  $su = $params->getParam(1);
  $sp = $params->getParam(2);
  $ts = $params->getParam(3);
  //  $spb = $params->getParam(4);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }
  
  $item = $ts->getval();
  $title = $item['title'];
  $content = $item['description'];
  $category = $item['category'];
  $body = $content;

  $blogid = entry_new($title, $body, time(), $category);

  return new xmlrpcresp(new xmlrpcval($blogid, "string"));
}

function metaWeblog_editPost($params) {
  $sid = $params->getParam(0);
  $su = $params->getParam(1);
  $sp = $params->getParam(2);
  $ts = $params->getParam(3);
  //  $spb = $params->getParam(4);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }
  
  $item = $ts->getval();
  $title = $item['title'];
  $body = $item['description'];
  $category = $item['category'];


  $blogid = $sid->scalarval();

  $ret = entry_edit($blogid, $title, $body, time(), $category);

  return new xmlrpcresp(new xmlrpcval($ret, "string"));
}

function metaWeblog_getPost($params) {
  $sid = $params->getParam(0);
  $su = $params->getParam(1);
  $sp = $params->getParam(2);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $blogid = $sid->scalarval();
  $e = get_entry($blogid);
  
  $content = $e['body'];
  $categories = array ($e['category']);
  $entrystruct = new xmlrpcval(array("dateCreated" => new xmlrpcval(iso8601_encode(strtotime($e['date'])), "dateTime.iso8601"),
				     "userid" => new xmlrpcval($admin_name, "string"),
				     "postid" => new xmlrpcval($e['id'], "string"),
				     "title" => new xmlrpcval($e['title'], "string"),
				     "link" => new xmlrpcval($e['link'], "string"),
				     "categories" => new xmlrpcval($categories, "array"),
				     "description" => new xmlrpcval($$content, "string")
				     ), "struct");

  return $entrystruct;
}

function metaWeblog_newMediaObject($params) {
  $sid = $params->getParam(0);
  $su = $params->getParam(1);
  $sp = $params->getParam(2);
  $ts = $params->getParam(3);
  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }
  $obj = $ts->getval();
  if (!isset ($item['name']) || !isset ($item['type'])||!isset ($item['bits']) ) {
    // not enough parameter
  }
      
  $name = $item['name'];
  $type = $item['type'];
  $bits = $item['bits'];

  return new xmlrpcresp(new xmlrpcval("not yet", "string"));
}

function metaWeblog_getCategories($params) {
  //  $sid = $params->getParam(0);
  $su = $params->getParam(1);
  $sp = $params->getParam(2);

  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }
  
  $categories = get_category_list();
  foreach($categories as $c) {
    $categorystruct = new xmlrpcval(array("Description" => $c['name'],
					  "htmlUrl" => $c['link'],
					  "rssUrl" => $c['rss']), 
				    "struct");
    $structarray[] = $categorystruct;
  }
  return new xmlrpcresp(new xmlrpcval($structarray, "array"));
}

function metawebLog_getRecentPosts($params) {
  $su = $params->getParam(1);
  $sp = $params->getParam(2);
  $sn = $params->getParam(3);
  $num = $sn->scalarval();
  $r = _login($su, $sp);
  if ($r !== TRUE) {
    return $r;
  }

  $structarray = array();
  $entries = get_recnet_entries($num);
  foreach($entries as $e) {
    $content = _blogger_specialTags($e) . $e['body'];
    $categories = array ($e['category']);
    $entrystruct = new xmlrpcval(array("dateCreated" => new xmlrpcval(iso8601_encode(strtotime($e['date'])), "dateTime.iso8601"),
				       "userid" => new xmlrpcval($admin_name, "string"),
				       "postid" => new xmlrpcval($e['id'], "string"),
				       "title" => new xmlrpcval($e['title'], "string"),
				       "link" => new xmlrpcval($e['link'], "string"),
				       "categories" => new xmlrpcval($categories, "array"),
				       "description" => new xmlrpcval($$content, "string")
				       ), "struct");

    $structarray[] = $entrystruct;
  }
  return new xmlrpcresp(new xmlrpcval($structarray, "array"));

}

$s = new xmlrpc_server(array("metaWeblog.newPost" => array("function" => "metaWeblog_newPost"),
			     "metaWeblog.editPost" => array("function" => "metaWeblog_editPost"),
			     "metaWeblog.deletePost" => array("function" => "metaWeblog_deletePost"),
			     "metaWeblog.newMediaObject" => array("function" => "metaWeblog_newMediaObject"),
			     "metaWeblog.getCategories" => array("function" => "metaWeblog_getCategories"),
			     "metaWeblog.getRecentPosts" => array("function" => "metaweblog_getRecentPosts"),
			     "blogger.newPost" => array("function" => "blogger_newPost"),
			     "blogger.editPost" => array("function" => "blogger_editPost"),
			     "blogger.deletePost" => array("function" => "blogger_deletePost"),
			     "blogger.getRecentPosts" => array("function" => "blogger_getRecentPosts"),
			     "blogger.getUsersBlogs" => array("function" => "blogger_getUsersBlogs"),
			     "blogger.getUserInfo" => array("function" => "blogger_getUserInfo")));
?>

