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
      <div className="col-sm-6 middle-col col-sm-offset-3">
        <img className="img-responsive" src={this.props.src} height="auto" width="auto" />
      </div>
    );
  } 
});

module.exports = PageItemImage;