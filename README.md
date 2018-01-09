HfG Documentation Generator
==========================

Simple web documentation generator


How to create a documentation
-----------------------------

* Install [node.js](https://nodejs.org/) (works only with Node 8.0.0 or higher)
* Download this repository
* Ceate a new folder somewhere on your harddrive e.g. `~/Desktop/my-documentation` (the documentation folder)
* Open terminal and ...
* Go to the folder e.g. `cd ~/Desktop/my-documentation`
* Run `npm install`
* Run the webserver `npm start`
* Now you can add your content! Copy your images, p5.js sketches, video files ... to the `/content/` folder and edit `/content/structure.json` file for the course infos

(If you don't want to install node.js, then have a [local webserver](https://github.com/processing/p5.js/wiki/Local-server) running and edit `content/structure.json` manually. Not recommended)

Supported media
---------------
* TITLE (page titles): `title.txt`
	If the title inside the file is prefixed with a TAB or SPACE charater, then the title will be rendered in the navigation as a seconday page
* TEXT (page main text): `text.txt`, main text of a sub page
basic [markdown](https://en.wikipedia.org/wiki/Markdown#Example)
* IMAGE (image files): `.jpg`, `.jpeg`, `.gif`, `.png`, `.svg`
* CAPTION (image/p5 captions): `1.txt` to refer a caption text e.g. to the first image of the page
* P5 ([p5.js](http://p5js.org/) sketch files): `.js`
* VIDEO (video files): `.mp4`
* VIMEO (online videos): `.vimeo`, to embedd videos from vimeo add a `.vimeo` file with the following structure:

	```
	{
		"id": 157991947,
		"width": 1920,
		"height": 1080
	}
	```

	Where "id" is the vimeo ID of your video. Just copy the ID out of the URL (Your vimeo URL of the video should look similar to this: [https://vimeo.com/157991947](https://vimeo.com/157991947)).
	Width and height represent the resolution of your video.


TEXT and CAPTION, support basic [markdown](https://en.wikipedia.org/wiki/Markdown#Example) syntax, to add e.g. hyperlink, bold text, italic text ... to your text


