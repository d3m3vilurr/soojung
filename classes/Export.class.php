<?php

class Export {

  /**
   * static method
   */
  function export() {
    $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    $xml .= "<soojung>\n";
    $xml .= Export::toXml("contents");
    $xml .= "</soojung>";
    return $xml;
  }

  /**
   * private, static method
   */
  function toXml($path) {
    if ($dh = opendir($path)) {
      while (($file = readdir($dh)) !== false) {
	if ($file == ".." || $file == ".") {
	  continue;
	}
	$filename = $path . '/' . $file;
	if (is_dir($filename)) {
	  $xml .= Export::toXml($filename);
	} else {
	  $xml .= Export::fileToXml($filename);
	}
      }
      closedir($dh);
    }
    return $xml;
  }

  /**
   * private, static method
   */
  function fileToXml($filename) {
    $fd = fopen($filename, "rb");
    $data = fread($fd, filesize($filename));
    $data = htmlspecialchars($data);
    fclose($fd);

    $xml = "\t<file>\n";
    $xml .= "\t\t<name>" . $filename . "</name>\n";
    $xml .= "\t\t<data>" . $data . "</data>\n";
    $xml .= "\t</file>\n";
    return $xml;
  }

}

# vim: ts=8 sw=2 sts=2 noet
?>
