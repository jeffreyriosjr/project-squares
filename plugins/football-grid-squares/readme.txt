=== Football Grid Squares ===
Contributors: Insight Dezign
Tags: superbowl squares, football squares, football grid, sports squares, sports grid
Donate link: https://footballgridsquares.com/donate/
Requires at least: 4.9
Tested up to: 5.0.3
Requires PHP: 5.6
Stable tag: 1.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create your own football/Superbowl Squares game for you and your friends. Use this simple shortcode based plugin to create as many grids as you want.

== Description ==
Create your own football/Superbowl Squares game for you and your friends. Use this simple shortcode based plugin to create as many grids as you want. 

* Each grid has it's own URL. 
* Only the grid author can generate the numbers after the squares are full. 
* Password protect your grids with WordPress's built in visibility settings. 
* Built on Bootstrap and fully responsive. 
* Add your own Pool rules right in the text editor. 
* Easy for everyone to use.

== Installation ==
Install Football Grid Squares just as you would any other WP Plugin:

1. Download Simple Custom CSS from WordPress.org.
1. Unzip the .zip file.
1. Upload the Plugin folder (football-grid-squares/) to the wp-content/plugins folder.
1. Go to Plugins Admin Panel and find the newly uploaded Plugin, "Football Grid Squares" in the list.
1. Click Activate Plugin to activate it.

Begin using the plugin by going to Squares in the Admin Menu and adding a new squares post. 
Add the following shortcode to display the Squares grid and provide players with the post URL to begin picking.

**[football_squares_grid team_one="N.E. Patriots" team_two="L.A. Rams" symbol="$" price="10.00"]**

Add your Rules below the the shortcode in the editor.

Example: 

**Rules**

THE NUMBERS FOR THE FOOTBALL SQUARES WILL BE SELECTED WHEN THE GRID IS FILLED.

**How it works:**

Once all the squares have been selected, we will randomly pick numbers from 0-9 for each team in the Super Bowl, and assign that number to a particular row or column. These numbers represent the last number in the score of each team. In other words, if the score is AFC 17 - NFC 14, then the winning square is the one with an AFC number of 7, and an NFC number of 4.

**Winnings Breakdown for Squares**

End of 1st Quarter: 20%
End of 2nd Quarter: 20%
End of 3rd Quarter: 20%
End of Game: 40%

== Screenshots ==
1. Create and manage squares post types.
2. Default loaded shortcode and rules.
3. Square as viewed from the front end.
4. Author has edit and clear function enabled.
5. Responsive design.


== Changelog ==
= 1.3.2 =
* Fix display of x in box for mobile screens
* Change bootstrap outline color for squares

= 1.3.1 =
* Fix support for Gutenberg editor


= 1.3 =
* Add clear square option for admin
* Add function to hide other users squares in admin
* Add default content for new squares


= 1.2 =
* Sanitize the name with sanitize_text_field
* Sanitize the square ID with intval


= 1.1 =
* Remove unnecessary jQuery.js
* Remove unnecessary poppers.js
* Localize Bootstrap js
* Localize Bootstrap css
* Add edit square option for admin
* Change data files to save by id instead of slug


= 1.0 =
* Initial release.