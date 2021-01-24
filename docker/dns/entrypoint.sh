#!/bin/bash
####
# Using multi-service container: https://docs.docker.com/config/containers/multi-service_container/
####

# Start sshd
service ssh start
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start sshd: $status"
  exit $status
fi

# Start named
/usr/sbin/named -g -c /etc/bind/named.conf -u bind
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start bind: $status"
  exit $status
fi

# Naive check runs checks once a minute to see if either of the processes exited.
while sleep 60; do
  ps aux | grep /usr/sbin/sshd | grep -q -v grep
  SSHD_STATUS=$?
  ps aux | grep /usr/sbin/named | grep -q -v grep
  NAMED_STATUS=$?
  # If the greps above find anything, they exit with 0 status
  # If they are not both 0, then something is wrong
  if [ $SSHD_STATUS -ne 0 -o $NAMED_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi
done


