# Angular Seed Project v0.0.1

Welcome to my AngularJS Seed project. As you can see this is still under heavy development (v0.0.1) and isn't ready for users to clone, install and use.

It is still a great reference for ideas on setting up a large AngularJS application of your own.

When this project is complete it will contain a AngularJS user interface with public areas, member areas where users must be logged in, and an admin area where administrative users can manage data saved in the app database.


### Installation

Node / NPM - https://nodejs.org/en/

Windows Installer

Bower - https://www.npmjs.com/package/bower

$ npm install -g bower

Composer - https://getcomposer.org/download/

$ npm install -g getcomposer

---

####  Front End

##### Angular.js User Interface

---

#### Back End 

##### API V1 - Slim PHP 

- Requires Bower 

$ cd api/v1
$ bower install


##### API V2 - Express JS


// Api Doc Js is used to generate documentation for the api
http://apidocjs.com/#getting-started

npm install apidoc -g
apidoc -i api/v2/controllers/ -o docs/


---

#### Server Setup

##### Apache and MySQL

##### Install

XAMP - Windows & Linux - https://www.apachefriends.org

MAMP - OSX - https://www.mamp.info/en/

##### Configure Apache Virtual Host 

These instructions were created with the paths for Windows 8 using XAMP

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

http://butlerccwebdev.net/support/testingserver/vhosts-setup-win.html


##### Node Static

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

### To Do v2.0
- MongoDB Database Design 
- Add a JS Comiler https://www.npmjs.com/package/jscompiler
- Express API http://expressjs.com/
- Package this project for Bower
- Package this project for NPM?
- Gallery / Portfolio Pages
- Blog... maybe just Wordpress setup instructions / integration
