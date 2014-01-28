########################################################################
#
#  LiveDVD with gnome (default), kde and icewm desktop
#
#  Urs Beyerle, ETHZ
#
########################################################################

part / --size 8192 --fstype ext4

########################################################################
# Include kickstart files
########################################################################

%include sl64-live-base.ks
%include sl64-config-icewm.ks
%include sl64-extra-software.ks
%include sl64-extra-software-livedvd.ks
%include sl64-doc.ks

# %include sl64-live-NONPAE.ks   # uncomment to build with nonpae kernel from elrepo

########################################################################
# Packages
########################################################################

%packages
# package added to @network-tools
nmap
@backup-client
@basic-desktop
@desktop-platform
@dial-up
@emacs
@fonts
@general-desktop
@graphics
@input-methods
@internet-applications
# package added to @internet-applications
thunderbird
xchat
@internet-browser
@ice-desktop
@java-platform
@network-file-system-client
@office-suite
@performance
@perl-runtime
@print-client
@scientific
@security-tools
@system-admin-tools
@system-management
@technical-writing
@tex

# more groups added 
@backup-server
@compat-libraries
@console-internet
lftp
@development
@directory-client
@eclipse
@hardware-monitoring
@ice-desktop
@kde-desktop
@legacy-unix
@legacy-x
@misc-sl
@mysql-client
@nfs-file-server
#@openafs-client
@php
@postgresql-client
@scalable-file-systems
# @storage-client-fcoe
@storage-client-iscsi
@storage-client-multipath
@system-management-snmp
# @system-management-messaging-client
# @system-management-messaging-server
# @system-management-wbem
# @turbogears
# @virtualization
# @virtualization-client
# @virtualization-platform
# @virtualization-tools

# Add support for your prefered language here
@french-support
@german-support
@russian-support
@chinese-support
@italian-support
@japanese-support
@spanish-support

# Other useful packages to add
gconf-editor

%end

