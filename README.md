[![](https://img.shields.io/packagist/v/inspiredminds/contao-eventlist-extended.svg)](https://packagist.org/packages/inspiredminds/contao-eventlist-extended)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-eventlist-extended.svg)](https://packagist.org/packages/inspiredminds/contao-eventlist-extended)

Contao Eventlist Extended
=====================

Contao extension to extend the event list module with some features. 

### Override redirect page

This enables you to define a redirect page other than the one defined in the calendar of the event. This can be useful in situations where you have just one calendar, but you want to display events of that calendar over multiple domains or languages. 

### Canonical tag

The extension also automatically adds a canonical tag on the event reader page, if the current page is not the one defined in the calendar.

### Skip items

Just like in the news list you are able to define how many events should be skipped for this event list.

### Counts

The following template variables will be available:

* `countNumber`: The number of the current event (always starts at 1).
* `countAscending`: The ascending number of the current event (next event is first).
* `countDescending`: The descending number of the current event (next event is last).
* `countTotal`: The total number of available events for this list module.

### Skip current event

If an event list is shown together with an event reader, then this setting will exclude the current event from the list.

## Attributions

Development funded by [Die Heilsarmee in Deutschland K.d.ö.R.](https://www.heilsarmee.de/).
