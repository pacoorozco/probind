#!/usr/bin/env bash
#
# Copyright (c) 2016 Paco Orozco <paco@pacoorozco.info>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.
#
# ----------------------------------------------------------------------
# This is a utility script for create a TAR package for the ProBIND
# application.
#
# NOTE: see the usage text for additional information regarding version
# numbers.
# ----------------------------------------------------------------------

# ----------------------------------------------------------------------
# Configuration Section
# ----------------------------------------------------------------------
# Define wich files and folders will be included on release package
INCLUDED_FILES="
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
vendor/
.env.example
AUTHORS
CHANGELOG.md
CONTRIBUTING.md
LICENSE
README.md
artisan
"

# ----------------------------------------------------------------------
# DO NOT MODIFY BEYOND THIS LINE
# ----------------------------------------------------------------------
# Program name and version
PN=$(basename "$0")
VER='0.1'
# Root directory where ${INCLUDED_FILES} reside in
ROOT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

# ----------------------------------------------------------------------
# Functions
# ----------------------------------------------------------------------
usage()
{
	cat <<-EndUsage
	Create a tar package of ProBIND!

	usage: ${PN} [-h|--help]

    Options:

    -h, --help   Prints usage and help.

	EndUsage
}

get_current_version_number()
{
    #
    # Get current version from config/app.php
    #
    current_version=`grep "'version' => '\(.*\)'" ${ROOT_DIR}/config/app.php |
    	head -1 |
    	awk '{ print $3 }' | sed "s/[',]//g"`
}

# ----------------------------------------------------------------------
# Main
# ----------------------------------------------------------------------

get_current_version_number

if [ "x${current_version}" == "x" ]; then
    echo "ERROR: Can't determine the current version."
    exit 1
fi

case $1 in
    -h|--help)
        usage
        ;;
	*)
	    RELEASE_FILE="ProBIND_release_v${current_version}.tgz"
	    tar -C ${ROOT_DIR} -czf ${RELEASE_FILE} ${INCLUDED_FILES}
	    echo "Release package for v${current_version} has been created: ${RELEASE_FILE}"
	    ;;
esac

exit 0
