########################################################################
#
#  LiveDVD 64bit with gnome (default), kde and icewm desktop
#
#  Urs Beyerle, ETHZ
#
########################################################################

########################################################################
# Include LiveDVD kickstart file
########################################################################

%include sl62-livedvd.ks

########################################################################
# Packages
########################################################################

%packages

# xfsprogs only avaialbe on 64 bit
xfsprogs

%end
