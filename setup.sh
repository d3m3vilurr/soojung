#!/bin/sh
# $Id: setup.sh,v 1.2 2005/02/20 02:20:14 mithrandir Exp $

curdir=`pwd`

# 수정 블로그의 루트 디렉토리에서 실행하고 있는지 간단히 체크
if [ ! -f $curdir/setup.sh -o ! -f $curdir/index.php ] ; then
	echo "Oops! Execute this at soojung blog's root directory please."
	echo
	echo "  (ex) cd ~/public_html/soojung ; ./$0"
	exit
fi

# chmod go+w .
RETVAL=0
chmod go+w .
RETVAL=$?
if [ ! $RETVAL -eq 0 ]; then
	echo "chmod go+w . : failed. Fix this problem manually and re-execute."
	exit
else
	echo "chmod go+w . : success."
fi

# chmod go+w templates
RETVAL=0
chmod go+w templates/
RETVAL=$?
if [ ! $RETVAL -eq 0 ]; then
	echo "chmod gw+w templates : failed. Fix this problem manually and re-execute."
	exit
else
	echo "chmod go+w templates : success."
fi

echo 'All is OK.'
