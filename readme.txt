=== Climate Tagger ===
Contributors: Aptivate
Tags: tags, tag cloud, suggestion, tag suggestion, reegle api
Requires at least: 3.7
Tested up to: 4.2.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Suggests tags for your posts using the reegle Tagging API

== Description ==

This plugin uses the [reegle Tagging
API](http://www.reeep.org/reegle-tagging-api) to suggest tags for your
posts. The tags are displayed in a word cloud, with the most relevant tags
appearing larger.

The plugin is based on [Thoth's Suggested Tags](https://wordpress.org/plugins/thoth-suggested-tags/)

[Follow this project on Github](https://github.com/aptivate/climate-tagger)


== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory.
2. Activate it through the **Plugins** menu in WordPress.
3. [Register with reegle for your API key](http://api.reegle.info/register)
4. Enable the plugin and enter the API key (**Settings** -> **Climate Tagger**)
5. The Suggested Tags box should now appear when you create a post.

== Changelog ==

= 1.0.0 =
* First version

= 1.0.1 =
* Documentation updates only

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
