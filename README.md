ProjectQuery
============

Utilities to query your project code base for various patterns/conditions.

Searching your code base only works up to certain point and then you realize that a Regular Expression has severe limitations.

For example how would you find:

 - All occurences of `<script>` tags that link to an external file that are **NOT** in the `/util/` directory?
 - All occurences of HTML5 `data-???` attributes and their associated values?
 - etc.

This can be accomplished in pretty much any language however I chose PHP as a simple solution that can be run across a large code base and can be scripted easily and quickly (without compiling) by anyone with access to Google. ;-)

Start with the `baseQuery.php` file and roll your own custom query or take any of the sample queries (to be added shortly) and adjust to suit your needs.


Requirements / Features
=======================

 - Recursively search all files in your project directory
 - Ability to restrict file scan iterations and/or extend execution timeout for extended query logic
 - Filter by filetypes
 - Easily search for keywords/regexes within files to "include" and likewise search for keywords/regexes to "exclude"
 - Easily search for multiple keywords... and compare their match positions within a file
 - Basic result details provided by default, fully customizable with no restrictions
