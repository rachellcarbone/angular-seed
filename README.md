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

####  Front End

##### Angular.js User Interface

- Requires Bower 

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

##### API V1 - Slim PHP 

- Requires Composer 

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

XAMP - Windows & Linux - https://www.apachefriends.org

MAMP - OSX - https://www.mamp.info/en/

##### Configure Apache Virtual Host 

These instructions were created with the paths for Windows 8 using XAMP

http://butlerccwebdev.net/support/testingserver/vhosts-setup-win.html

###### Edit the hosts file: C:\windows\system32\drivers\etc\hosts

```
# localhost name resolution is handled within DNS itself.
127.0.0.1       localhost
#	::1         localhost

127.0.0.1		www.newprojectseed.dev
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

### Other Resources

[Link to GitHub Markdown Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet#links)

---

### To Do v1.0

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

### To Do v2.0
- MongoDB Database Design 
- Add a JS Comiler https://www.npmjs.com/package/jscompiler
- Express API http://expressjs.com/
- Package this project for Bower
- Package this project for NPM?
- Gallery / Portfolio Pages
- Blog... maybe just Wordpress setup instructions / integration
