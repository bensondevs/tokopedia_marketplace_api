#!/bin/bash

public=$1
private=$2

if [[ $# -lt 2 ]] ; then 
    echo "Usage: generate <public_key> <private_key>"
    exit
fi

openssl genrsa -out $private 4096 && \
openssl rsa -in $private -pubout -out $public
