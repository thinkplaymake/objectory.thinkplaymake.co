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


API Docs:
============

All API calls are POST or GET to /api/v1/...

POST to / - Creates a new Objectory Object
			Requires: description (string) - briefly describe the object, ie. a unique identifier or book name / author
					  type (objectory_object_type: book,camera,toy,person,story,other)


Random/Todo
============
- Filebased/CDN Caching for objects and stories
- Filter on object types
- Add support for groupings of objects and custom names
- Add support for custom meta against objects
- Add support for automatic story creation on object creation, along with location data
- Add filters for GETs to hide private information
- Add public/private keys for 'finding' object support