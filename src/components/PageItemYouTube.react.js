'use strict';

var _ = require('lodash');
var React = require('react');


var PageItemYouTube = React.createClass({
  getDefaultProps: function () {
    return {
      youtubeId: "",
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
    var youtubeSrc = 'http://www.youtube.com/embed/'+ this.props.youtubeId + '?';
    if (this.props.autoplay == 1){
      youtubeSrc = youtubeSrc + 'autoplay=1';
    }
    if (this.props.loop == 1){
      youtubeSrc = youtubeSrc + '&loop=1&playlist=' + this.props.youtubeId;
      console.log(youtubeSrc);
    }

    return youtubeSrc;
  },

  render: function () {
    return (
      <div className="element">
        <iframe id="ytplayer" type="text/html" src={this.createUrl()} width={this.getLayoutWidth()} height={this.calcHeight()} allowFullScreen></iframe>
      </div>
    );
  }
});

module.exports = PageItemYouTube;
