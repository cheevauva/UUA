#!/bin/bash

HOSTS="repo.packagist.org packagist.org ://github.com ://github.com github.com ://githubusercontent.com"

for h in $HOSTS; do
  ip=$(getent ahostsv4 "$h" | awk '{print $1}' | head -n 1)

  if [ -n "$ip" ] && [ "$ip" != "127.0.0.1" ]; then
    sed -i "/$h/d" /etc/hosts
    echo "$ip $h" >> /etc/hosts
    echo " -> $h => $ip"
  else
    echo " -> Failed resolve name for $h"
  fi
done