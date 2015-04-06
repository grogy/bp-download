#!/bin/bash

tar -cvf all.tar src/* vendor/*
scp all.tar root@127.0.0.1:all.tar

ssh root@127.0.0.1 <<'ENDSSH'
rm -rf src/
tar xvf all.tar
sudo rm -rf /opt/wikipedia-download
sudo mkdir /opt/wikipedia-download
sudo mv src /opt/wikipedia-download/src
sudo mv vendor /opt/wikipedia-download/vendor
sudo mkdir /opt/wikipedia-download/temp
sudo chmod -R a+rw /opt/wikipedia-download/temp
rm all.tar
ENDSSH

rm all.tar
