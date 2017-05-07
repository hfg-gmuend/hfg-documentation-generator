'use strict';

var _ = require('lodash');
var React = require('react');


var PageItemVimeo = React.createClass({
  getDefaultProps: function () {
    return {
      vimeoId: "",
      width: 600,
      height: 400
    };
  },
  
  getLayoutWidth: function() {
    return 600;
  },
  
  calcHeight: function() {
    var layoutHeight = (this.getLayoutWidth() * this.props.height) / this.props.width;
    layoutHeight = Math.ceil(layoutHeight);
    return layoutHeight;
  },

  createUrl: function() {
    var vimeoSrc = 'https://player.vimeo.com/video/'+ this.props.vimeoId;
    return vimeoSrc;
  },

  render: function () {
    return (
      <div className="element">
        <iframe src={this.createUrl()} width={this.getLayoutWidth()} height={this.calcHeight()} allowFullScreen></iframe>
      </div>
    );
  } 
});

module.exports = PageItemVimeo;