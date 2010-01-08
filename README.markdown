HumanHelp - Alternative Web Interface for Adobe RoboHelp help files
===================================================================

Overview
--------
HumanHelp's goal is to provide a Web interface for [Adobe RoboHelp][1] 
generaged documentation projects, that is user-friendly, search-engine 
friendly, easy to use and deploy and most of all allows for user 
contribution and interaction in the spirit of today's Read-Write Web.

HumanHelp is free software - you are allowed to use it freely for any legal
purpose, distribute it and modify it under the terms of the New BSD license. 
See LICENSE for additional information.

Disclaimer
----------
- HumanHelp is in no way associated with Adobe, and is not edorsed by Adobe. 
- HumanHelp is still undergoing heavy development, and is to be considered 
  experimental / alpha state software. You may use it at your own risk.
- See LICENSE for licensing information and additional notes.

Installation & Usage
--------------------
For now HumanHelp still requires a lot of manual work in order to set up. 
Please contact the maintainers for detailed usage information. 

Overall, to install HumanHelp: (this process may be automated in the future)

- Have Zend Framework 1.9+ in your include_path. If you don't have a PHP 
  environment set up, install [Zend Server CE][2] - as it already includes
  Zend Framework. 
- Place the files somewhere and hook the 'public' directory to your web 
  server somehow: this can be done by creating a virtual host with 'public'
  as it's document root, or using an Alias or symbolic link.
- Create a database: the simplest solution is to use SQLite, but if you 
  expect heavy traffic or need to scale to multiple web servers, you can
  use any other database supported by Zend Framework's Zend_Db (e.g. MySQL,
  PostgreSQL, Oracle, IBM DB2, etc.). To create an SQLite DB, run the following
  command in the project's root directory: 

    cat tools/schema.sql | sqlite3 data/data.sq3

Note that you must have the Sqlite 3 tool installed - the command's name may 
vary but on most systems it's `sqlite3`. 

- Set permissions: the data directory and the data.sq3 file must *both* be 
  writable by the web server.
- Copy application/configs/config.sample.php to application/configs/config.php
  and edit it to suite your needs. 

Now, you should deploy your first book:

- Export the contents of your full RoboHelp project as XHTML
- pick a book name - this name will be used in the URL of the help files. 
  For example if your documentation is for a product called 'My Confabulator',
  you can use 'my-confabulator' as the book name, and your book's URL will be
  http://your.host/hh-path/my-confabulator. 
- Create the directory data/<book name> (e.g. data/my-confabulator) and move 
  all the exported HTML files (usually ending with .html or .htm) into that 
  directory. 
- Create the directory public/media/<book name> and move all images, CSS,
  JavaScript and other media embedded in the XHTML files into that directory.
- Your newly deployed help files should now be in http://your.host/hh-path/book

Enjoy!

Credits
-------
- Author: Shahar Evron <shahar.evron@gmail.com> 
- Contributors: 
- Artwork:

HumanHelp uses the following open-source 3rd party components:

- Zend Framework, <http://framework.zend.com>  
  Copyright (c) 2005-2010 Zend Technologies Inc
- jQuery JavaScript Library, <http://jquery.com>  
  Copyright (c) 2009 John Resig
- jQuery Treeview Plugin, <http://docs.jquery.com/Plugins/Treeview>  
  Copyright (c) 2007 Jörn Zaefferer

Adobe and RoboHelp are registered trademarks of Adobe Systems Incorporated

[1]: http://www.adobe.com/products/robohelp/
[2]: http://www.zend.com/products/server-ce/

