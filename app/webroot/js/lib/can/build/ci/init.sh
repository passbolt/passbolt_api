#!/bin/bash
BASEDIR=$(dirname $0)
mkdir $BASEDIR/can

# Make a clean copy of the CanJS repository in the actual CI directory
git archive HEAD | tar -x -C $BASEDIR/can
cd $BASEDIR

# Fire up the HTTP server
../../node_modules/.bin/http-server -p 8000 &

# We only need shallow clones for Steal and FuncUnit
git clone https://github.com/bitovi/steal.git --depth=1 --quiet
git clone https://github.com/bitovi/funcunit.git --depth=1 --quiet

# Initialize submodule (Syn)
cd funcunit
git submodule update --init --recursive