<?php
class Calendar {
  var $year;
  var $month;
  var $day;

  function Calendar($year, $month, $day) {
    $this->year = $year ? $year : date("Y");
    $this->month = $month ? $month : date("m");
    $this->day = $day ? $day : date("d");
  }

  function getMonthHref($year, $month) {
    global $blog_baseurl, $blog_fancyurl;

    if ($month < 1) {
      $year--;
      $month += 12;
    } elseif ($month > 12) {
      $year++;
      $month -= 12;
    }

    if ($blog_fancyurl) {
      return sprintf("%s/%04d/%02d", $blog_baseurl, $year, $month);
    } else {
      return sprintf("%s/index.php?archive=%04d%02d", $blog_baseurl, $year, $month);
    }
  }

  function getMonthAnchor($year, $month) {
    if ($month < 1) {
      $year--;
      $month += 12;
    } elseif ($month > 12) {
      $year++;
      $month -= 12;
    }
    return date("F Y", mktime(0, 0, 0, $month, 1, $year));
  }

  function getCalendar() {
    global $blog_baseurl;

    $year = $this->year;
    $month = $this->month;
    
    $ndays = date("t", mktime(0, 0, 0, $month, 1, $year));
    $_weekday = date("w", mktime(0, 0, 0, $month, 1, $year));
    $entries = $this->getEntriesForCalendar($year, $month);
    $cal = "<div id=\"calendar\">\n".
      "<p><a href=\"".$this->getMonthHref($year, $month-1)."\" title=\"".$this->getMonthAnchor($year, $month-1)."\">&laquo;</a>\n".
      "<a href=\"".$this->getMonthHref($year, $month)."\" class=\"current\">".$this->getMonthAnchor($year, $month)."</a>\n".
      "<a href=\"".$this->getMonthHref($year, $month+1)."\" title=\"".$this->getMonthAnchor($year, $month+1)."\">&raquo;</a></p>\n".
      "<table>\n".
      "<tr class=\"header\"><th>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th></tr>\n";

    $weekday = 0;
    for ($day=1-$_weekday; $day<=$ndays; $day++) {
      if ($weekday == 0) {
        $cal .= "<tr>";
      }
      if ($day < 1) {
        $cal .= "<td></td>";
      } elseif($entries[$day]) {
        $nentries = count($entries[$day]);
        if($nentries > 1) {
          $title = "$nentries entries posted on this day";
        } else {
          $title = "$nentries entry posted on this day";
        }
        $entry = new Entry($entries[$day][0]);
        $cal .= "<td><a href=\"".$entry->getHref()."\" title=\"$title\">$day</a></td>";
      } else {
        $cal .= "<td>$day</td>";
      }
      if ($weekday == 6) {
        $cal .= "</tr>\n";
      }
      $weekday = ($weekday + 1) % 7;
    }
    if ($weekday < 6) {
      $cal .= "</tr>\n";
    }
    $cal .= "</table></div>\n";
    return $cal;
  }

  function getEntriesForCalendar($year, $month) {
    $entries = array();
    $filenames = Soojung::queryFilenameMatch(sprintf("^%04d%02d[^.]+[.]entry$", $year, $month));
    sort($filenames);
    foreach($filenames as $filename) {
      list($datetime, $category, $entryid) = explode("_", substr($filename, 9, -6));
      $day = intval(substr($datetime, 6, 2));
      if($entries[$day]) {
        $entries[$day][] = $filename;
      } else {
        $entries[$day] = array($filename);
      }
    }
    return $entries;
  }
}
?>
