<?php
class Calendar {
  var $year;
  var $month;
  var $day;

  function Calendar($year, $month, $day)
  {
    if (!$year && !$month)
      list($year, $month) = $this->get_year_month();
    $this->year = $year;
    $this->month = $month;
    $this->day = $day;
  }

  function get_year_month()
  {
    return list($year, $month) = split(" ", date("Y m"));
  }

  function get_month_href($year, $month)
  {
    global $blog_baseurl, $blog_fancyurl;

    if ($month<1) {
      --$year;
      $month = 12;
    } elseif ($month>12) {
      ++$year;
      $month = "01";
    }
    if (strlen($month)<=1)
      $month = "0".$month;
    if (strlen($year)<=1)
      $year = "0".$year;

    if ($blog_fancyurl)
      return "$blog_baseurl/$year/$month";
    else
      return "$blog_baseurl/?archive=$year$month";
  }

  function get_month_alt($year, $month)
  {
    if ($month<1) {
      --$year;
      $month = 12;
    } elseif ($month>12) {
      ++$year;
      $month = 1;
    }
    return date("F Y", mktime(0, 0, 0, $month, 1, $year));
  }


  function get_calendar()
  {
    global $blog_baseurl;
    $year = $this->year;
    $month = $this->month;

    $num_days = date("t", mktime(0, 0, 0, $month, 1, $year));
    $fDoWoM = date("w", mktime(0, 0, 0, $month, 1, $year));
    $cal = "
      <table cellpadding=\"0\" cellspacing=\"0\" id=\"calendar\" width=\"100%\">
      <tr>
      <th colspan=\"7\">
      <a href=\"".$this->get_month_href($year,$month-1)."\" title=\"".$this->get_month_alt($year, $month-1)."\">«</a>
      <a href=\"".$this->get_month_href($year,$month)."\">".$this->get_month_alt($year, $month)."</a>
      <a href=\"".$this->get_month_href($year,$month+1)."\" title=\"".$this->get_month_alt($year, $month+1)."\">»</a>
      </th>
      </tr>
      <tr>
      <th width=\"15.0%\">Sun</th>
      <th width=\"14.2%\">Mon</th>
      <th width=\"14.2%\">Tue</th>
      <th width=\"14.0%\">Wen</th>
      <th width=\"14.2%\">Thu</th>
      <th width=\"14.2%\">Fri</th>
      <th width=\"14.2%\">Sat</th>
      </tr>
      <tr>";
    $nweeks = (int) (($num_days+$fDoWoM)/7)+1;
    if ($num_days+$fDoWoM == 35)
      --$nweeks;

    $total = 0;
    $today = 1;
    $bloged_days = $this->get_bloged_days($year, $month);
    while ($total++<$nweeks*7) {
      if ($total%7==1)
	$cal = $cal . "<tr>\n";
      if ($today<$num_days+1 && $total>$fDoWoM) {
	if (in_array($today, $bloged_days))
	  $cal = $cal . "<td><a href=\"$blog_baseurl/$year/$month/$today\">$today</a></td>";
	else
	  $cal = $cal . "<td>$today</td>";
	$today++;
      }
      else
	$cal = $cal . "<td>&nbsp;</td>";
      if (!($total%7))
	$cal = $cal . "</tr>\n";
    }
    $cal = $cal . "</table>\n";
    #$cal = $cal . "$year $month $num_days $nweeks $fDoWoM";
    return $cal;
  }

  function get_bloged_days($year, $month)
  {
    $days = array();
    $filenames = Soojung::queryFilenameMatch($year . $month . "[^.]+[.]entry$");
    foreach($filenames as $filename) {
      $day = substr($filename, 15, 2);
      $days[] = $day;
    }
    $days = array_unique($days);

    return $days;
  }
}
?>
