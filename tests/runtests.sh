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

if [ "$#" -lt 2 ]; then  
	COVERAGEDIR='testsout';
else
	COVERAGEDIR=$2
fi

if [ -d $COVERAGEDIR ]; then
	echo ""
else
	mkdir -p $COVERAGEDIR;
fi

#phpunit --bootstrap TestBootstrap.php --no-globals-backup --process-isolation --coverage-html $COVERAGEDIR $TESTPATH;
phpunit --colors --bootstrap TestBootstrap.php  --coverage-html $COVERAGEDIR $TESTPATH;
