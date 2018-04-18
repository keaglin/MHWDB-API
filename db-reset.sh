#!/usr/bin/env bash

function usage() {
    echo "${0} snapshot [database]"
    echo
    echo "snapshot - a path to a SQL file to load, or the string 'latest' to load the most recent file from the"
    echo "           snapshots directory"
    echo "database - the schema to load the SQL file into"

    exit
}



if [[ $# < 1  || "${1}" == '--help' || "${1}" == '-h' ]]; then
    usage
fi

snapshot="${1}"

if [[ "${snapshot}" == "latest" ]]; then
    snapshot=`ls -t snapshots | cut -f1 | head -n1`
    snapshot="snapshots/${snapshot}"
fi

db="application"

if [[ $# > 1 ]]; then
    db="${2}"
fi

mysql -e "DROP SCHEMA ${db}; CREATE SCHEMA ${db};"
mysql "${db}" < "${snapshot}"