# serpri

[![Build Status](https://img.shields.io/travis/TrurlMcByte/serpri/master.svg?style=flat-square)](https://travis-ci.org/TrurlMcByte/serpri)

[![Latest Stable Version](https://poser.pugx.org/trurlmcbyte/serpri/v/stable)](https://packagist.org/packages/trurlmcbyte/serpri) [![Total Downloads](https://poser.pugx.org/trurlmcbyte/serpri/downloads)](https://packagist.org/packages/trurlmcbyte/serpri) [![Latest Unstable Version](https://poser.pugx.org/trurlmcbyte/serpri/v/unstable)](https://packagist.org/packages/trurlmcbyte/serpri) [![License](https://poser.pugx.org/trurlmcbyte/serpri/license)](https://packagist.org/packages/trurlmcbyte/serpri)

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

### "Hard" way

`composer require trurlmcbyte/serpri`


## Documentation
 [Documentation](serpri.md)