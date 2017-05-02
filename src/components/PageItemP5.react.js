'use strict';

var _ = require('lodash');

var React = require('react');


var style = {
  width: '100%',
  marginBottom: '25px'
};

var PageItemP5 = React.createClass({
  getInitialState: function() {
    this._iframeId = _.uniqueId('iframe_p5_');
    return null;
  },

  getDefaultProps: function () {
    return {
      content: ""
    };
  },

  componentWillMount: function() {
    var re = /createCanvas\((.*),(.*)\)/g;
    var tokkens = this.props.content.split(re);
    this._iframeWidth = Number(tokkens[1]);
    this._iframeHeight = Number(tokkens[2]);
  },

  iframeDidLoad: function() {
    // frame is loaded, now add code of p5 sketch
    // https://github.com/processing/p5.js-website/blob/master/js/examples.js#L133
    var iframe = $('#'+this._iframeId)[0];
    var userScript = iframe.contentWindow.document.createElement('script');
    userScript.type = 'text/javascript';
    userScript.text = "new p5();\n" + this.props.content;
    userScript.async = false;
    iframe.contentWindow.document.body.appendChild(userScript);
  },

  render: function () {
    return (
      <div className="col-sm-6 middle-col col-sm-offset-3">
        <iframe
          id={this._iframeId}
          className="p5-iframe"
          onLoad={this.iframeDidLoad}
          height={this._iframeHeight}
          style={style}
          seamless="seamless"
          frameBorder="0"
          scrolling="no"
          src="./lib/p5/p5-iframe-template.html"/>
      </div>
    );
  }
});

module.exports = PageItemP5;
