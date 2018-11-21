$(document).ready(function() {
const types = {"image":["jpeg","jpg","jpe","gif","png","svg","ico","tif","tiff","bmp","psd","ai","eps","ps"],"document":["txt","text","mdown","md","markdown","pdf","doc","docx","dotx","word","xl","xls","xlsx","xltx","ppt","pptx","potx","csv","rtf","rtx","log","odt","odp","odc"],"archive":["zip","tar","gz","gzip","tgz"],"code":["js","css","scss","htm","html","shtml","xhtml","php","php3","php4","rb","xml","json","java","py"],"video":["mov","movie","avi","ogg","ogv","webm","flv","swf","mp4","m4v","mpg","mpe"],"audio":["mp3","m4a","wav","aif","aiff","midi"]}

// List of elements for which the draggable definition should be updated
// key -> file extension or kirby defined file type  NOTE: file extension gets checked before file type
// value -> type of tag
const draggableDefs = {
    "js":    "p5",
    "video": "video"
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

// taken and slightly changed from https://github.com/brocessing/kirby-previewfiles/commit/974c8d8bae3c48635e3cc64805e4c7433e372fe5

function previewFiles(items) {

    if(items.length <= 0) return

    items.forEach(item => {
        var $item = $(item).find(".icon.fa")[0];
        var isImage = $item.classList.contains("fa-file-image-o")
        var $parent = $item.parentNode

        var $icon = $parent.querySelector("i")
        var $thumb = document.createElement("div")
        var $text = document.createElement("div")
        var $options = $parent.nextElementSibling
        var $ellipsis = $options.querySelector("i")

        var textValue = $parent.childNodes[0].nodeValue
        var thumbUrl = $parent.dataset.url

        $parent.innerHTML = ""
        $parent.style.padding = "0"
        $parent.style.margin = "0"
        $parent.style.marginBottom = "5px"

        $text.innerHTML = textValue
        $text.style.height = "40px"
        $text.style.padding = "0 0 0 50px"
        $text.style.lineHeight = "40px"
        $text.style.boxSizing = "border-box"

        if (isImage) {
            $thumb.style.background = "url(" + thumbUrl + ") no-repeat center"
            $thumb.style.backgroundSize = "cover"
        } else {
            $thumb.style.background = "#efefef"
            $thumb.style.background = "#efefef"
        }
        $thumb.style.width = "40px"
        $thumb.style.height = "40px"
        $thumb.style.position = "absolute"
        $thumb.style.top = "0"
        $thumb.style.left = "0"

        $options.style.top = "0px"
        $options.style.left = "0px"
        $options.style.width = "40px"
        $options.style.height = "40px"
        $options.style.background = "rgba(0, 0, 0, 0.7)"
        $options.style.lineHeight = "40px"
        $options.style.textAlign = "center"
        $ellipsis.style.color = "white"
        $ellipsis.style.lineHeight = "15px"
        $ellipsis.style.position = "static"

        $icon.style.top = "0px"
        $icon.style.left = "14px"
        $icon.style.lineHeight = "40px"
        // $icon.style.textAlign = "center"

        $parent.appendChild($thumb)
        if (!isImage) $parent.appendChild($icon)
        $parent.appendChild($text)
    });
}

// create observer instance
var observer = new MutationObserver(function(mutations) {
    extendFileSidebar();
});

// start observation by passing target node and config
observer.observe(app.content.root[0], { childList: true });

// functions that get called by observer
function extendFileSidebar() {
    // get all file nodes
    let items = app.content.element("ul.sidebar-list li .draggable-file").toArray();

    // call functions if they exist
    if(typeof previewFiles === "function") previewFiles(items);
    if(typeof updateDraggableDefs === "function") updateDraggableDefs(items);
}

// run functions once on init
extendFileSidebar();

});