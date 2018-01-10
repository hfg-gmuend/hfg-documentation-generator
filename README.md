HfG Documentation Generator
==========================

Simple web documentation generator


How to create a documentation
-----------------------------

* Install [node.js](https://nodejs.org/) (works only with Node 8.0.0 or higher)
* Download this repository
* Unzip the package
* Move the unpacked folder somewhere on your hard drive, e.g. `~/Desktop/my-documentation`
* Open terminal
* Navigate to the folder using `cd ~/Path/To/Folder` e.g. `cd ~/Desktop/my-documentation`
* Run `npm install`
* Run the webserver `npm start`
* Now you can add your content! Copy your images, p5.js sketches, video files ... to the `/content/` folder and edit `/content/structure.json` file for the course infos

(If you don't want to install node.js, then run a [local webserver](https://github.com/processing/p5.js/wiki/Local-server) and edit `content/structure.json` manually. Not recommended)

Supported media
---------------
* TITLE (page titles): `title.txt`
	If the title inside the file is prefixed with a TAB or SPACE character, then the title will be rendered as a secondary page in the navigation
* TEXT (page main text): `text.txt`, main text of a sub page
basic [markdown](https://en.wikipedia.org/wiki/Markdown#Example)
* IMAGE (image files): `.jpg`, `.jpeg`, `.gif`, `.png`, `.svg`
* CAPTION (image/p5 captions): `1.txt` to refer a caption text e.g. to the first image of the page
* P5 ([p5.js](http://p5js.org/) sketch files): `.js`
* VIDEO (video files): `.mp4`
* VIMEO (online videos hosted on Vimeo): `.vimeo`, to embed videos from vimeo add a `.vimeo` file with the following structure:
	```
	{
		"id": 157991947,
		"width": 1920,
		"height": 1080,
		"autoplay": 0,
		"loop": 0
	}
	```
	"id" represents the Vimeo ID of your video. Just copy the ID out of the URL (Your Vimeo URL of the video should look similar to this: [https://vimeo.com/157991947](https://vimeo.com/157991947)).
	Width and height represent the resolution of your video.
	"autoplay" represents wether the player should autoplay videos or not. 0 means no autoplay, 1 means autoplay enabled.
	"loop" represents wether the player should loop videos or not. 0 means no loop, 1 means loop enabled.

* YOUTUBE (online videos hosted on YouTube): `.youtube`, to embed videos from YouTube add a `.youtube` file with the following structure (note that the id has to be provided in quotes in order to work properly, in contrast to the Vimeo implementation):

	```
	{
		"id": "lw99zNF2GRI",
		"width": 1920,
		"height": 1080,
		"autoplay": 0,
		"loop": 0
	}
	```

	"id" represents the YouTube ID of your video. Just copy the ID out of the URL (Your YouTube URL of the video should look similar to this: [https://www.youtube.com/watch?v=lw99zNF2GRI](https://www.youtube.com/watch?v=lw99zNF2GRI) or this: [https://youtu.be/lw99zNF2GRI](https://youtu.be/lw99zNF2GRI).
	Width and height represent the resolution of your video.
	"autoplay" represents wether the player should autoplay videos or not. 0 means no autoplay, 1 means autoplay enabled.
	"loop" represents wether the player should loop videos or not. 0 means no loop, 1 means loop enabled.


TEXT and CAPTION, support basic [markdown](https://en.wikipedia.org/wiki/Markdown#Example) syntax, to add e.g. hyperlink, bold text, italic text ... to your text

Troubleshooting
---------------
**AUTOPLAYING DOES NOT WORK**

The latest version of Safari blocks autoplay by default. To change this setting, right click the address bar, choose "Settings for This Website..." and toggle the "Auto-Play" option like shown below

![GIF](gifs/troubleshooting-autoplay.gif)
