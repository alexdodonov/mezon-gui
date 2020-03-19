# GUI [![Build Status](https://travis-ci.com/alexdodonov/mezon-gui.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-gui) [![codecov](https://codecov.io/gh/alexdodonov/mezon-gui/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-gui)
## Intro

Mezon provides set of classes for GUI creation.

## Installation

Just print in console

```
composer require mezon/gui
```

And that's all )

## Usage

### DateTimeUtils class

This class provides utilities for date and time tasks. For example this call:

```PHP
Mezon\Class\DateTimeUtils::isToday('2020-02-02');
```

Will return true if the passed date is a today and false otherwise.

And this method will return true if the passed date was a yesterday:

```PHP
Mezon\Class\DateTimeUtils::isYesterday('2020-02-02');
```

But we also can get name of the month by it's code:

```PHP
Mezon\Class\DateTimeUtils::locale = 'ru';
var_dump(Mezon\Class\DateTimeUtils::dayMonth('2020-02-02'));
```