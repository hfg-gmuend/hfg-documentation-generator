'use strict';

var React = require('react');

var markdownToHtml = require('../utils/react').markdownToHtml;


var PageItemText = React.createClass({
  getDefaultProps: function () {
    return {
      content: ""
    };
  },

  createParagraphs: function() {
    return markdownToHtml(this.props.content);
  },

  render: function () {
    return (
      <div className="element paragraph">
        {this.createParagraphs()}
      </div>
    );
  }
});

module.exports = PageItemText;
