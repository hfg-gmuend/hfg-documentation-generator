// List of elements for which the draggable definition should be updated
// key -> file extension or kirby defined file type  NOTE: file extension gets checked before file type
// value -> type of tag
const draggableDefs = {
    "js":    "p5",
    "video": "video",
    "audio": "mp3"
}

// update draggable definition of each item
function updateDraggableDefs(items) {
    items.forEach((item) => {
        // check if file extension exists in draggableDefs else check if file type exists and determine tag type
        let filename  = $(item).attr("data-helper");
        let extension = getFileExtension(filename);

        // get defined type of tag by extension or file type
        let tagType   = draggableDefs[extension] || draggableDefs[getFileType(extension)];

        // if type of tag has been found update draggableDef of item
        if(tagType !== undefined) $(item).attr("data-text", "(" + tagType + ": " + filename + ")");
    });
}

// returns the file extension from the passed filename
function getFileExtension(filename) {
    return filename.substring(filename.lastIndexOf(".") + 1);
}

// returns the matching kirby file type for passed file extension. If no type matches extension undefined gets returned
function getFileType(extension) {
    return Object.keys(types).find(type => {
        return types[type].includes(extension);
    });
}