#!/bin/bash

# let count=1
# while read -r line
# do
#  echo "$line" | clickhouse-client --query="INSERT INTO books FORMAT JSONEachRow"
#  echo -ne "Processed $count line(s)"\\r
#  let count=$count+1
# done < "/data/goodreads_books.json"

# clickhouse-client --query="INSERT INTO books FORMAT JSONEachRow" < /data/goodreads_books.json
clickhouse-client --query="INSERT INTO data FORMAT JSONEachRow" < /data/gh-archive-2021-12.json
# Import for pgsql
# cat /data/gh-archive-2021-12.json | spyql -Ochunk_size=500 -Otable=data "SELECT json->id, json->event_type, json->actor_login, json->repo_name, json->action, json->title, json->body FROM json TO sql" | psql -U test postgres