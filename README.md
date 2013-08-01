FacebookNewscoopBundle
======================

Usefull sevices for integration Newscoop and Facebook

Facebook caches shared urls for better performance. It can cause some problems at the time we want to change title, decscription of our article because we still have an older version of our article. This is why this plugin is designed for.

**Purpose:** Clear an article cache on Facebook.

Installing Newscoop Facebook Plugin Guide
-------------
Installation is a quick process:


1. Installing plugin through our Newscoop Plugin System
2. That's all!

### Step 1: Installing plugin through our Newscoop Plugin System
Run the command:
``` bash
$ php application/console plugins:install "ahs/facebook-newscoop-bundle" --env=prod
```
Plugin will be installed to your project's `newscoop/plugins/AHS` directory.


### Step 2: That's all!
Go to an article edition in Newscoop to see Newscoop Facebook Plugin on the right sidebar in action.

Also read more about [Lifecycle Subscriber Managing](https://wiki.sourcefabric.org/display/NPS/Lifecycle+Subscriber+Managing).

License
-------

This bundle is under the GNU General Public License v3. See the complete license in the bundle:

    LICENSE.txt

About
-------
FacebookNewscoopBundle is a [Sourcefabric o.p.s](https://github.com/sourcefabric) initiative.

[1]: http://getcomposer.org/doc/00-intro.md
[packagist]: https://packagist.org/
[github]: https://github.com/
[satis]: https://github.com/composer/satis
