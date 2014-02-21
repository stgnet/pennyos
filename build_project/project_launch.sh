#!/bin/sh
# this script is uploaded to and run by build.pennyos.org to generate
# sub-job steps for farming out to build agents
#
# $1 argument is URL to project dir for downloads

set -e

SITE=mirror.pennyos.org
RELEASE=6.5
SPACKAGES=os/Source/SPackages

# put a nice descriptor on the build dashboard
echo "{\"name\":\"PennyOS $RELEASE Repo os\",\"description\":\"Download upstream srpms, patch, rebuild, update repo\",\"started\":\"`date -u +%s`\"}" > project.json

PROJECT_URL="$1"
[ -z "$1" ] && echo "ERROR: No project directory argument" && exit 1

lynx -dump http://${SITE}/Upstream/${SPACKAGES} |fgrep "${SPACKAGES}" |grep '.src.rpm$' |sed 's/^.*http/http/' >source-package-url-list-full
[ ! -s source-package-url-list-full ] && echo "ERROR: No sources found?!" && exit 1

SDIR="/www/mirror.pennyos.org/PennyOS/6/os/Source/SRPMS"

cp /dev/null source-package-url-list
cat source-package-url-list-full |while read URL
do
	FILE="${URL##*/}"
	FILE="${FILE%.el*.src.rpm}.pennyos.src.rpm"
	if [ -f "$SDIR/$FILE" ]
	then
		echo "ALREADY BUILT: $FILE"
		continue
	fi
	echo "$URL" >> source-package-url-list
done

[ ! -s source-package-url-list ] && echo "NO ERROR: there are no packages that need built" && exit 1

rm -rf temp

STEP=0
cat source-package-url-list |while read SRCRPM
do
    STEP=`expr $STEP + 1`
    mkdir temp
    echo "{ \"name\":\"Patch ${SRCRPM##*/}\", \"description\": \"Patch and rebuild src rpm file\"}" > temp/step.json
    echo "SRCRPM='$SRCRPM'" > temp/step_launch.sh
    echo "wget $PROJECT_URL/patchadams && sh patchadams $PROJECT_URL $SRCRPM" >> temp/step_launch.sh
    mv temp s$STEP
done

