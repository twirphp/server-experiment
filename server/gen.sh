#!/bin/bash

mkdir -p src
protoc -I .. service.proto --php_out=src
