#!/bin/bash

tar -cvf all.tar src/* vendor/*
scp all.tar root@127.0.0.1:all.tar

ssh root@127.0.0.1 <<'ENDSSH'
rm -rf src/
tar xvf all.tar
rm -rf /opt/wikipedia-download
mkdir /opt/wikipedia-download
mv src /opt/wikipedia-download/src
mv vendor /opt/wikipedia-download/vendor
mkdir /opt/wikipedia-download/temp
chmod -R a+rw /opt/wikipedia-download/temp
rm all.tar
ENDSSH

rm all.tar
