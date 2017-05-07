'use strict';

var React = require('react');

var Header = React.createClass({
  getDefaultProps: function () {
    return {
      
    };
  },

  render: function () {
    return (
        <div id="header">
          <div id="logo">
            <span className="red">H</span>
            <span className="invisible">ochschule</span>

            <span className="red">f</span>
            <span className="invisible">ür</span>

            <span className="red">G</span>
            <span className="invisible">estaltung</span>
            <br /><br />
            Hochschule für Gestaltung<br />
            Schwäbisch Gmünd
          </div>
        </div>
    );
  } 
});

module.exports = Header;