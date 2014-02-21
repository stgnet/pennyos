#!/bin/sh


	RESULT="result.tgz"
	[ ! -f $RESULT ] && echo "WARNING: no file $RESULT"

	tar xvf $RESULT ./build-script-output

	cat ./build-script-output

