#!/bin/sh


DEST="/www/mirror.pennyos.org/PennyOS/6/os"
[ ! -d $DEST ] && mkdir -p $DEST
[ ! -d $DEST/i386 ] && mkdir -p $DEST/i386/RPMS
[ ! -d $DEST/x86_64 ] && mkdir -p $DEST/x86_64/RPMS

	RESULT="result.tgz"
	[ ! -f $RESULT ] && echo "WARNING: no file $RESULT"

	tar xvf $RESULT -C $DEST ./i386 ./x86_64 ./Source
	rm -rf result.tgz

cd $DEST/Source
createrepo .
cd $DEST/i386
createrepo .
cd $DEST/x86_64
createrepo .

