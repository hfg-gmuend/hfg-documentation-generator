HfG Documentation Generator
==========================

Simple web documentation generator based on [Kirby CMS](https://getkirby.com/).

The documentation generator aims to solve the typical student documentation needs of courses at the HfG Schwäbisch Gmünd.

🤖 How to create a documentation
--------------------------------

**macOS**

* Download this repository
* Unzip the package
* Move the unpacked folder somewhere on your hard drive, e.g. "~/Desktop/my-documentation"
* Double click `_start-php.command`. 
* (You might have to give permission to [open an app from an unidentified developer](https://support.apple.com/kb/ph25088?locale=en_US))
* This will open "localhost:8000" in your browser ... you can start editing via `http://localhost:8000/panel`

**Windows**

* Install and start a local Apache server like [MAMP](https://www.mamp.info/de/downloads/) that supports PHP 7.1+. XAMPP would be an alternative, but doesn't seem to support all needed PHP features.
* Download this repository
* Unzip the package
* Move the unpacked folder inside your webserver folder, e.g. "C:\MAMP\htdocs\"
* You may rename the folder "hfg-documentation-generator-master" to something shorter, e.g. "my-documentation"
* Open the url `http://localhost/my-documentation` in your browser ... you can start editing via `http://localhost/my-documentation/panel`

ℹ️ Important information
----------------------
* The first user created on /panel/installation is automatically a user with the role admin
* After an admin user was created, other users can log in on /panel/login with their HfG Account
* Students can create documentations and add other students who have already logged in once with their HfG Account as authors for participation
* When uploading images > 1MB they are resized to be <= 1MB → It's advisable to upload images that don't exceed the limit to have full control over the quality

👩‍🎨 Supported media
------------------
You will write and edit your content in [Kirby Markdown](https://getkirby.com/docs/guide/content/text-formatting). Supported media: videos, Vimeo videos, YouTube videos, p5.js sketches, text, images and images with captions.

🌍 Hosting documentations online
--------------------------------
In case you want to have a documentation online under your own domain ... please make sure to follow first these steps:

1. [Buy](https://getkirby.com/buy) your own Kirby license
2. Delete the default "student" user (site/accounts/student.php) and create your own user.

🔧 Dependencies
----------------
* PHP 7.2+
* [PHP mbstring extension](https://www.php.net/manual/de/mbstring.installation.php)
* [PHP dom extension](https://www.php.net/manual/de/book.dom.php)
* [PHP GD extension](https://www.php.net/manual/de/book.image.php)
* [PHP IMAP extension](https://www.php.net/manual/de/book.imap.php)

❤️ Thanks
---------
* [Nico Brand](http://www.nico-brand.com) visual design
* [Paul Raschke](https://paul-raschke.de/) coding and implemenation 
* [Kirby CMS](https://getkirby.com)
