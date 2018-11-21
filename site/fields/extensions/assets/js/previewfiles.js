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