# Angular Seed Project v0.0.1

Welcome to my AngularJS Seed project. As you can see this is still under heavy development (v0.0.1) and isn't ready for users to clone, install and use.

It is still a great reference for ideas on setting up a large AngularJS application of your own.

When this project is complete it will contain a AngularJS user interface with public areas, member areas where users must be logged in, and an admin area where administrative users can manage data saved in the app database.


### Installation

Node / NPM - https://nodejs.org/en/

Windows Installer

Bower - https://www.npmjs.com/package/bower

```
$ npm install -g bower
```

Composer - https://getcomposer.org/download/

```
$ npm install -g getcomposer
```

---

#### Project Directory Structure

```
/api
    /v1                 Slim PHP API Coming Soon
/node_modules           [.gitignored]
/public                 Files that users upload, add sub directories as needed
/www
    /app
    /components         Website components like a signup form or sidebar that is loaded from the state definition
    /config             Angular app .confg and .run methods used to configure the application
    /directives         Directives used by the application
    /filters            Filters used by the application
    /modules            I consider a module to be a grouping of services that are used by another controller or that configure themselfs to act on an event.
    /router             Router files configure the states of the application
    /views              View modules represent different areas of a website.
        /admin          The administrative area for managing the application settings and databases
        /auth           Auth pages like "Login" and "Sign Up".
        /error          Error pages such as "404" and "User Not Authorized".
        /maintenance    The maintenance / under construction blocking page.
        /member         Logged in member pages like the "User Profile" page and "Member Dashboard"
        /public         Public (unauthenticated) states, such as the landing page and "About Us"
    app.js              "theApp" - Loads all of the modules used by the site
    /bower_components   [.gitignored]
    /media              Holds various resources for site content, add more sub directories as needed
        /images         Theme and content images
        /pdfs           PDF content
        favicon.ico     The website fav icon, set in index.html
    /styles             
        /less           Less CSS Files to be compiled into styles.css
        /styles.css     Site theme css file that is included in index.html
```
---

####  Front End

##### User Interface - Angular.js 

Requires Bower 

```
$ bower install
```

Requirements:

- Angular https://angularjs.org/
- Bootstrap http://getbootstrap.com/
- Angular Bootstrap https://angular-ui.github.io/bootstrap/
- Datatables https://www.datatables.net/
- Angular Datatables https://l-lin.github.io/angular-datatables/#/welcome
- Qtip2 http://qtip2.com/
- Angular Qtip2 Directive https://github.com/romainberger/angular-qtip2-directive
- Angular UI Router https://github.com/angular-ui/ui-router
- Font Awesome https://fortawesome.github.io/Font-Awesome/
- JQuery https://jquery.com/
- Moment http://momentjs.com/

---

#### Back End 

##### API V1 - PHP and MySQL

Requires Composer 

```
$ cd api/v1
$ composer install
```


Requirements:

- Grunt http://gruntjs.com/
- Grunt Bower https://github.com/yatskevich/grunt-bower-task
- Grunt Bower Concat https://github.com/sapegin/grunt-bower-concat
- Grunt Composer https://www.npmjs.com/package/grunt-composer
- Grunt Less https://github.com/gruntjs/grunt-contrib-watch
- Grunt Watch https://github.com/gruntjs/grunt-contrib-watch
- Grunt Nodestatic https://github.com/ia3andy/grunt-nodestatic
- Jit Grunt https://www.npmjs.com/package/jit-grunt

---

#### Server Setup - Apache and MySQL

##### Install

- XAMP - Windows & Linux - https://www.apachefriends.org
- MAMP - OSX - https://www.mamp.info/en/

##### Configure Apache Virtual Host 

These instructions were created with the paths for Windows 8 using XAMP

http://butlerccwebdev.net/support/testingserver/vhosts-setup-win.html

###### Edit the hosts file: C:\windows\system32\drivers\etc\hosts

```
# localhost name resolution is handled within DNS itself.
127.0.0.1       localhost
#	::1         localhost

127.0.0.1		www.seed.dev
```

###### Edit httpd-vhosts.conf file: C:\xampp\apache\conf\extra\httpd-vhosts.conf

```
<VirtualHost *:80>
    ServerAdmin webmaster@seed.dev
    DocumentRoot "C:/xampp/htdocs/seed.dev"
    ServerName seed.dev
    ServerAlias www.seed.dev
    ErrorLog "logs/seed.dev-error.log"
    CustomLog "logs/seed.dev-access.log" common
</VirtualHost>
```

---

#### Other Resources

[Link to GitHub Markdown Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet#links)

---

###### To Do v1.0

- MySQL Database Design
- MySQL Upgrade Controlls (Versioning)
- PHP API http://www.slimframework.com/
- Authentication
- Shopping Cart
- Product Categories
- Product Detail
- Read Site Config File
- Admin Managment Pages
- API Docs http://apidocjs.com/#getting-started
- Add config file for the application

###### To Do v2.0
- MongoDB Database Design 
- Add a JS Comiler https://www.npmjs.com/package/jscompiler
- Express API http://expressjs.com/
- Build with Express testing
- Package this project for Bower
- Package this project for NPM?
- Gallery / Portfolio Pages
- Blog... maybe just Wordpress setup instructions / integration
- Language settings (change seed.dev to yoursite.com everywhere)
- Add testing api v1
- Add testing front end
