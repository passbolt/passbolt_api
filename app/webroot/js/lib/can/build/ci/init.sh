#!/bin/bash
BASEDIR=$(dirname $0)
mkdir $BASEDIR/can
git archive HEAD | tar -x -C $BASEDIR/can
cd $BASEDIR
../../node_modules/.bin/http-server -p 8000 &
git clone https://github.com/jupiterjs/steal.git --depth=1 --quiet
git clone https://github.com/jupiterjs/funcunit.git --depth=1 --quiet
cd funcunit
git submodule update --init --recursive