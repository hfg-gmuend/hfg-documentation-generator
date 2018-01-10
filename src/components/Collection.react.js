'use strict';

var _ = require('lodash');
var React = require('react');

var PageItemText = require('./PageItemText.react');
var PageItemCaption = require('./PageItemCaption.react');
var PageItemImage = require('./PageItemImage.react');
var PageItemP5 = require('./PageItemP5.react');
var PageItemHtml = require('./PageItemHtml.react');
var PageItemVideo = require('./PageItemVideo.react');
var PageItemVimeo = require('./PageItemVimeo.react');
var PageItemYouTube = require('./PageItemYouTube.react');

var Collection = React.createClass({
  getDefaultProps: function () {
    return {
      pageItems: []
    };
  },

  createPageItems: function() {
    var items = this.props.pageItems.map(
      function(item, index) {
        // console.log(item.type);
        switch(item.type) {
          case 'TEXT':
            return (<PageItemText key={_.uniqueId()} content={item.content}/>);
            break;
          case 'CAPTION':
            return (<PageItemCaption key={_.uniqueId()} content={item.content}/>);
            break;
          case 'IMAGE':
            return (<PageItemImage key={_.uniqueId()} src={item.path}/>);
            break;
          case 'P5':
            return (<PageItemP5 key={_.uniqueId()} content={item.content}/>);
            break;
          case 'HTML':
            return (<PageItemHtml key={_.uniqueId()} src={item.path}/>);
            break;
          case 'VIDEO':
            return (<PageItemVideo key={_.uniqueId()} src={item.path}/>);
            break;
          case 'VIMEO':
            var vimeoObject = JSON.parse(item.content);
            return (<PageItemVimeo
              key={_.uniqueId()}
              vimeoId={vimeoObject['id']}
              width={vimeoObject['width']}
              height={vimeoObject['height']}
              autoplay={vimeoObject['autoplay']}
              loop={vimeoObject['loop']}
            />);
            break;
          case 'YOUTUBE':
           var youtubeObject = JSON.parse(item.content);
           return (<PageItemYouTube
              key={_.uniqueId()}
              youtubeId={youtubeObject['id']}
              width={youtubeObject['width']}
              height={youtubeObject['height']}
              autoplay={youtubeObject['autoplay']}
              loop={youtubeObject['loop']}
            />);
            break;
        }
        return null;
      },
      this // bind
    );
    return items;
  },

  render: function () {
    return (
      <div id="content">
        {this.createPageItems()}
      </div>
    );
  }
});

module.exports = Collection;
