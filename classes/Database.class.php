<?php

class Database {
  var $filename;
  var $field;
  var $block;

  function Database($filename) {
    if (empty($filename)) {
      $field = $block = array();
      return;
    }

    $this->filename = $filename;
    $fd = fopen($filename, "r");

    $this->field = array();
    while(trim($line = fgets($fd, 1024))) {
      list($name, $value) = explode(':', $line, 2);
      $name = strtolower(trim($name));
      $value = trim($value);
      if(is_array($this->field[$name])) {
	$this->field[$name][] = $value;
      } elseif(isset($this->field[$name])) {
	$this->field[$name] = array($this->field[$name], $value);
      } else {
	$this->field[$name] = $value;
      }
    }

    $this->block = ftell();
    fclose($fd);
  }

  function getBlock() {
    if(!is_array($this->block)) {
      $fd = fopen($this->filename, "r");
      fseek($fd, $this->block);
      $this->block = array();
      $block = "";
      $sep = $this->field["separator"];
      while(!feof($fd)) {
	$line = fgets($fd, 4096);
	if($sep && trim($line) == $sep) {
	  $this->block[] = $block;
	  $block = "";
	} else {
	  $block .= $line;
	}
      }
      $this->block[] = $block;
      fclose($fd);
    }

    return $this->block;
  }

  function write($filename, $sep = NULL) {
    if(is_null($sep)) $sep = $this->field["separator"];
    if($sep) $this->field["separator"] = $sep = trim($sep);

    $fd = fopen($filename, "w");
    foreach($this->field as $key => $value) {
      $key = ucfirst(strtolower($key));
      if(!is_array($value)) $value = array($value);
      foreach($value as $_value) {
	$_value = trim(str_replace("\r", "", str_replace("\n", "", $_value)));
	fwrite($fd, "$key: $_value\r\n");
      }
    }
    
    fwrite($fd, "\r\n");
    $flag = true;
    foreach($this->getBlock() as $block) {
      if($flag) {
	$flag = false;
      } else {
	fwrite($fd, "$sep\r\n");
      }
      fwrite($fd, $block);
      if($block && substr($block, -1) != "\n") {
	fwrite($fd, "\r\n");
      }
    }
    fclose($fd);
  }

  function makeSeparator($prefix = "--soojung--") {
    $sep = $prefix;
    $blocks = $this->getBlock();
    while(1) {
      $sep = $prefix.md5($prefix).md5(microtime());
      $flag = true;
      foreach($blocks as $block) {
	if(strpos($block, $sep) !== false) {
	  $flag = false;
	  break;
	}
      }
      if($flag) return $sep;
    }
  }
}

# vim: ts=8 sw=2 sts=2 noet
?>
