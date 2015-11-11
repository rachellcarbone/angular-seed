# Angular Seed Project

---

## Installation

Node / NPM - https://nodejs.org/en/

Windows Installer

Bower - https://www.npmjs.com/package/bower

$ npm install -g bower

Composer - https://getcomposer.org/download/

$ npm install -g getcomposer

---

###  Front End

#### Angular.js User Interface

---

### Back End 

#### API V1 - Slim PHP 

- Requires Bower 

$ cd api/v1
$ bower install


#### API V2 - Express JS


// Api Doc Js is used to generate documentation for the api
http://apidocjs.com/#getting-started

npm install apidoc -g
apidoc -i api/v2/controllers/ -o docs/


---

### Running the Server


#### Node Static



#### Alternatively run Apache and MySQL

http://butlerccwebdev.net/support/testingserver/vhosts-setup-win.html

1. Edit the hosts file: C:\windows\system32\drivers\etc\hosts

### localhost name resolution is handled within DNS itself.
##127.0.0.1               localhost
###	::1             localhost

##127.0.0.1		www.newprojectseed.dev

2. Edit httpd-vhosts.conf file: C:\xampp\apache\conf\extra\httpd-vhosts.conf


##<VirtualHost *:80>
    ##ServerAdmin webmaster@dummy-host.example.com
    ##DocumentRoot "C:/xampp/htdocs/dummy-host.example.com"
    ##ServerName dummy-host.example.com
    ##ServerAlias www.dummy-host.example.com
    ##ErrorLog "logs/dummy-host.example.com-error.log"
    ##CustomLog "logs/dummy-host.example.com-access.log" common
##</VirtualHost>

---

## Other Resources

[Link to GitHub Markdown Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet#links)