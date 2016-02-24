# serpri

 SERialized smart object pretty PRInt for php.

 Usefull to compact print crosslinked and recursive objects.
 In HTML mode also creating links between recusive and referal objects.
 May show raw serialized data without original clases.
 Low memory consumption in file mode with large files (mostly used sream get|print).
 Fast (and bit furious).
 No any composers or waving a dead chicken required

## Usage

### Simple way
Download this class
`wget https://raw.githubusercontent.com/TrurlMcByte/serpri/master/serpri.php`

And just `include_once 'serpri.php';`

Calls examples:

`(new \serpri($SomeSmartObject))->process(2);`

`$p=new \serpri('file_with_serialized.dump'); $p->process(2);`

### Hard way

Think for yourself.

# Documentation

Methods
-------


### __construct

    \serpri serpri::__construct(mixed $input, int $html)

serpri object constructor.



* Visibility: **public**


#### Arguments
* $input **mixed** - *filename (if file exists), serialized string, array or object*

* $html **int** - *1-format as html, 2-include page header/footer with css*




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

    mixed serpri::process(int $html)

Output formatted object.

use ob_start|ob_end_clean to put in var

* Visibility: **public**


#### Arguments
* $html **int** - *1-format as html, 2-include page header/footer with css*


