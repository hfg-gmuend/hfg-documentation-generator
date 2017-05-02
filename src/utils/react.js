"use strict";

var _ = require('lodash');
var React = require('react');
var markdown = require( "markdown" ).markdown;


function markdownToHtml(content) {
  var lines = content.split('\n');
  lines = _.without(lines, ''); // empty lines
  var paragraphs = lines.map(
    function(line, index) {
      return (
        <div key={_.uniqueId()} dangerouslySetInnerHTML={{__html: markdown.toHTML(line)}} />
      );
    },
    this // bind
  );
  return paragraphs;
}


module.exports.markdownToHtml = markdownToHtml;
