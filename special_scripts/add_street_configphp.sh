#!/bin/bash
#set -vx

# Prereq:      for the equalstreetnames project. i expect this to be run from
#              the directory of a city.
# Purpose:     output ready to be pasted into config.php
#
#              '4586071' => 'M', // Weichselbaumer, Schulkommissar Matthias Weichselbaumer
#    
# 2021.03.15   S.Kim

SCRIPTNAME=$(basename $0 .sh)
JSONFILE=data/ways.geojson

type jq >/dev/null 2>&1 || { echo >&2 "This script require jq but it's not installed."; exit 3; }

function usage {
   echo "Usage: $(basename $0) STREET GENDER NAME COMMENT , e.g. Valpichlerstraße M Valpichler \"eine der 42 Münchner Geiseln König Gustavs II. Adolf\" "
}

if [ "$#" -ne 4 ] || ! [ -f $JSONEFILE ]; then
  usage;
  exit 1
fi

STREET=$1
GENDER=$2
NAME=$3
COMMENT=$4
# NAME=${STREET%%straße*} // better do this manually, could re-add it as default

for id in $(cat $JSONFILE | jq --arg street "${STREET}" '.features[] | select(.properties.name==$street) | .id'); do
  echo "            '$id' => '$GENDER', // $STREET: $NAME, $COMMENT "
done



