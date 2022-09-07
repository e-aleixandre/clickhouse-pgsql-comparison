#!/bin/bash

# let count=1
# while read -r line
# do
#  echo "$line" | clickhouse-client --query="INSERT INTO books FORMAT JSONEachRow"
#  echo -ne "Processed $count line(s)"\\r
#  let count=$count+1
# done < "/data/goodreads_books.json"

clickhouse-client --query="INSERT INTO books FORMAT JSONEachRow" < /data/goodreads_books.json