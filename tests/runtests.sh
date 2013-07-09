#!/bin/bash

clear;

echo "wolxXxMVC: running unit tests, generating documentation, adding new svn files";
echo "";
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

<<<<<<< HEAD
phpunit --bootstrap TestBootstrap.php --debug --strict --no-globals-backup --verbose --process-isolation --coverage-html testsout  $TESTPATH;
=======
phpunit --debug --stderr --strict --no-globals-backup --verbose --process-isolation --bootstrap TestBootstrap.php --coverage-html testsout  $TESTPATH
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
