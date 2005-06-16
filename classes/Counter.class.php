<?php

class Counter {
  
  function Counter($cookiename = "soojungcountercookie", $ipcount = 10) {
    $this->cookiename = $cookiename;
    $this->ipcount = $ipcount;
    $this->read();
  }

  function read() {
    if ($fd = @fopen("contents/.count", "r")) {
      flock($fd, LOCK_SH);
      $lastdate = trim(fgets($fd, 256));
      $today = intval(fgets($fd, 256));
      $total = intval(fgets($fd, 256));
      $recent = array();
      while (!feof($fd)) {
	$recent[] = trim(fgets($fd, 256));
      }
      flock($fd, LOCK_UN);
      fclose($fd);
    } else {
      $lastdate = date("Y-m-d");
      $today = $total = 0;
      $recent = array();
    }

    $this->today = $today;
    $this->total = $total;
    $this->recent = $recent;
    $this->lastdate = $lastdate;

    return array($today, $total, $recent, $lastdate);
  }

  function write($today, $total, $recent, $lastdate=null) {
    if (is_null($lastdate)) {
      $lastdate = date("Y-m-d");
    }

    if ($fd = @fopen("contents/.count", "w")) {
      flock($fd, LOCK_EX);
      fwrite($fd, "$lastdate\n$today\n$total\n");
      foreach ($recent as $recentitem) {
	fwrite($fd, "$recentitem\n");
      }
      flock($fd, LOCK_UN);
      fclose($fd);
    }

    $this->today = $today;
    $this->total = $total;
    $this->recent = $recent;
    $this->lastdate = $lastdate;
  }

  function isbot() {
    return strstr($_SERVER['HTTP_USER_AGENT'], "bot");
  }

  function update() {
    setcookie($this->cookiename, "on", 0);
    if ($_COOKIE[$this->cookiename] != "on" && !$this->isbot()) {
      list($today, $total, $recent, $lastdate) = $this->read();
      if (!in_array($_SERVER['REMOTE_ADDR'], $recent)) {
	$todaydate = date("Y-m-d");
	if ($lastdate < $todaydate) {
	  $lastdate = $todaydate;
	  $today = 0;
	}
	array_unshift($recent, $_SERVER['REMOTE_ADDR']);
	array_splice($recent, $this->ipcount);
	$this->write($today+1, $total+1, $recent, $lastdate);
      }
    }
  }

}

# vim: ts=8 sw=2 sts=2 noet
?>
