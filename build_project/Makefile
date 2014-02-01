# Makefile for generating build probject to rebuild source packages
#

.PHONY:all
all: launch

.PHONY:tools
tools:
	@which lynx >/dev/null || sudo yum -y install lynx

.PHONY:launch
launch: tools
	tar cvfz RebuildFromSource.tgz . --exclude Makefile --exclude RebuildFromSource.tgz
	curl -u scott -F userfile=@RebuildFromSource.tgz http://build.pennyos.org/addjob.php
	rm -f RebuildFromSource.tgz
