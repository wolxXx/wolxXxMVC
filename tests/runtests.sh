#!/bin/bash

clear;
here=$(dirname $(readlink -f $0));
cd $here;

if [ -d testsout ]; then
	echo ""
else
	mkdir testsout;
fi

phpunit --process-isolation --bootstrap TestBootstrap.php --colors --coverage-html testsout .
