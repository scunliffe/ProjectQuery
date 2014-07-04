ProjectQuery
============

![ProjectQuery Logo][5]

Utilities to query your project code base for various patterns/conditions.

Searching your code base only works up to certain point and then you realize that a Regular Expression has severe limitations.

For example how would you find:

 - All occurences of `<script>` tags that link to an external file that are **NOT** in the `/util/` directory?
 - All files containing form elements `button`, `input`, `select`, `textarea` but no `form` element?
 - All occurences of HTML5 `data-???` attributes and their associated values?
 - etc.

This can be accomplished in pretty much any language however I chose PHP as a simple solution that can be run across a large code base and can be scripted easily and quickly (without compiling) by anyone with access to Google. ;-)

Start with the `baseQuery.php` file and roll your own custom query or take any of the sample queries and adjust to suit your needs.


Requirements / Features
=======================

 - Recursively search all files in your project directory
 - Ability to restrict file scan iterations and/or extend execution timeout for extended query logic
 - Filter by filetypes
 - Easily search for keywords/regexes within files to "include" and likewise search for keywords/regexes to "exclude"
 - Easily search for multiple keywords... and compare their match positions within a file
 - Basic result details provided by default, fully customizable with no restrictions

Sample Query Templates
=======================

The following templates will get you started... just pick the one that best matches your situation.

 - Base template to recurse all files ([baseQuery.php][1])
 - Find all files including `X` but excluding `Y` ([baseQueryIncludeThisExcludeThis.php][2])
 - Find all files that include `X` and `Y` but **not** in the desired order ([baseQueryPositionCheck.php][3])
 - Find arbitrary values and return each matching file with a list of matches ([baseQueryReturnMatches.php][4])
 - (more to come!)


[1]: https://github.com/scunliffe/ProjectQuery/blob/master/baseQuery.php
[2]: https://github.com/scunliffe/ProjectQuery/blob/master/baseQueryIncludeThisExcludeThis.php
[3]: https://github.com/scunliffe/ProjectQuery/blob/master/baseQueryPositionCheck.php
[4]: https://github.com/scunliffe/ProjectQuery/blob/master/baseQueryReturnMatches.php
[5]: https://raw.githubusercontent.com/scunliffe/ProjectQuery/master/projectquery.png
