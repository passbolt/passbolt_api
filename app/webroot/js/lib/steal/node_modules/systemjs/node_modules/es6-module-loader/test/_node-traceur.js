'use strict';

global.expect = require('expect.js');

require('./_helper');

global.System = require('../index').System;

require('./system.spec');

require('./custom-loader');
require('./custom-loader.spec');
