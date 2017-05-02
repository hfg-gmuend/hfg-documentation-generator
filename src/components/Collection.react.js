'use strict';

var _ = require('lodash');
var React = require('react');

var PageItemText = require('./PageItemText.react');
var PageItemCaption = require('./PageItemCaption.react');
var PageItemImage = require('./PageItemImage.react');
var PageItemP5 = require('./PageItemP5.react');
var PageItemVideo = require('./PageItemVideo.react');
var PageItemVimeo = require('./PageItemVimeo.react');


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
          case 'VIDEO':
            return (<PageItemVideo key={_.uniqueId()} src={item.path}/>);
            break;
          case 'VIMEO':
            var vimeoObject = JSON.parse(item.content);
            // console.log(vimeoObject);
            return (<PageItemVimeo 
              key={_.uniqueId()}
              vimeoId={vimeoObject['id']}
              width={vimeoObject['width']}
              height={vimeoObject['height']}
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
      <div className="row">
        {this.createPageItems()}
      </div>
    );
  } 
});

module.exports = Collection;