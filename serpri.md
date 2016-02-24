Methods
-------

### __construct

    \serpri serpri::__construct(mixed $input, integer $html)

serpri object constructor.

* Visibility: **public**

#### Arguments
* $input **mixed** - *filename (if file exists), serialized string, array or object*
* $html **integer** - *1-format as html, 2-include page header/footer with css*

### infile

    \serpri serpri::infile(string $input)

Set serialized data file to process.

* Visibility: **public**

#### Arguments
* $input **string** - *filename with serialized data*

### instring

    \serpri serpri::instring($str)

Set serialized string to process.

* Visibility: **public**

#### Arguments
* $str **mixed**

### process

    mixed serpri::process(integer $html)

Output formatted object.

use ob_start|ob_get_clean to put in var

* Visibility: **public**

#### Arguments
* $html **integer** - *1-format as html, 2-include page header/footer with css*

