# GUI
[![Build Status](https://travis-ci.com/alexdodonov/mezon-gui.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-gui) [![codecov](https://codecov.io/gh/alexdodonov/mezon-gui/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-gui) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdodonov/mezon-gui/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdodonov/mezon-gui/?branch=master)
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

### Fields algorithms

This class provides routines for operation with form fields. To init this class use constructor:

```PHP
$fields = new \Mezon\Gui\FieldsAlgorithms([
    'id'=>['type'=>'int'],
    'description'=>['type'=>'string']
]);
```

Here we define two fields.

### Form builder

Form builder is obviously used for building forms )

It can be done like this:

```PHP
$form = new \Mezon\Gui\FormBuilder([
	'id' => [
		'type' => 'int',
		'title' => 'our entity's id'
	],
	'title' => [
		'type' => 'string',
		'title' => 'our entity's title'
	]
]);
```