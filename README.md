FacebookNewscoopBundle
======================

Usefull sevices for integration Newscoop and Facebook

Facebook caches shared urls for better performance. It can cause some problems at the time we want to change title, decscripton of our article because we still have an older version of our article. This is why this plugin is designed.

**Purpose:** Clear an article cache on Facebook.
Installation
-------------
Installation is a quick process:

1. Download FacebookNewscoopBundle using composer
2. Call an install event to create Database schema
3. That's all!

### Step 1: Download FacebookNewscoopBundle using composer
Tell composer to download the bundle by running the command:
``` bash
$ sudo php composer.phar install ahs/facebook-newscoop-bundle
```
Composer will install the bundle to your project's `newscoop/plugins/AHS` directory.
### Step 2: Call an install event to create Database schema
At the beginning of `indexAction` in `DefaultController.php` file add:
``` php
// AHS/FacebookNewscoopBundle/Controller/DefaultController.php
$this->container->get('dispatcher')->dispatch('plugin.install', new \Newscoop\EventDispatcher\Events\GenericEvent($this, array( 'Newscoop Facebook Plugin' => '' )));
```
This will call an install event and will create Database schema for plugin.

Read more about [Lifecycle Subscriber Managing](https://wiki.sourcefabric.org/display/NPS/Lifecycle+Subscriber+Managing).

### Step 3: That's all!
Go to an article edition in Newscoop to see Facebook Newscoop Plugin on the right sidebar in action.

License
-------

This bundle is under the GNU General Public License v3. See the complete license in the bundle:

    LICENSE.txt

About
-------
FacebookNewscoopBundle is a [Sourcefabric o.p.s](https://github.com/sourcefabric) initiative.
