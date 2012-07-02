objectory.thinkplaymake.co
============

About
============
objectory is a platform and factory for moving object projects. moving object projects are where users can release and find objects which move around the world, attaching stories to them as they go - think bookcrossing.org and disposablememoryproject.org

we're building the platform as we go, adding new functionality to react to our needs, with the aim of eventually converting the disposable memory project to use objectory, as well as allowing other developers and users to create their own similar projects.

the code will be available at all times via github (https://github.com/thinkplaymake/objectory.thinkplaymake.co), and we'll be taking public feedback on board always. to keep posted, subscribe to the newsletter.


Installing
============
1. Clone this git
2. Install on your local LAMP server
3. Create a mongodb database
4. Add in the mongodb connection settings to /public/htdocs/api/v1/_localconfig.default.php
5. Rename _localconfig.default.php to _localconfig.php
6. Point a domain at /public/htdocs/
7. You should now be able to POST data to http://yourlocalcopy.of.objectory/api/v1/ to create an object


API Docs
============

Coming Soon, once an API method is properly exposed and ready for data!


Random/Todo
============
- Add 'test' flag to all object creation for removing test data from db
- Filebased/CDN Caching for objects and stories