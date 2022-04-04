#!/bin/bash
#set -vx

# Prereq:      for the equalstreetnames project, osm_api script. Populating the config.ini
#    
# 2022.04.03   S.Kim

SCRIPTNAME=$(basename $0 .sh)
JSONFILE=../data/streets_bezirk4.geojson
CONFIGFILE=./config.ini

set -euo pipefail
IFS=$'\n\t'
type jq >/dev/null 2>&1 || { echo >&2 "This script require jq but it's not installed."; exit 3; }

function usage {
   echo "Usage: $(basename $0) STREET WikidataId , e.g. Adams-Lehmann-Straße Q448583; needs $JSONFILE and $CONFIGFILE"
}

if [ "$#" -ne 2 ] || ! [ -f $JSONFILE ] || ! [ -f $CONFIGFILE ]; then
  usage;
  exit 1
fi

STREET=$1
WIKIDATAID=$2

OPENSTREETMAPIDS=$(cat $JSONFILE | jq --arg street "${STREET}" '.features[] | select(.properties.name==$street) | .id' | tr -d "way/")
OPENSTREETMAPIDS=$(echo $OPENSTREETMAPIDS | tr '" "' '","')

# mac sed
sed -i 'bak' "s/^osm_ids = .*/osm_ids = [ $OPENSTREETMAPIDS ]/ ; s/^wikidata_id = .*/wikidata_id = $WIKIDATAID/" $CONFIGFILE

cat $CONFIGFILE

