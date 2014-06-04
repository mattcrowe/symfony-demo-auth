Symfony Auth Demo
========================

Welcome to the my auth demo using Symfony Standard Edition.

Installing the Standard Edition
----------------------------------

Refer to: https://github.com/symfony/symfony/blob/master/README.md

Notes regarding this demo
-------------------------------------

To setup a new database with fixtures:

php console doctrine:database:drop --force
php console doctrine:database:create
php console doctrine:schema:create
php console doctrine:fixtures:load

User Login

http://example/app_dev.php/auth/manage/login

Staff Login

http://example/app_dev.php/auth/admin/login

Files of Interest

#app
app/AppKernel.php

#config
app/config/security.yml #various params
app/config/routing_dev.yml #UserBundle/Resources/config/routing.yml

#Bundle
src/User/Bundle/AcmeDemoBundle.php #includes "build" function

#Entity
src/User/Bundle/Entity/Role.php
src/User/Bundle/Entity/RoleObject.php
src/User/Bundle/Entity/Staff.php            #staff can access areas behind "admin" firewall
src/User/Bundle/Entity/User.php             #user can access areas behind "manage" firewall

#Controller
src/User/Bundle/Controller/AuthController.php   #contains unsecured login actions
src/User/Bundle/Controller/AdminController.php  #contains secured actions behind "admin" prefix
src/User/Bundle/Controller/ManageController.php #contains secured actions behind "manage" prefix

#Views
src/User/Bundle/Resources/views/Auth/adminLogin.html.twig   #staff login form
src/User/Bundle/Resources/views/Auth/manageLogin.html.twig  #user login form
src/User/Bundle/Resources/views/Admin/home.html.twig        #staff home page
src/User/Bundle/Resources/views/Admin/agent.html.twig       #staff agent page, requires ROLE_AGENT
src/User/Bundle/Resources/views/Admin/editor.html.twig      #staff editor page, requires ROLE_EDITOR
src/User/Bundle/Resources/views/Manage/home.html.twig       #user home page

#Security (Base)
src/User/Bundle/Security/BaseAuthenticateToken.php
src/User/Bundle/Security/BaseAuthenticationListener.php
src/User/Bundle/Security/BaseAuthenticationProvider.php
src/User/Bundle/Security/BaseAuthorize.php
src/User/Bundle/Security/BaseEncoder.php
src/User/Bundle/Security/BaseProvider.php

#Security (Staff)
src/User/Bundle/Security/Staff/StaffAuthenticationListener.php
src/User/Bundle/Security/Staff/StaffAuthenticationProvider.php
src/User/Bundle/Security/Staff/StaffAuthorize.php
src/User/Bundle/Security/Staff/StaffProvider.php

#Security (User)
src/User/Bundle/Security/User/UserAuthenticationListener.php
src/User/Bundle/Security/User/UserAuthenticationProvider.php
src/User/Bundle/Security/User/UserAuthorize.php
src/User/Bundle/Security/User/UserProvider.php

#Dependency Injection
src/User/Bundle/DependencyInjection/StaffSecurityFactory.php
src/User/Bundle/DependencyInjection/UserSecurityFactory.php