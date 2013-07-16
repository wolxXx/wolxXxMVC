#!/bin/bash

clear;

echo "wolxXxMVC: generating documentation, adding new svn files";
echo "";
echo "";

here=$(dirname $(readlink -f $0));
cd $here;
cd ..;
cd ..;

phpdoc -d wolxXxMVC -t wolxXxMVC/documentation -i wolxXxMVC/documentation -i wolxXxMVC/tests

todo=$(svn status | grep "?" | cut -d " " -f8);
for abc in $todo
do
	echo "new file in svn: $abc";
	svn add $abc;
done;

cd $here;