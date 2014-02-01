#!/bin/sh
# this script is uploaded to and run by build.pennyos.org to generate
# sub-job steps for farming out to build agents
#
# $1 argument is URL to project dir for downloads

set -ex

SITE=vault.centos.org
RELEASE=6.5
SPACKAGES=os/Source/SPackages

# put a nice descriptor on the build dashboard
echo "{\"name\":\"Rebuild PennyOS $RELEASE Repo os\",\"description\":\"Download upstream srpms, patch, rebuild, update repo\",\"started\":\"`date -u +%s`\"}" > project.json

PROJECT_URL="$1"
[ -z "$1" ] && echo "ERROR: No project directory argument" && exit 1

lynx -dump http://${SITE}/${RELEASE}/${SPACKAGES} |fgrep "http://${SITE}/${RELEASE}/${SPACKAGES}" |grep '.src.rpm$' |sed 's/^.*http/http/' >source-package-url-list
[ ! -s source-package-url-list ] && echo "ERROR: No sources found?!" && exit 1

rm -rf temp

STEP=0
cat source-package-url-list |while read SRCRPM
do
    STEP=`expr $STEP + 1`
    mkdir temp
    echo "{ \"name\":\"Patch ${SRCRPM##*/}\", \"description\": \"Patch and rebuild src rpm file\"}" > temp/step.json
    echo "SRCRPM='$SRCRPM'" > temp/step_launch.sh
    echo "wget $PROJECT_URL/patchadams && sh patchadams $SRCRPM" >> temp/step_launch.sh
    mv temp s$STEP
done

