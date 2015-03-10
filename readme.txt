=== Plugin Name ===
Contributors: donnapep
Tags: book, review, rating, book review, book blog, book blogger, book blogging
Author URI: http://wpreviewplugins.com/
Plugin URI: http://wpreviewplugins.com/book-review/
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: 2.1.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save countless hours, drive more traffic to your site, and maybe even earn some extra money. Spend your time on what really matters - writing reviews!

== Description ==

The WordPress Book Review plugin saves you time when writing your book review posts – no more copying and pasting or manually entering in a book’s details. Instead, you can focus on the important stuff, like actually writing your reviews!

The plugin can also help to increase traffic to your site by creating better-looking search results in Google that will make yours stand out from the rest.

And, if you’re interested in throwing monetization into the mix, the WordPress Book Review plugin makes that easy too!

Sound pretty good? Then check out the [full documentation](http://wpreviewplugins.com/book-review/) to learn more about what the WordPress Book Review plugin can do for you!

= Feature Requests =
For a comprehensive list of feature requests, or to vote on your favorites, please see the [Book Review Roadmap](https://trello.com/b/rlglE0Gf/book-review-roadmap). You can submit your own requests in the [Feature Requests forum](http://wpreviewplugins.com/support/forum/feature-requests/).

= Internationalization =
This plugin supports the following translations (with thanks to the translators!):

* Arabic (منهل العدّاي‎)
* Chinese (Vahi Chen)
* Czech (Radek Volf)
* French (Guillaume Andreux)
* German (Linguasoft)
* Italian (James Garofalo)
* Russian (Alexander Wolf)
* Spanish (Jose Luis Parra)

If you would like to translate this plugin to another language, please review the Translations section of the [documentation](http://wpreviewplugins.com/book-review/#translations).

= Resources =
* Official documentation for the WordPress Book Review Plugin can be found [here](http://wpreviewplugins.com/book-review/).
* Follow me on [Google+](https://plus.google.com/u/0/+DonnaPeplinskie/posts), [Twitter](https://twitter.com/donnapep) or [LinkedIn](http://www.linkedin.com/in/donnapeplinskie).
* If you have problems, questions or suggestions, please post them in the [General Support forum](http://wpreviewplugins.com/support/forum/general-support/).

= About Me =
* I’m the founder of [WPReviewPlugins.com](http://wpreviewplugins.com/).
* I’m a front-end developer & developer advocate for [Rise Vision](http://risevision.com/).
* I’m founder and contributor of the [Book Wookie](http://bookwookie.ca) book blog.
* I blog about web and professional development at [donnapeplinskie.com](http://donnapeplinskie.com/).

== Installation ==

1. In WordPress, go to *Plugins* > *Add Plugins*.
1. Search for *book review plugin*.
1. Click on *Install Now*.
1. Click on *Activate Plugin*.
1. Customize the settings by clicking on *Book Review* in the *Settings* menu.

OR

1. Download the plugin and extract it.
1. Upload the `book-review` folder to the `/wp-content/plugins/` directory on your server.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. Customize the settings by clicking on *Book Review* in the *Settings* menu.

== Frequently Asked Questions ==

= Why do I receive an error message when clicking on the Get Book Info button? =

The first thing you'll want to check is that you have created a Google API Key and that it has been entered correctly on the *Advanced* tab of the *Book Review Settings*.

This message can also appear if the IP address of your web server has not been entered correctly in the Google Developers Console. You will need to go back into the [Developers Console](https://code.google.com/apis/console) and enter the correct IP address. If you're still having trouble, try removing the IP address altogether. See the [documentation](http://wpreviewplugins.com/book-review/#advanced) for more information.

If all else fails, please leave a message in the [General Support forum](http://wpreviewplugins.com/support/forum/general-support/). Be sure to include the URL of your web site in your post.

= What is the Synopsis field for? =

The *Synopsis* field is meant to hold the summary or description of the book. It is not where you are intended to write your review. You should write your review in the regular WordPress editor at the top of the page, although if you prefer you can certainly write it inside the *Synopsis* editor instead. Be aware that if you do that, the custom links will show at the very bottom of your post, rather than directly below the book's description and cover image.

= Why is my title sorting on "A", "An" or "The" in the title archives? =

Titles that start with "A", "An" or "The" are sorted using the second word in the title. For example, *The Hunger Games* would be shown as *Hunger Games, The*. If you have a post that is not sorting this way, try going back into that post and re-saving it.

= Why is my post not showing up in the genre archives? =

Check to ensure that you have filled out the *Genre* field in the *Book Info* section of the post, and that the *Include post in archives* checkbox is selected.

= Why do the archives take a long time to load? =

If you are showing thumbnails of the book covers in your archives, then you should be aware that thumbnails are only used in those themes that support Featured Images. If your theme does not support Featured Images, or if a post does not have a Featured Image set, then scaled-down versions of the full-size covers are used. The images are scaled down as per the *Thumbnail size* value in the *Media Settings*. Showing thumbnails in the archive(s) without using Featured Images will result in longer page load times.

== Screenshots ==

1. Book Review Settings
2. Book Info
3. Book Info on a Sample Post
4. Archives by Title
5. Archives by Genre
6. Google Search Result

== Changelog ==

= 2.1.10 =
* Fixed issue with rating images not showing for PHP versions older than 5.3.0.

= 2.1.9 =
* Added Review Box Border Width setting.
* Removed tooltips from Book Review Settings.
* Updated error message that displays when unable to retrieve book info.
* Updated links throughout plugin.

= 2.1.8 =
* Fixed data in Book Info section not updating when field cleared.
* Restructured code to conform to WordPress Plugin Boilerplate 3.0.

= 2.1.7 =
* Fixed broken schema markup so that ratings, author and published date show in a search engine results page.

= 2.1.6 =
* Changed Book Review Settings to be a tabbed interface.
* Removed limit of only being able to configure 5 custom links.
* Added ability to set individual custom links to inactive.
* Added custom hooks for developers.
* Added Czech translation files.

= 2.1.5 =
* Renamed Arabic translation files.

= 2.1.4 =
* Added Arabic & French translation files.
* Fixed bug with Google Books API text not translating.

= 2.1.3 =
* Added German translation files.

= 2.1.2 =
* Fixed some text not translating on the Book Review Settings page.
* Added Russian translation files.

= 2.1.1 =
* Refactored some of the code.
* Added Chinese translation files.

= 2.1.0 =
* Added support for schema.org.
* Cover image in archives now links to related post.

= 2.0.2 =
* Added Spanish translation files.
* Bug fixing - Fixed issue with URLs not saving for posts.

= 2.0.1 =
* Bug fixing - Removed obsolete public-facing Javascript and CSS.

= 2.0 =
* Added ability to auto-populate a book's details using the Google Books API.
* Added ability to format the Release Date.
* Refactored entire codebase.

= 1.9 =
* Added Rating column to the manage posts screen.

= 1.8 =
* Added support for custom post types.

= 1.7 =
* Added ability to show book cover thumbnails and rating images in archives.

= 1.6 =
* Bug fixing - Archives now work with multisite.

= 1.5 =
* Added Italian translation files.

= 1.4 =
* Added support for internationalization.
* Bug fixing - Ignore case when sorting archives by title.

= 1.3 =
* Added a shortcode for showing archives by title or genre.

= 1.2 =
* Added new *Review Box Position* setting.
* Renamed *Summary* field to *Synopsis* to reduce confusion.

= 1.1 =
* New Fields
    * Added optional *Genre*, *Format* and *Pages* fields.
* Bug Fixing
    * Included the book info above the post's content on the home page if Full Text is being displayed.
    * Included the book info in the RSS feed.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 2.1.10 =
Fixed issue with rating images not showing for PHP versions older than 5.3.0.

= 2.1.9 =
Added Review Box Border Width setting and removed tooltips from Book Review Settings page.

= 2.1.8 =
Fixed data in Book Info section not updating when field cleared.
Restructured code to conform to WordPress Plugin Boilerplate 3.0.

= 2.1.7 =
Fixed broken schema markup so that ratings, author and published date show in search engine results pages.

= 2.1.6 =
Changed Book Review Settings to be a tabbed interface.
Removed limit of only being able to configure 5 custom links.
Added ability to set individual custom links to inactive.
Added custom hooks for developers.
Added Czech translation files.

= 2.1.5 =
Renamed Arabic translation files.

= 2.1.4 =
Added Arabic & French translation files.
Fixed bug with Google Books API text not translating.

= 2.1.3 =
Added German translation files.

= 2.1.2 =
Fixed some text not translating on the Book Review Settings page.
Added Russian translation files.

= 2.1.1 =
Refactored some of the code.
Added Chinese translation files.

= 2.1.0 =
Added support for schema.org.
Cover image in archives now links to related post.

= 2.0.2 =
Added Spanish translation files.
Fixed issue with URLs not saving for posts.

= 2.0.1 =
Removed obsolete public-facing Javascript and CSS.

= 2.0 =
Added ability to auto-populate a book's details using the Google Books API.
Added ability to format the Release Date.
Refactored entire codebase.

= 1.9 =
Added Rating column to the manage posts screen.

= 1.8 =
Added support for custom post types.

= 1.7 =
Added ability to show book cover thumbnails and rating images in archives.

= 1.6 =
Archives now work with multisite.

= 1.5 =
Added Italian translation files.

= 1.4 =
Added support for internationalization.

= 1.3 =
Added a shortcode for showing archives by title or genre.

= 1.2 =
Added new Review Box Position setting for controlling the position of the review box.

= 1.1 =
Added optional Genre, Format and Pages fields. Bug fixes related to showing the book info on the home page and in the RSS feed.

= 1.0 =
Initial release.