'use strict';

var React = require('react');

var Header = React.createClass({
  getDefaultProps: function () {
    return {
      school: 'School Name',
      semester: 'SS 20XX',
      teacher: 'Prof. XYZ',
      course: 'Course Name',
      student: 'Student Name'
    };
  },

  render: function () {
    return (
      <div>
        <div className="container-fluid heading">
          <div className="container">
            <div className="row">
              <div className="col-xs-4 col-sm-3 logo">
                <img src="lib/logo.svg" alt="logo" width="26px"/>
                <div className="logo-title">Digital Design</div>  
              </div>
              <div className="col-xs-6 col-sm-9 school">
                {this.props.school}<br />
                {this.props.semester}<br />
                {this.props.teacher}
              </div>
            </div>
          </div>
        </div>
        <div className="container doc-wrapper">
          <div className="row">
            <div className="col-xs-12">
              <div className="doc-title">
                Dokumentation {this.props.course}
              </div>
              <div className="doc-autor">{this.props.student}</div>
            </div>
          </div>
        </div>
      </div>
    );
  } 
});

module.exports = Header;