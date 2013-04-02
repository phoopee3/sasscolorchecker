#SASS Color Checker

This script runs through your SCSS source files and grabs all the color definitions. Next it goes through the generated CSS file and finds all the references to the defined colors. Then it generates a color palette that, when clicked, shows you the css that uses the color. It will also generate a list of the colors that aren't being used, but some of these colors might be used in mixins.

##Requirements

- [jQuery](http://jquery.com/)
- [Bootstrap](http://twitter.github.com/bootstrap/)
- [PrismJS](http://prismjs.com/) - CSS Syntax Highlighter
- [Color Conversion Class](http://www.phpclasses.org/package/4598-PHP-Convert-color-values-between-different-models.html) - Hex to RGB conversion

##To Set Up

Download the libraries listed in the Requirements section.

Copy the `config-dist.php` to `config.php`

Edit `config.php`: 

- Set the path for the Color class
- Set the `$directoryToScan` variable to the directory of SCSS files, and the `$cssFile` to the location of the generated CSS file.
- Set the paths to the html resources (jquery/bootstrap/prism)

##To Use

Load the index.php file and it should generate a page of colors.

##TODO

- Parse the scss files instead of the generated css
- Generate a static html page that you can link to as a reference
- Figure out git submodules