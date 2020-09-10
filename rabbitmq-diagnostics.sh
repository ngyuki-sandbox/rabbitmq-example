#!/bin/bash

set -u -o pipefail
export LANG=en_US.UTF-8 LC_ALL=en_US.UTF-8

status_file=/tmp/.nagios-rabbitmq-diagnostics-cluster_status

line=$(rabbitmq-diagnostics -q node_health_check 2>&1 | head -1)
if [ $? -ne 0 ]; then
  echo "$line"
  exit 2
fi

if [ -e "$status_file" ]; then
  mv -Tf -- "$status_file" "$status_file.bak"
else
  touch -- "$status_file.bak"
fi

rabbitmq-diagnostics -q cluster_status >"$status_file" || exit 2

out=$(diff -U 10 "$status_file.bak" "$status_file" | grep -Ev '^(\+\+\+|---|@@)')
if [ -n "$out" ]; then
  echo "Cluster status changed"
  echo "$out"
  exit 1
fi

echo "Node health check is OK, Cluster status has not change."
exit 0
