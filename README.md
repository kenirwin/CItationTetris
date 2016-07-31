# Citation Tetris

**Citation Tetris** is a game to help library workers and others practice quickly identifying the kind of citation they are looking at. Options include "article", "book chapter", and "book". 

This tool can be readily adapted for other identification/sorting practice for both text and images as well. See alternate demos below.

## Demo

Demo available online at:
http://www6.wittenberg.edu/lib/ken/demo/CitationTetris

Alternate demo #1: sorting animals by Linnean Class:
http://www6.wittenberg.edu/lib/ken/demo/CitationTetris?settings=animal

Alternate demo #2: photo-based animal sorting 
http://www6.wittenberg.edu/lib/ken/demo/CitationTetris?settings=pix

Alternate demo #3: U.S. presidents by party: 
http://www6.wittenberg.edu/lib/ken/demo/CitationTetris?settings=presidents

Alternate demo #4: Musical instruments by type: 
http://www6.wittenberg.edu/lib/ken/demo/CitationTetris?settings=instruments

## Installation

The basic functionality of Citation Tetris should work "out of the box" upon moving the files to a suitable server. The leaderboard functions, however, require access to MySQL. To set up the leaderboard:
1. create a database or using an existing MySQL database
2. copy the file `global_settings_example.php` to `global_settings_example.php`
3. modify the variables in `global_settings.php` to connect to the database
4. use the `leaderboard.sql` file to create the table in your mysql database (you can do this by copying and pasting the sql file)

The `global_settings.php` file includes an `$audioOK` variable; by default it is set to false. If your server supports playing mp3 audio, you can set it to true. (Servers without support for this feature may hang significantly if the setting is turned on, so the default is to false.)

You may add a Google Analytics ID to the appropriate variable in `global_settings.php` to track use of the program in Google Analytics.


## Configuration

The settings file defines the default configuration for an installation. By default, the program looks for a `settings.php` file, and fails back to `settings_bib.php`. (Note: the program does not install with a `settings.php` file -- that is reserved for local use. When freshly installed, the program defauts to using `settings_bib.php`.) Additional settings can be supported by the same installation by creating additional settings files; additional settings files must be named `settings_xxx.php` where `xxx` is replaced with an apprpriate string. (e.g. settings_animal). As shown in the alternate demo link above, the `settings_animal.php` file describes the configuration for a game playable at the URL `CitationTetris?settings=animal`.

The two most important variables in the settings files are `$buttons` and `$data_file`: 
* `$buttons` array, which defines the allowable answers (e.g. "book","chapter","article" or "mammal", "fish", "bird")
* `$data_file` points to a json file such as bibliography.json or animals.json

### Generating JSON files

The `prep_files.php` file is a command-line tool for generating JSON-formatted data suitable for use with the program. Plain text files of categorized data go in the `raw-data/` folder. It contains a subfolder for each knowledge area (bibliography, animals, presidents) and each subfolder contains a plain text file with a list of examples (one item per line). So the "animals" folder contains files for "mammals", "fishs", and "birds". (Note that these files are named as plurals, but not very smart plurals -- just append an extra 's' to the file name, even if incorrect such as 'fishs' and 'brasss'.)

Run `prep_files.php on the command line, giving the knowledge area corresponding to the folder name as an argument, e.g.:

`php prep_files.php animals`

This will generate a correctly-formatted JSON file `animals.json` in the `data-files` directory. When establishing a new JSON file, you will also need to create a new corresponging `settings_FILENAME.php` file in the `settings/` folder in order to play a game with that data file.  

## Credits
Written by Ken Irwin, Wittenberg University

"Atari" font created by Genshichi Yasui with a freeware license: http://www.fontspace.com/genshichi-yasui/atari-font 

"Press Start 2P" font by Cody "CodeMan38" Boisclair, used under the Open Font License: https://fonts.google.com/specimen/Press+Start+2P?query=press&selection.family=Press+Start+2P

Structure based in part on Tetris with jQuery by Franck Marcia (MIT License):
http://fmarcia.info/jquery/tetris/tetris.html

Animal photos used under the Creative Commons CC0 from https://pixabay.com

## License

Licensed under the GNU Affero General Public License.