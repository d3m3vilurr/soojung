<?php

class Formatter {

  function plainToHtml($str) {
    return pre_nl2br($str);
  }

}

?>