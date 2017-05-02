'use strict';

var React = require('react');

var markdownToHtml = require('../utils/react').markdownToHtml;


var PageItemCaption = React.createClass({
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
      <div className="col-sm-3 caption">
        {this.createParagraphs()}
      </div>
    );
  }
});

module.exports = PageItemCaption;
