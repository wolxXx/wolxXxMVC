#!/bin/bash
clear;

echo "wolxXxMVC shelltools: deployment.";

die () {
	echo >&2 "$@";
	exit 1;
}

#really needs to have a specification!!
if [ "$#" -lt 1 ]; then  
	die "please specify the configuration! (dev, production)";
fi
if [ $1 != "dev" ] && [ $1 != "production" ]; then  
	die "please specify the configuration! (dev, production)";
fi

echo "mode: $1";

#needed directories: here (sth like /var/domains/wolxxx.de/Lib/wolxXxMVC/Backend/), docroot (sth like /var/domains/wolxxx.de), installdir (sth like wolxxx.de)
echo "grabbing directories...";
here=$(dirname $(readlink -f $0));
cd $here;
cd ..;cd ..;cd ..;
docroot=$(pwd);
installdir=${PWD##*/};
echo "directories grabbed...";
#now change into the main installation directory
cd $docroot;
version=$(svn info | grep Revision | cut -d" " -f2);
versiondate=$(svn info | grep "Last Changed Date" | cut -d":" -f 2- -s | cut -d"+" -f 1);
newestversion=$(svn info | grep "URL" | cut -d" " -f 2- -s | xargs svn info | grep Revision | cut -d" " -f2)
echo "current svn version: $version from $versiondate. newest version: $newestversion";
echo "reverting changes...";
svn revert -R $docroot;
echo "updating svn...";
svn update --force $docroot;
echo "svn update done...";
#remove test directories
#if [ $1 = "production" ] && [ -d tests ]; then
#	rm -rf tests;
#fi
echo "setting owner and file permissions...";
chown -fR www-data:www-data $docroot
chmod -fR 770 $docroot
echo "owner and file permissions set...";
echo "running migrations tool...";
php $here/migratior.php $1;
cd $here;
echo "migrations done...";
echo "melde gehorsamst: deployment done. path: $docroot. modus: $1" | mail -s "$installdir: deployment done" devops@wolxxx.de
echo "thats it...";
exit 0;
