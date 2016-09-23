=== Climate Tagger ===
Contributors: Aptivate
Tags: tags, tag cloud, suggestion, tag suggestion, climate tagger, api
Requires at least: 3.7
Tested up to: 4.2.2
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Suggests tags for your posts based on an experts-vetted climate thesaurus, using
the Climate Tagger API

== Description ==

The Climate Tagger Plugin for WordPress is a simple, FREE and easy-to-use way to
integrate the well-known Climate Tagger API into your WordPress site. The
[Climate Tagger API](api.climatetagger.net) has been helping knowledge-driven web
sites better catalogue, categorize, contextualize and connect their data with
that from the broader climate knowledge community since 2011. The Climate Tagger
is backed by an expansive Climate Compatible Development Thesaurus, developed by
experts in multiple fields and continuously updated to remain current.

The tags suggested by the Climate Tagger are displayed in a word cloud, with the
most relevant tags appearing larger.

The plugin is based on [Thoth's Suggested
Tags](https://wordpress.org/plugins/thoth-suggested-tags/). More information
about the Climate Tagger is available at
[http://www.climatetagger.net](http://www.climatetagger.net)

[Follow this project on Github](https://github.com/aptivate/climate-tagger)


== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate it through the **Plugins** menu in WordPress.
3. Register at [http://api.climatetagger.net/register](http://api.climatetagger.net/register) to get your FREE API token (or use your exiting one)
4. Enable the plugin and enter the API key (**Settings** -> **Climate Tagger**)
5. The **Suggested Tags** box will appear on the right hand side when you create a post (**Save Draft** to refresh tag suggestions after you added your text or made changes..
6. Select any of the suggested tags from the word cloud to automatically add them to your article
7. If you wish tag suggestions to appear for pages as well as posts, add `page` to the comma-separated list of **Post types** on the **Settings** page

== Changelog ==

= 1.0.3 =
Props [@swc-pdi](https://github.com/swc-pdi)
* Added the possibility to select a certain project in the Climate Tagger configuration
* Adapted methods after ClimateTagger API changes

= 1.0.2 =
* Documentation updates
* Replaced references to reegle API with Climate Tagger

= 1.0.1 =
* Documentation updates only

= 1.0.0 =
* First version

== Upgrade Notice ==

= 1.0.0 =
* First version


== Development ==

This plugin uses [wp-cli](http://wp-cli.org/) and [PHPUnit](https://phpunit.de/) for testing.
The tests require [runkit](https://github.com/zenovich/runkit) for mocking functions.

* Grab the latest source from github:

`
$ git clone git@github.com:aptivate/climate-tagger.git
`

* Install [wp-cli](http://wp-cli.org/#install)
* Install [PHPUnit](https://phpunit.de/)
* Set up runkit:

`
$ git clone https://github.com/zenovich/runkit.git
$ cd runkit
$ phpize
$ ./configure
$ sudo make install
`

Add the following lines to `/etc/php5/cli/php.ini`:

`
extension=runkit.so
runkit.internal_override=1
`

* Install the test WordPress environment:

`
cd climate-tagger
bash bin/install-wp-tests.sh test_db_name db_user 'db_password' db_host version
`

where:
** `test_db_name` is the name for your **temporary** test WordPress database
** `db_user` is the database user name
** `db_password` is the password
** `db_host` is the database host (eg `localhost`)
** `version` is the version of WordPress (eg `4.2.2` or `latest`)

* Run the tests
`phpunit`
