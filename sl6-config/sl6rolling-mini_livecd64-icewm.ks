########################################################################
#
#  Mini LiveCD 64bit with icewm desktop
#
#  Urs Beyerle, ETHZ
#
########################################################################

########################################################################
# Include LiveDVD kickstart file
########################################################################

%include sl6rolling-mini_livecd-icewm.ks

########################################################################
# Packages
########################################################################

%packages

# xfsprogs only avaialbe on 64 bit
xfsprogs

%end
