#!/bin/bash

clear;

echo "wolxXxMVC: running unit tests, generating documentation, adding new svn files";
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

phpunit --bootstrap TestBootstrap.php --no-globals-backup --process-isolation --coverage-html testsout  $TESTPATH;