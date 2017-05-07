'use strict';

var React = require('react');


var PageItemImage = React.createClass({
  getDefaultProps: function () {
    return {
      src: ""
    };
  },

  render: function () {
    return (
      <div className="element">
        <img src={this.props.src} />
      </div>
    );
  } 
});

module.exports = PageItemImage;