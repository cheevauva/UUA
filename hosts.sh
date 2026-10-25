#!/bin/bash

HOSTS="repo.packagist.org packagist.org github.com githubusercontent.com pecl.php.net"

for h in $HOSTS; do
  ip=$(getent ahostsv4 "$h" | awk '{print $1}' | head -n 1)

  if [ -n "$ip" ] && [ "$ip" != "127.0.0.1" ]; then
    echo "$ip $h"
  fi
done