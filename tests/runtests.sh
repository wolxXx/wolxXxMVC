#!/bin/bash

clear;

echo "wolxXxMVC: running unit tests...";
echo "";
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

phpunit --debug --stderr --strict --no-globals-backup --verbose --process-isolation --bootstrap TestBootstrap.php --coverage-html testsout  $TESTPATH
