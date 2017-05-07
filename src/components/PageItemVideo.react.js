'use strict';

var _ = require('lodash');
var React = require('react');


var PageItemVideo = React.createClass({
  getDefaultProps: function () {
    return {
      src: ""
    };
  },

  render: function () {
    return (
      <div className="element">
        <video width="100%" controls>
          <source src={this.props.src} type="video/mp4"/>
          Your browser does not support HTML5 video.
        </video>
      </div>
    );
  } 
});

module.exports = PageItemVideo;