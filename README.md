FacebookNewscoopBundle
======================

Usefull sevices for integration Newscoop and Facebook

Facebook caches shared urls for better performance. It can cause some problems at the time we want to change title, decscripton of our article because we still have an older version of our article. This is why this plugin is designed for.

**Purpose:** Clear an article cache on Facebook.


The whole plugin system (installation/management) is based on [Composer][1] packages.
Packages can live on [github.com][github] or your own private git repositories but they must be listed on [packagist.org][packagist] or private own (based on [satis][satis]) composer repositories.

For now we support only this way of plugins management. But we have some plans to make an installation from .zip files.

The whole management process should be done through our `Newscoop\Services\Plugin\ManagerService` class. It's important because this way we allow for developers to react on installation/remove/update events (and more) in their plugins.

#### Installation/Updating/Removing

#### Installation

``` bash
    $ php application/console plugins:update "ahs/facebook-newscoop-bundle" --env=prod # install plugin
```
Install command will add your package to your composer.json file (and install it) and update plugins/avaiable_plugins.json file (used for plugin booted as Bundle). This command will also fire `plugin.install` event with `plugin_name` parameter in event data

#### Removing

``` bash
    $ php application/console plugins:remove "ahs/facebook-newscoop-bundle" --env=prod # remove plugin
```
Remove command will remove your package from composer.json file and update your dependencies (this is the only way at the moment), It will also remove info about plugin from `plugins/avaiable_plugins.json` file and fire `plugin.remove` event with plugin_name parameter in event data.

#### Updating

``` bash
    $ php application/console plugins:update "ahs/facebook-newscoop-bundle" --env=prod # update plugin
```

Update command is a little specific - firstly It will remove your plugin form Newscoop (but It won't fire `plugin.remove` event) and after that will install your plugin again (again without `plugin.install` event). After everything of this It will fire `plugin.update` event.

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
Go to an article edition in Newscoop to see Facebook Newscoop Plugin on the right sidebar in action.

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
