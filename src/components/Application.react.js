'use strict';

var _ = require('lodash');
var async = require('async');
var React = require('react');

var Header = require('./Header.react');
var Navigation = require('./Navigation.react');
var Collection = require('./Collection.react');

var endsWith = require('../utils/string').endsWith;
var startsWith = require('../utils/string').startsWith;
var extension = require('../utils/string').extension;


var typesMap = [
  {type: 'TITLE', fileName: 'title', extensions: []},
  {type: 'TEXT', fileName: 'text', extensions: []},
  {type: 'CAPTION', extensions: ['txt']},
  {type: 'IMAGE', extensions: ['jpg', 'jpeg', 'gif', 'png', 'svg']},
  {type: 'P5', extensions: ['js']},
  {type: 'VIDEO', extensions: ['mp4']},
  {type: 'VIMEO', extensions: ['vimeo']},
];

var Application = React.createClass({
  getInitialState: function () {
    return {
      structure: {},
      content: [],
      pages: [],
      currentPage: {},
      currentPageContent: []
    };
  },

  componentDidMount: function() {
    var that = this;
    async.waterfall([
        function(doneOne) { that.loadStructure(doneOne) },
        function(structure, doneTwo) {
          that.loadContent(structure, doneTwo);
        },
      ],
      function(err, structure, content) {
        if (that.isMounted()) {
          var pages = _.filter(content, _.matches({fileName: 'title.txt'}));
          that.setState({
            structure: structure,
            content: content,
            pages: pages
          });
          that.setCurrentPage(pages[0]);
          that.setDocumentTitle(structure.course+" - "+structure.student);

          // console.log('structure:', structure);
          console.log('loaded pages count:', pages.length);
          console.log('loaded pageItems count:', content.length);
        }
      }
    );
  },

  loadStructure: function(done) {
    $.get('./content/structure.json')
    .done(function(data) {
      done(null, data);
    })
    .fail(function() {
      console.error("Can't parse or load structure.json");
      done(new Error());
    });
  },

  loadContent: function(structure, done) {
    var that = this;
    // create flat structure to hold all dynamic pageItems
    var allPageItems = [];
    var pageNames = _.keys(structure.pages);
    _.each(pageNames, function(pageName) {
      var pageItems = structure.pages[pageName];
      var sortedPageItems = that.sortPageItems(pageItems);
      _.each(sortedPageItems, function(fileName, index) {
        allPageItems.push({
          pageName: pageName,
          subPageIndex: index,
          fileName: fileName,
          type: that.typeFromFileName(fileName),
          content: null,
          path: null
        });
      });
    });
    // load content of all pageItems
    var reqests = _.map(allPageItems, function(pageItem){
      var path = './content/' + pageItem.pageName +'/'+ pageItem.fileName + '?nocache='+ new Date().getTime();
      var shouldLoadContent = (pageItem.type !== 'IMAGE' || pageItem.type !== 'VIDEO' ||pageItem.type !== 'VIMEO');
      pageItem.path = path;
      return function(cb){
        if (shouldLoadContent) {
          $.get(path)
            .done(function(data) {
              pageItem.content = data;
              cb(null, pageItem)
            })
            .fail(function() {
              console.error("Can't load", path);
              cb(new Error());
            });
        } else {
          cb(null, pageItem);
        }
      };
    });
    async.parallel(reqests, function(err, results) {
      if (err) done(err);
      else done(null, structure, results);
    });
  },

  sortPageItems: function(pageItems) {
    var mainTextIndex = pageItems.indexOf('text.txt');

    if(mainTextIndex > 0) {
      pageItems.unshift(pageItems.splice(mainTextIndex, 1)[0]);
    }

    var lastPageItem = [];
    var i = 0;

    _.each(pageItems, function(pageItem) {
      var fileParts = pageItem.split('.');
      if(fileParts[0] == lastPageItem[0]) {
        if(fileParts[1] != 'txt') {
          pageItems.swap(i - 1, i);
        }
      }

      lastPageItem = fileParts;
      i++;
    });

    return pageItems;
  },

  typeFromFileName: function(fileName) {
    if (startsWith(fileName.toLowerCase(),'title')) return 'TITLE';
    if (startsWith(fileName.toLowerCase(),'text')) return 'TEXT';

    var ext = extension(fileName).toLowerCase();
    for (var i = 0; i < typesMap.length; i++) {
      var index = _.findIndex(typesMap[i].extensions, function(e) { return e === ext; });
      if (index >= 0) return typesMap[i].type;
    };
    return null;
  },

  setCurrentPage: function(page) {
    this.setState({
      currentPage: page,
      currentPageContent: _.filter(this.state.content, _.matches({pageName: page.pageName}))
    });
    // console.log('currentPage:', this.state.currentPageContent);
  },

  setDocumentTitle: function(title) {
    document.title = title;
  },

  render: function () {
    return (
      <div>
        <Header />
        <Navigation
          semester={this.state.structure.semester}
          teacher={this.state.structure.teacher}
          course={this.state.structure.course}
          student={this.state.structure.student}
          pages={this.state.pages}
          currentPage={this.state.currentPage}
          setCurrentPage={this.setCurrentPage} />
        <Collection pageItems={this.state.currentPageContent} />
      </div>
    );
  }
});

Array.prototype.swap = function (x, y) {
  var b = this[x];
  this[x] = this[y];
  this[y] = b;
  return this;
}

module.exports = Application;
