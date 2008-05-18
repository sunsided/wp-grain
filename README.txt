Grain Theme for WordPress
-------------------------

* You can find Grain at: 
  http://wp-grain.wiki.sourceforge.net/

* You can download newest releases at:
  http://sourceforge.net/project/showfiles.php?group_id=228008



1) Installation prerequisites

* Get and install YAPB (http://johannes.jarolim.com/yapb)
  The Grain theme won't work without the YAPB plugin. You will find installation
  instructions on the YAPB website.


2) Installation instructions

* Extract the archives's contents into the wp-content/themes directory of
  your WordPress installation.
  If you are updating from a version prior to 0.2, please consider removing 
  all old files first - at least the .css files - since you would end up with 
  a bunch of abandoned files otherwise.

3) Configuration

When you have YAPB and Grain installed and activated, you can configure it.

   1. Go to the YAPB configuration menu (under Options) and be sure to disable 
      automatic embedding under Automatic Template Insertion. Leave RSS2 and 
      Atom feed embedding activated and finally set your preferences there. Also 
      activate filtering of EXIF tags there. Generally spoken: Take a look around 
      here, as YAPB is the engine under Grain's hood. (If this is the first time 
      you install and activate Grain, this may already be set for you automatically - 
      be sure to double check it, anyways)
   2. Go to the Presentation menu ("Themes" in the german version) and enable Grain.
   3. Once it's activated, you'll find two new options there: "Copyright Settings" 
      and "General Settings".
   4. Under Copyright Settings set your copyright information. If you want to 
      license your content under a Creative Commons license, pick a license here and 
      add the HTML content in the "License's HTML Code" box.
   5. Under General Settings you're able to tweak Grain to your needs. Since this 
      is your first installation, hit "Factory Defaults" next to the save button. 
      This should give you a default installation of the Theme.
   6. If you want to write an "About" page, do this. Then find out this page's ID 
      (under Page or Post management) and put it into the "About page ID" box. Check 
      the "Show the About link" box and everything will be fine. If you don't have 
      or want to have an about page, uncheck that box.
   7. Creation of a mosaic-type archive listing is analogous to the About page 
      creation, but you have to set the "Mosaic" template for that page in addition. 
      You can do that in the Create/Edit Page screen.
   8. If you are (or want to be) listed on those photoblog sites like vfxy, 
      photoblogs.org or else, you should dive into the functions.syndication.php file 
      via the Theme editor. If you don't need this option, uncheck it.
   9. Grain is a tiny bit multilanguage. If you want to display your photo's title 
      in two languages, check the "2nd Language" box. Then, while on the Post screen, 
      add a custom field named the same as given in the box - or rename that tag, if 
      your like - and add your foreign language title to it. It will be displayed in 
      the photo's tooltip, as well as the comments/details popup.
  10. If you don't like the comments popup, try enabling the extended info page under 
      "General Settings". The comments will then be shown under the current photo once 
      the visitor has clicked the "show comments" link. Information about the image 
      (including it's title and exif info, if you want them to) will be shown there, too.
  11. Under Options | Reading set the "Show the most recent posts" to any value you 
      like. I'd recommend multiples of five, but that's up to you. This will affect 
      the search and archive pages, so don't worry. :)

4) Have fun.
   ;)