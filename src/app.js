'use strict';

window.$ = require('jquery');

var React = require('react');
var ReactDOM = require('react-dom');

var packageInfo = require('../package.json');
var Application = require('./components/Application.react');

console.log("%c * "+ packageInfo.name +" "+ packageInfo.version + " * ", "background: #0C2; color: #fff");

ReactDOM.render(<Application />, document.getElementById('react-application'));
