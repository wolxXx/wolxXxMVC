#!/bin/bash

clear;

echo "wolxXxMVC: running unit tests...";
echo "";

here=$(dirname $(readlink -f $0));
cd $here;

if [ "$#" -lt 1 ]; then  
	TESTPATH='.';
else
	TESTPATH=$1
fi

if [ -d testsout ]; then
	echo ""
else
	mkdir testsout;
fi

phpunit --verbose --process-isolation --bootstrap TestBootstrap.php --colors --coverage-html testsout  $TESTPATH
