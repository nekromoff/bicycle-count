Bicycle counting helper
============

Generates printable survey documents (PDF) with locations and combinations of directions based on TSV (CSV) provided.

Installation
------------
- download files using `git clone` or as a ZIP file
- run `composer install`
- edit TSV input file (see below)
- Open generate.php in your browser

TSV input file (locations.tsv):
------------
|Location|Sublocation|Exact place|Note|Direction 1|Direction 2|Direction 3|...|Direction X|
|----|----|----|----|----|----|----|----|----|
|River|Bridge X|Link to streetview|By the end of bridge|City center|Street X|Under bridge|Riverbank|District Y|

Dependencies
------------
### mPDF

(c) 2018+ Daniel Duris
