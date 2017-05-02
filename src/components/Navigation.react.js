'use strict';

var React = require('react');
var cx = require('classnames');
var Sticky = require('react-sticky');


var stickyStyle = {
  position: 'fixed',
  top: '5px'
};

var Navigation = React.createClass({
  getDefaultProps: function () {
    return {
      pages: [],
      currentPage: {},
      setCurrentPage: function() {}
    };
  },

  handleClick: function(page) {
    this.props.setCurrentPage(page);
    $('html, body').animate({scrollTop: 0}, 750);
  },

  createMenuItems: function() {
    var items = this.props.pages.map(
      function(page) {
        var isChapter = page.content ? !/^\s/.test(page.content) : false; // startsWith space or tab?
        var isFocused = this.props.currentPage === page;
        var pageClasses = cx({
          'color-green': isFocused,
          'bold': isChapter,
          'chapter-start': isChapter
        });
        return (
          <div key={page.pageName} className={pageClasses} onClick={this.handleClick.bind(this, page)}>{page.content.trim()}</div>
        );
      }, 
      this // bind
    );
    return items;
  },

  render: function () {
    return (
      <div className="row">
        <div id="left-col" className="col-xs-12 col-sm-2 col-lg-3 left-col">
          <Sticky stickyStyle={stickyStyle}>
            <div className="content-block">
              {this.createMenuItems()}
            </div>
          </Sticky>
        </div>
      </div>
    );
  } 
});

module.exports = Navigation;