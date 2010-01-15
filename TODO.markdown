HumanHelp - TODO
================

Administration
--------------
 - Create an admin area
 - Allow admins to approve / delete / archive comments
 - Allow admins to publish books / update books (?) 
 - Allow (some) configuration management through the admin panel 

Authentication & Access Control 
-------------------------------
 - Integrate with external authentication backends (Zend_Auth) 
 - Implement built-in user management (at lest for admin users)
 - Allow control over who is admin
 - Allow control over who can comment and do other future actions  

Search
------
 - Integrate (optionally) Google Search AJAX API
   - Users need to set their Google API key
 
 - Implement internal search engine
   - Pluggable
   - Zend_Search_Lucene by default
   - Other external search engines: Solr, Sphinx, Lucene through Java Bridge (?)

