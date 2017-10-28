# Elevator Assessment Test

## Instructions
First there is an elevator class.

It has a direction (up, down, stand, maintenance), a current floor and a list of floor requests sorted in the direction.

Each elevator has a set of signals: Alarm, Door open, Door close.

The scheduling will be like:

* If available pick a standing elevator for this floor.
* Else pick an elevator moving to this floor.
* Else pick a standing elevator on another floor.

Sample data:
* Elevator standing in first floor
* Request from 6th floor go down to ground(first floor).
* Request from 5th floor go up to 7th floor
* Request from 3rd floor go down to ground
* Request from ground go up to 7th floor.
* Floor 2 and 4 are in maintenance.

## Installation

Install [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Terminal:
```
$ git clone git@github.com:AustinW/elevator.git
$ cd elevator
$ php composer.phar install
```

## Testing

```
$ php composer.phar phpunit
```
Or:
```
$ phpunit
```
