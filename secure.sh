#!/bin/sh
# $Id: secure.sh,v 1.1 2005/02/20 02:00:56 ddt Exp $

chmod 701 .
RETVAL=0
rm -f install.php
if [ ! $RETVAL -eq 0 ]; then
	echo 'Deleting install.php: failed. Please fix this problem and re-execute.'
	exit
fi

echo 'OK.'
