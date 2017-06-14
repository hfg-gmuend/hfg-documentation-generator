'use strict';

var React = require('react');


var PageItemHtml = React.createClass({
  getDefaultProps: function () {
    return {
      src: ""
    };
  },

  render: function () {
    console.log("render PageItemHtml");
    return (
      <div className="element">
        <iframe src={this.props.src} scrolling="no"/>
      </div>
    );
  } 
});

module.exports = PageItemHtml;