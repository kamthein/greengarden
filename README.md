# prepousse 🌱

## ------ CONTRIBUTION ------

<p>Help us to make this website great !!

Find the license here : https://choosealicense.com/licenses/gpl-3.0/
or in the file LICENSE at the root of the source code.</p>

## ------ INSTALL ------

- Download or clone the project source code
- Create your SQL database - Link your database in the file .env
- Create Tables :
  ```bash
  php bin/console doctrine:migrations:migrate
  ```
- Generate datatest :
  ```bash
  php bin/console doctrine:fixtures:load
   ```
- Build files Webpack :
  ```bash
  yarn encore dev
   ```

- Start the server :
  ```bash
  Symfony server:start -d
  ```
- Connect with : camille@example.com / admin

❤️ Current technical stack : Symfony version 5.8.6/ Twig / PHP 8.3.2



## ------ THE PROJECT ------

Imagine TOMORROW, there is a GENERAL COLLAPSE. The goal of the website it to become THE SURVIVALISTS' WEBSITE 🚀


 <b>** CURRENT FEATURES **</b>

<i>Authentification;</i> The access to the platform is free; Each user has an account;

<i>Mon Jardin : Garden Dashboard;</i>
- A user can consult his dashboard with a summary of his activities by year. 
- A user can consult the global history of his activities
- A user can follow other users</p>

<i>Mon Carnet : Logbook;</i>  A user can consult his activities through a calendar; A user can input activities like below :
- his notes with picture or not
- his planting (seeding or planting)
- his harvests
- his expenses;

<i>Mon Profil; </i>A user can modify his personnel data


<b>** NEXT FEATURES **</b>

<i>Priorities :</i>
<p>🛠️ Migrate properly Symfony5 -> Symfony6</p> OK DONE
<p> ADD Garden information and display it in Mon Jardin (linked to a user) </p> OK DONE
<p>🛠️ Fix "Notify by email when a user create his account"</p>
<p>🎨 Improve UI/UX of the current website</p> OK but can be better

<i>Next developments :</i>

<p>🌍 Multilingue FR / EN</p>

<p>🎯 Access to the BARTER. The website's big feature.</p>

<p> Donner les prix des Consommables en temps réèl pour les calculs d'économie, par ex https://www.tridge.com/fr/</p>



## ------ TECHNICAL DOCUMENTATION ------

<b>** Tables **</b>

<p><b>User</b> = users</p>
<p><b>Friend</b> = users followed</p>

<p><b>Photo</b> (UserId ou PostId ou PanierId)</p>

<p><b>Flux</b> (userId) is :
    <b>Post </b>(FluxId)
    <b>Achat</b> (FluxId)
    <b>Panier</b> (FluxId)
  
<p><b>Panier</b> contain <b>Plants</b> (userId & PanierId) and <b>Récoltes </b>(userId & PanierId) </p>

<p><b>Commentaire</b> + <b>Like</b></p>

List tables : 
- <b>Consommable</b> : Nested Tree, 3 levels (used by entity Recolte and entity Plant)
- <b>Taille</b> et <b>ZoneAdministrative</b> (used by entity User)
- <b>State</b> (used by entity Plant)
- <b>Type</b> (used by entity Achat)
- <b>Méthode</b> (used by entity Recolte)




