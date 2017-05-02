var watch = require('node-watch');
var glob = require('glob');
var fs = require('fs');
var path = require('path');

watch('./content', { recursive: true }, function(evt, name) {
  if (path.basename(name) !== 'structure.json') {
    console.log(name, ' changed');
    console.log('New structure.json:');
    createStructure();
  }
});

function createStructure() {
  var structure = JSON.parse(fs.readFileSync('./content/structure.json', 'utf8'));
  var filePaths = glob.sync('./content/**/*');
  var pages = {};
  filePaths.forEach(function (filePath) {
    var parentName = path.dirname(filePath).split(path.sep).pop();
    var fileName = path.basename(filePath);
    if (fs.lstatSync(filePath).isDirectory()) {
      pages[fileName] = [];
    } else {
      if (parentName !== 'content') {
        pages[parentName].push(fileName);
      }
    }
  });
  structure.pages = pages;

  fs.writeFileSync(
    './content/structure.json',
    JSON.stringify(structure, null, 2)
  );

  console.log(structure);
}


