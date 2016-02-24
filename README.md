# serpri

 SERialized smart object pretty PRInt for php.

 May show raw serialized data without original clases.

 No any composers or waving a dead chicken required

## Usage

### Simple way
Download this class
`wget https://raw.githubusercontent.com/TrurlMcByte/serpri/master/serpri.php`

And just `include_once 'serpri.php';`

Calls example:

`(new serpri($SomeSmartObject))->process(2);`

`$p=new serpri('file_with_serialized.dump'); $p->process(2);`

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
* $input **mixed** - &lt;p&gt;filename (if file exists), serialized string, array or object&lt;/p&gt;

* $html **int** - &lt;p&gt;1-format as html, 2-include page header/footer with css&lt;/p&gt;




### infile

    \serpri serpri::infile(string $input)

Set serialized data file to process.



* Visibility: **public**


#### Arguments
* $input **string** - &lt;p&gt;filename with serialized data&lt;/p&gt;



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
* $html **int** - &lt;p&gt;1-format as html, 2-include page header/footer with css&lt;/p&gt;


