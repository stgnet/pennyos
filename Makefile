# Makefile for building live cd iso images from kickstart.cfg files
#
# Must run as root, must have livecd-tools installed
#
RELEASE=6

.PHONY:all
all: $(patsubst %.cfg,%.iso,$(wildcard *.cfg))

%.iso: %.cfg
	LANG=C setarch i686 livecd-creator --verbose --config=$< --fslabel=$(patsubst %.cfg,%,$<) --cache=/var/cache/live --releasever=$(RELEASE)
	LANG=C livecd-creator --verbose --config=$< --fslabel=$(patsubst %.cfg,%,$<) --cache=/var/cache/live --releasever=$(RELEASE)

.PHONY:clean
clean:
	rm *.iso

# to create usb stick
# livecd-iso-to-disk --format --reset-mbr test.iso /dev/sdxN
