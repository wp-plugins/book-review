=== Plugin Name ===
Contributors: Book Wookie
Tags: book, review, book review, book blog, book blogger, book blogging
Author URI: http://bookwookie.ca
Plugin URI: http://bookwookie.ca/wordpress-book-review-plugin/
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: 1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add details such as title, author, cover photo, rating, purchase links and more to each of your book review posts. Show archives by title or genre.

== Description ==

The WordPress Book Review Plugin adds a *Book Info* section to the *Add New Post* and *Edit Post* pages. Fill this out whenever you would like to show more information about a particular book. Note that, at a minimum, *Title* must be specified in order for the information to show at the top of the post, and any fields that you leave blank will not appear. These fields include:

* Title (required) 
* Series
* Author
* Genre
* Publisher
* Release Date
* Format
* Pages
* Source
* Up to five customized URLS (as configured in the settings)
* Cover URL
* Synopsis
* Rating
* Include post in archives

If the *Book Info* section has been filled out, these details will appear in the post.

= Features =
* Position the review box either above or below the post's content.
* Customizable background and border colours.
* Ability to optionally show ratings on the home page when summary text is used.
* Use the built-in star rating images or specify your own.
* Configure up to five links of your choosing. These can be text-based links or you can provide your own images.
* Open your custom links in the same tab or in a new one.
* Show an archive of your reviews by title or genre.

= Archives =
To add archives to a page or post, you can use the `book_review_archives` shortcode. For example, to show archives by title, use the following shortcode format:

`[book_review_archives type="title"]`

This is the default archive type. Only those posts that have the *Title* field filled out will be displayed. Note that titles that start with "A", "An" or "The" will be sorted using the second word in the title. For example, *The Hunger Games* would be shown as *Hunger Games, The*.

To show archives by genre, use the following shortcode format:

`[book_review_archives type="genre"]`

Only those posts that have the *Genre* field filled out will be displayed.

You can control whether or not a particular post should appear in the archives.  There is an *Include post in archives* checkbox in the *Book Info* section of every post, which is selected by default. If you don't want a particular post to be listed in the archives, simply uncheck this box. This setting is especially useful for non-review posts such as cover reveals or book blitzes.

= Internationalization =
This plugin supports the following translations:

* Italian

If you would like to volunteer to translate this plugin into other languages, please contact me at thebookwookie@gmail.com.

= Coming Soon =
Ability to show book cover thumbnails and rating images in archives.

= Resources =
* Full documentation for the WordPress Book Review Plugin can be found on [Book Wookie](http://bookwookie.ca/wordpress-book-review-plugin/).
* Follow Book Wookie on [Facebook](https://www.facebook.com/BookWookie), [Twitter](https://twitter.com/BookWookie), or [Google+](https://plus.google.com/u/0/115576325157071227855/posts).
* If you have questions or suggestions, please post them in the forum that can be found on the Support tab.

= About Me =
As a book blogger myself, I have my finger on the pulse of the book blogging community, and so I have a very good sense of what it is that other book bloggers are looking for in a WordPress plugin. I use the Book Review plugin on my own [Book Wookie](http://bookwookie.ca) blog. Please visit if you'd like to see examples of this plugin in action.

== Installation ==

1. Download the plugin and extract it.
1. Upload the `book-review` folder to the `/wp-content/plugins/` directory on your server.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. Customize the settings by clicking on *Book Review* in the *Settings* menu.

== Frequently Asked Questions ==

= Who is the Book Review plugin for? =

The Book Review plugin is meant to be used by book bloggers and other reviewers who post book reviews to their blog and need an easy way to show the details of that book in each of their review posts.

= What is the Synopsis field for? =

The *Synopsis* field is meant to hold the summary or description of the book. It is not where you are intended to write your review. You should write your review in the regular WordPress editor at the top of the page, although if you prefer you can certainly write it inside the *Synopsis* editor instead. Be aware that if you do that, the custom links will show at the very bottom of your post, rather than directly below the book's description and cover image.

= Why is my title sorting on "A", "An" or "The" in the title archives? =

Titles that start with "A", "An" or "The" should be sorted using the second word in the title. For example, *The Hunger Games* would be shown as *Hunger Games, The*. If you have a post that is not sorting this way, try going back into that post and re-saving it.

= Why is my post not showing up in the genre archives? =

Check to ensure that you have filled out the *Genre* field in the *Book Info* section of the post, and that the *Include post in archives* checkbox is selected.

== Screenshots ==

1. Book Review Settings
2. Book Info
3. Book Info on a Sample Post
4. Archives by Title
5. Archives by Genre

== Changelog ==

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