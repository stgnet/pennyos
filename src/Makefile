# Makefile for building source packages
#
SITE=vault.centos.org
RELEASE=6.5
SPACKAGES=os/Source/SPackages

OUR=PennyOS

.PHONY:all
all: 

.PHONY:download
download:
	wget -r -nc -l 9 --no-parent http://$(SITE)/$(RELEASE)/

.PHONY:extract
extract:
	#[ ! -d $(OUR) ] && mkdir $(OUR)
	ls $(SITE)/$(RELEASE)/$(SPACKAGES) | while read PACKAGE; do mkdir $(OUR)/$$PACKAGE; (cd $(OUR)/$$PACKAGE ; rpm2cpio ../../$(SITE)/$(RELEASE)/$(SPACKAGES)/$$PACKAGE | cpio -idmv ); done

#%.iso: %.cfg
#	LANG=C setarch i686 livecd-creator --verbose --config=$< --fslabel=$(patsubst %.cfg,%,$<)-32 --cache=/var/cache/live --releasever=$(RELEASE)
#	LANG=C livecd-creator --verbose --config=$< --fslabel=$(patsubst %.cfg,%,$<)-64 --cache=/var/cache/live --releasever=$(RELEASE)
#
#.PHONY:clean
#clean:
#	rm *.iso
