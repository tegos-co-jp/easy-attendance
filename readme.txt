=== Easy Attendance ===
Contributors: tegos
Tags: CSV Attendance Member
Donate link: https://www.tegos.co.jp/easy_attendance
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

"Easy Attendance" provides custom post types in a simplified format.
It is possible to output registered custom post types in CSV format.
You can use "Easy Attendance" to output CSV with members and time period.

== Description ==

"Easy Attendance" provides custom post types in a simplified format.
It is possible to output registered custom post types in CSV format.
You can use "Easy Attendance" to output CSV with members and time period.

== Installation ==
1. Upload All files to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.



== Frequently Asked Questions ==
= I want to change the input field name. Where can I do that? =

1. Go to the 「Easy Attendance Settings」 page under Settings menu.
2. You may change the name of any entry on this page.
3. Caution. When you change the input item name, the CSV header will be changed at the same time.

= I would like to add a member. Where can I do that? =

1. Members can be added by modifying the source code.
   You will find a file named 「easy-attendance.php」 directly under the plugin directory
2. You can add members to the array after row 57.See


== Screenshots ==
1. Setting screen
Change the name of each item before starting to use it. The CSV will be output with the name set on this screen.
[/assets/wp-to-trello-screenshot-1.png]

2. Add Attendance screen
Enter attendance on this screen
[/assets/wp-to-trello-screenshot-2.png]

3. CSV Output screen
You can specify a time period range and output a CSV.
[/assets/wp-to-trello-screenshot-3.png]

4. Edit Member
You can directly modify the source and add members.(easy-attendance.php row 57)
[/assets/wp-to-trello-screenshot-4.png]

== Support ==
[easy-attendance github page](https://github.com/tegos-co-jp/easy-attendance)

== Upgrade Notice ==

== Changelog ==

= 1.0.0 =
* Initial release.

= 1.0.1 =
* Add Languege File (Japanese)

= 1.0.2 =
* Tested to work with version 6.0

