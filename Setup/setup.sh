#!/bin/bash
clear;
echo "wolxXxShellTools: mvcsetup | installs foo and bar.";

echo "need to set svn properties ignore and externals correctly and commit!";
echo "admin layout, default layout!";
exit 1;

tryCreateDirectory () {
	if [ -d $1 ]; then
		echo "directory $1 already exists",
	else
		mkdir --parents $1
		echo "directory $1 created",
	fi;
}


if [ -z $1 ]; then
	echo -ne "provide the path to the wished installation: ";
	read INSTALLDIR;
else
	INSTALLDIR=$1;
fi;

if [ -d $INSTALLDIR ]; then
	echo "directory $INSTALLDIR already exists. can not install there...",
	exit 1;
fi;

if [ -z $2 ]; then
	echo -ne "provide the path svn repository: ";
	read SVNREPOSITORY;
else
	SVNREPOSITORY=2;
fi;

#checkout from svn path
svn co $SVNREPOSITORY $INSTALLDIR 

tryCreateDirectory "$INSTALLDIR/application";
tryCreateDirectory "$INSTALLDIR/application/controllers";
tryCreateDirectory "$INSTALLDIR/application/config";
tryCreateDirectory "$INSTALLDIR/application/migrations";

tryCreateDirectory "$INSTALLDIR/Lib";
tryCreateDirectory "$INSTALLDIR/views";
tryCreateDirectory "$INSTALLDIR/js";
tryCreateDirectory "$INSTALLDIR/css";
tryCreateDirectory "$INSTALLDIR/application/img";

tryCreateDirectory "$INSTALLDIR/log";
tryCreateDirectory "$INSTALLDIR/files";
tryCreateDirectory "$INSTALLDIR/tmp";

cp src/.htaccess $INSTALLDIR;
cp src/ajax.php $INSTALLDIR/views;
cp src/AppConfig.php $INSTALLDIR/application/config;
cp src/Bootstrap.php $INSTALLDIR/application/config;
cp src/CmsController.php $INSTALLDIR/application/controllers;
cp src/defines.php $INSTALLDIR/application/config;
cp src/Helper.php $INSTALLDIR/application;
cp src/HostConfig.php $INSTALLDIR/application/config;
cp src/index.php $INSTALLDIR;
cp src/json.php $INSTALLDIR/views;
cp src/Model.php $INSTALLDIR/application; 

propset svn:ignore "log" $INSTALLDIR
propset svn:ignore "files" $INSTALLDIR
propset svn:ignore "tmp" $INSTALLDIR
propset svn:ignore "application/config/HostConfig.php" $INSTALLDIR

propset svn:externals "wolxXxMVC  http://svn.wolxxx.de/mvc/Lib/wolxXxMVC
Bitly http://svn.wolxxx.de/mvc/Lib/Bitly
Etherpad http://svn.wolxxx.de/mvc/Lib/Etherpad
Facebook http://svn.wolxxx.de/mvc/Lib/Facebook
MobileDetect http://svn.wolxxx.de/mvc/Lib/MobileDetect
MooUpload http://svn.wolxxx.de/mvc/Lib/MooUpload
Piwik http://svn.wolxxx.de/mvc/Lib/Piwik
Projekktor http://svn.wolxxx.de/mvc/Lib/Projekktor
Redmine http://svn.wolxxx.de/mvc/Lib/Redmine
Twitter http://svn.wolxxx.de/mvc/Lib/Twitter
TCPDF http://svn.wolxxx.de/mvc/Lib/TCPDF
" "$INSTALLDIR/Lib"

echo "..done..byebye!";

exit 0;
