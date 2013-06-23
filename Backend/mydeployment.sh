#!/bin/sh

here=$(dirname $(readlink -f $0));
cd $here;
./deploy.sh production