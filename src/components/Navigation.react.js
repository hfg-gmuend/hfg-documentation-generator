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
      semester: 'SS 20XX',
      teacher: 'Prof. XYZ',
      course: 'Course Name',
      student: 'Student Name',
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
          'active': isFocused,
          'chapter': isChapter,
          'subchapter': !isChapter
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
      <div id="leftCol">
        <div id="info">
          <strong>{this.props.course}</strong><br />
          {this.props.semester}<br />
          {this.props.teacher}<br />
          <br />
          Dokumentation von<br />
          <strong>{this.props.student}</strong>
        </div>

        <div id="nav">
          {this.createMenuItems()}
        </div>
      </div>
    );
  } 
});

module.exports = Navigation;