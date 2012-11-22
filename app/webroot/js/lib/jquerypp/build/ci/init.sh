#!/bin/bash
BASEDIR=$(dirname $0)
mkdir $BASEDIR/jquery

# Make a clean copy of the repository in the actual CI directory
git archive HEAD | tar -x -C $BASEDIR/jquery
cd $BASEDIR

../../node_modules/.bin/http-server -p 8000 &
# We only need shallow clones for CanJS, Steal and FuncUnit
git clone https://github.com/jupiterjs/canjs.git can --depth=1
git clone https://github.com/jupiterjs/steal.git --depth=1
git clone https://github.com/jupiterjs/funcunit.git --depth=1

# Initialize submodule (Syn)
cd funcunit
git submodule update --init --recursive
