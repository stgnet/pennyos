# Makefile for building source packages
#
SITE=vault.centos.org
RELEASE=6.5
SPACKAGES=os/Source/SPackages

OUR=PennyOS

.PHONY:all
all: tools patch

.PHONY:tools
tools:
	@[ `whoami` != "root" ] || (echo "ERROR: rpm build must NOT be run as ROOT!"; exit 1)
	@which rpmbuild >/dev/null || sudo yum -y install rpm-build
	@[ -f /usr/lib/rpm/redhat/rpmsort ] || sudo yum -y install redhat-rpm-config
	@which mock >/dev/null || (sudo yum -y install mock && sudo usermod -a -G mock $USER)
	@[ -d ~/rpmbuild ] || mkdir -p ~/rpmbuild/{BUILD,RPMS,SOURCES,SPECS,SRPMS}
	@[ ! -z "`fgrep topdir ~/.rpmmacros`" ] || echo '%_topdir %(echo $$HOME)/rpmbuild' >~/.rpmmacros
	@grep '^mockbuild:' /etc/passwd >/dev/null || sudo useradd mockbuild

.PHONY:download
download:
	wget -r -nc -l 9 --no-parent http://$(SITE)/$(RELEASE)/

.PHONY:patch
patch:
	@[ `whoami` != "root" ] || (echo "ERROR: rpm build must NOT be run as ROOT!"; exit 1)
	./patchadams $(SITE)/$(RELEASE)/$(SPACKAGES)

	#[ ! -d $(OUR) ] && mkdir $(OUR)
	#ls $(SITE)/$(RELEASE)/$(SPACKAGES) | while read PACKAGE; do mkdir $(OUR)/$$PACKAGE; (cd $(OUR)/$$PACKAGE ; rpm2cpio ../../$(SITE)/$(RELEASE)/$(SPACKAGES)/$$PACKAGE | cpio -idmv ); done

#%.iso: %.cfg
#	LANG=C setarch i686 livecd-creator --verbose --config=$< --fslabel=$(patsubst %.cfg,%,$<)-32 --cache=/var/cache/live --releasever=$(RELEASE)
#	LANG=C livecd-creator --verbose --config=$< --fslabel=$(patsubst %.cfg,%,$<)-64 --cache=/var/cache/live --releasever=$(RELEASE)
#
#.PHONY:clean
#clean:
#	rm *.iso
