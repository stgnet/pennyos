#!/bin/sh


DEST="/www/mirror.pennyos.org/PennyOS/6/os"
[ ! -d $DEST ] && mkdir -p $DEST
[ ! -d $DEST/i386 ] && mkdir -p $DEST/i386
[ ! -d $DEST/x86_64 ] && mkdir -p $DEST/x86_64

	RESULT="result.tgz"
	[ ! -f $RESULT ] && echo "WARNING: no file $RESULT"

	tar xvf $RESULT -C $DEST ./i386 ./x86_64
	rm -rf result.tgz

cd $DEST/i386
createrepo .
cd $DEST/x86_64
createrepo .

