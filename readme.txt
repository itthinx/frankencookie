=== FrankenCookie ===
Contributors: itthinx
Donate link: https://www.itthinx.com/shop/
Tags: cookie, cookie law, bureaucracy, compliance, cookie directive
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv3

FrankenCookie reminds visitors of the use of cookies.

== Description ==

FrankenCookie reminds visitors of the use of cookies when they visit a site.
It assumes implied consent and allows to hide the informational message by clicking a link.
When the visitor clicks the link, a cookie `frankencookie` is created.
When the cookie is found, the informational message is not shown.

This functionality is provided via the `[frankencookie]` shortcode and a widget. If you would like to use it as a block, use the `Shortcode` block with the plugin's shortcode.

The plugin uses a default text, but you can provide your own alternative. It also provides a link that visitors can click, so that the message does not appear again as long as the cookie `frankencookie` is found when the visitor browses the site.

The `[frankencookie]` shortcode takes the optional parameters `text` to provide an alternative message, `hide` to provide an alternative link message and `class` which allows to indicate additional CSS classes used on the wrapping container.

_"Beware, for I am fearless and therefore powerful."_ - the monster

### IMPORTANT ###

There is *no guarantee* as to whether this plugin is compliant or not with your regional and legal requirements.

Make sure to verify your legal requirements before relying on this or any similar solution to make your site compliant with Cookie Laws.

As a site owner, it is your sole responsibility to deploy appropriate methods within your jurisdiction and for the jurisdictions from which your site can be accessed.

### Feedback ###

Feedback is welcome!

If you need help, have problems, want to leave feedback or want to provide constructive criticism, please do so here at the [FrankenCookie plugin page](https://www.itthinx.com/plugins/frankencookie/).

Please try to solve problems there before you rate this plugin or say it doesn't work. There goes a _lot_ of work into providing you with free quality plugins! Please appreciate that and help with your feedback. Thanks!

#### X / Twitter ####

Follow [@itthinx](https://x.com/itthinx) for updates related to this and other plugins.

### Translations ###

If you would like to contribute a translation, send a pull requests via the plugin's repository on GitHub: [FrankenCookie](https://github.com/itthinx/frankencookie) or use [Translating WordPress](https://translate.wordpress.org/projects/wp-plugins/frankencookie/).

== Installation ==

1. Upload or extract the `frankencookie` folder to your site's `/wp-content/plugins/` directory. You can also use the *Add new* option found in the *Plugins* menu in WordPress.  
2. Enable the plugin from the *Plugins* menu in WordPress.
3. Drag the FrankenCookie widget under *Appearance > Widgets* to a sidebar.
4. Customize the widget's text if you want to.

== Frequently Asked Questions ==

= I have a question, where do I ask? =

You can leave a comment at the [FrankenCookie plugin page](https://www.itthinx.com/plugins/frankencookie/).

= Does it work with caching plugins? =

Yes. FrankenCookie renders the content of the widget and hides it with Javascript that checks if the `frankencookie` cookie (*yummy*) is present.
If it is found, it hides the widget's content.
As what is rendered does not change, it doesn't matter whether a caching mechanism is used or not.
What changes is the behaviour based on the cookie. Of course this will only work if the visitor has Javascript enabled.
Those that don't will always see the message.

= How can I style the output? =

The widget can be styled quite easily using CSS rules.

- the widget's CSS class is `frankencookie` 
- the message is wrapped in a `div` with class `frankencookie-message`
- the link to hide the message is also in a div with class `frankencookie-hide`

Example - show the message at a fixed position at the bottom of the page:

`.frankencookie {
    font-size: 11px;
    margin-top: 2px;
    text-align: center;
    position: fixed;
    bottom: 0;
    color: #f0f0f0;
    background-color: #000;
    z-index: 10000;
}
.frankencookie .frankencookie-message,
.frankencookie .hide {
    display: inline;
    margin: 2px;
}
.frankencookie .frankencookie-hide a {
    color: #fff;
    padding: 2px;
    font-weight: bold;
}
.frankencookie .frankencookie-hide a:hover {
    background-color: #999;
    color: #111;
}`

== Screenshots ==

1. FrankenCookie Widget Settings
2. Example FrankenCookie Widget Appearance

== Changelog ==

See [changelog.txt](https://github.com/itthinx/frankencookie/blob/master/changelog.txt)

== Upgrade Notice ==

Tested with the latest version of WordPress.
