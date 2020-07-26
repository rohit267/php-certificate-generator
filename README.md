# PHP CERTIFICATE GENERATOR
This project is an extension of https://github.com/zedomel/certificate-generator. That was a command line application, modified to make it a web based app.

## Installation
	
You need composer to install it, run 'composer install', it will install all dependencies

Import the sql file in your database and and MySQL connection fila name is portaldbiconnect.php and is located in '../private/php/' , make sure you have same folder structure or you can modify the following files according to your structure: index.php, download.php, genrate.php, createNew.php.

## Description
The defult username and password is 'admin'. After login create a workshop event by create new button, enter Event Name and choose the CSV file according to format of sample.csv. After creating you can genrate certificate using the link in the table, the user can download the certificate by the share link which will be visible after genrating certificates. User enters his email and it searchs the file if exists and it start downloads. The default design in sample.html, modify it according to your needs and also the CSV. Make your you are designing using inline css  while designing. The output pdf is stored in output folder.


## PDF Certificate Generator
PHP script to generate certificates for participation in events.

This script uses TCPDF library to generate PDF's from HTML templates.
Given a CSV file with person's details and data to be printed into PDF's file is possible create customized certificates including background images.
There are a range of options (listed bellow) to customize certificates.

## Dependencies

* [TCPDF](https://github.com/tecnickcom/TCPDF): PHP PDF Library.
* [PHPMailer](https://github.com/PHPMailer/PHPMailer): a full-featured email creation and transfer class for PHP.
* [GetOpt](https://github.com/getopt-php/getopt-php): a library for command-line argument processing.


## Creating a HTML template

The recommend way to create HTML template is using a HTML editor or a text editor. But it's also possible to use converting tools like [ZAMZAR](https://www.zamzar.com/convert/ppt-to-html/) or [PowerPoint2HTML](https://www.idrsolutions.com/online-powerpoint-to-html5-converter/) to convert PowerPoint presentation to HTML.

The problem with using conversion tools is that sometimes the HTML created contains tags not handle by TCPDF and the generate PDF certificate will not work correctly.
**Be sure that you HTML template contains only TCPDF supported tags.**
Currently supported tags are: a, b, blockquote, br, dd, del, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6, hr, i, img, li, ol, p, pre, small, span, strong, sub, sup, table, tcpdf, td, th, thead, tr, tt, u, ul
**NOTE: all the HTML attributes must be enclosed in double-quote.** Refer to [TCPDF documentation](https://tcpdf.org/docs/srcdoc/TCPDF/class-TCPDF/) to get more details about supported tags.

CSS styles must be inline into HTML files in order to be handle by TCPDF.

# HTML templace placeholders

It's possible to use placeholders in HTML template to be replaced by data from CSV files (see bellow). The placeholders must use the Jinja syntax, for example:
```xml
<p>{{ first_name }}</p>
```
for define placeholder for a "variable" called `first_name`inside a HTML paragraph tag. The PHP script will search for any placeholder (`{{ * }}`) and replaces it by the value from a column with same name in the CSV file provided as argument to the script (`-d` option, see bellow).

A special placeholder `{{ %now% }}` is available and will be replaced by the current date when generating certificates.

# Creating a PDF certificate

The main file of Certificate Generator is `certgen.php`. To get a list of available options run:
```sh
php certgen.php --help
```

# Options

There are a set of options available to customize the certificate generator that should be passed at command line:
* `-i` or `--input` **(required)**: the HTML template file with placeholders in Jinja format (e.g.: `{{ first_name }}`).
* `-o` or `--output`: PDF output file/directory (default: `output.pdf`).
* `-d`or `--data`: CSV file with header at first line that will be used as source to replace placeholders in HTML template. The header (column) names must be the same as in template HTML.
* `-p` or `--page`: the page size (default: `'A4'`).
* `-e`or `--email_col`: the email column's name in CSV data file (default: `email`). If a column with that name is present in CSV file then the generated certificates will be sent to attendants emails.
* `-s` or `--subject`: the subject of emails sent to attendants (default: `Certificate`).
* `-m` or `--message`: a text or a path to file with the message (body) of the email sent to attendants (default: `Here is yoor certificate`).
* `-r`or `--replyto`: the email to set as reply to (default: `example@certificategenerator.com`).
* `-a`or `--attach`: addiotional attachments to sent in emails. You can provide as many attachments you want by provinding multiple arguments for this parameter (e.g. `-a path-to-attachment-1.pdf -a path-to-attachment-2.txt`).
* `-y` or `--offset`: Y offset of HTML cell (defaul: 0).
* `?` or `--help`: show help and quit.
* `-f` or `--font`: add a font to TCPDF and quit (do not generate certificates).

# Add a font to TCPDF

The TCPDF comes with a limited number of fonts. To convert and add a new font to TCPDF you can use the options `-f` or `--font` of Certificate Generator script. For example to add the `Ubuntu-Light` font to TCPDF you can use the follow command:
```sh
php certgen.php -f Ubuntu-Light.ttf
```
In case of success you will received a message with the font name's that should be used in configuration file (see bellow) replacing the `DEFAULT_FONT` constant.
```
Font added: ubuntumedium
Change DEFAULT_FONT value to ubuntumedium in config.php to use the new font!
```


## Contributing
Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/zedomel/certificate-generator/issues).

If you found a mistake in the docs, or want to add something, go ahead and amend the wiki - anyone can edit it.

# License
Certificate-Generatoris publised under [GNU GENERAL PUBLIC LICENSE v3.0](https://opensource.org/licenses/GPL-3.0).
