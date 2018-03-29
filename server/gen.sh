#!/bin/bash

mkdir -p generated
protoc -I .. service.proto --php_out=generated
