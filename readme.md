#SASS Color Checker

This script runs through your SCSS source files and grabs all the color definitions. Next it goes through the generated CSS file and finds all the references to the defined colors. Then it generates a color palette that, when clicked, shows you the css that uses the color. It will also generate a list of the colors that aren't being used. Note that due to the way it searches, some of the unused colors might actually be used, it usually happens with mixins.

##Requirements

- [jQuery](http://jquery.com/)
- [Bootstrap](http://twitter.github.com/bootstrap/) **Use version 2.3.2  - Not compatible with 3.x yet**
- [PrismJS](http://prismjs.com/) - CSS Syntax Highlighter
- [Color Conversion Class](http://www.phpclasses.org/package/4598-PHP-Convert-color-values-between-different-models.html) - Hex to RGB conversion *(Account required for download - sorry)*

##To Set Up

Download the libraries listed in the Requirements section.

Copy the `config-dist.php` to `config.php`

Edit `config.php`: 

- Set the path for the Color class
- Set the `$directoryToScan` variable to the directory of SCSS files, and the `$cssFiles` to the location of the generated CSS file.
- Set the paths to the js/css resources (jquery/bootstrap/prism)
*(I have found the CDN service [jsDelivr](http://jsdelivr.com) was useful for loading the js/css libaries with minimal fuss)*

##To Use

Load the `index.php` file and it should generate a page of colors.

##TODO

*In order of interest to me*

- Support either multiple locations for SCSS scanning, and/or recursive finding SCSS files
- Generate a static html page that you can link to as a reference
- Figure out git submodules
- Parse the scss files instead of the generated css
