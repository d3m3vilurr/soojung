<?php
include("libs/xmlrpc.inc");
include("libs/xmlrpcs.inc");

function hello($params) {
	return new xmlrpcresp(new xmlrpcval("hello world", "string"));
}

$s = new xmlrpc_server(array("examples.hello" => array("function" => "hello")));
?>
