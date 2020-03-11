# Spaceship 🚀🪐

<p align="center">
  <img src="https://firebasestorage.googleapis.com/v0/b/bento-vince.appspot.com/o/spaceshipGithub.png?alt=media&token=eaa4013a-581e-4d68-a6a3-3b0bf66849a0"/>
</p>

<p align="center">
  <a href="https://gitmoji.carloscuesta.me/"><img src="https://img.shields.io/badge/gitmoji-%20😜%20😍-FFDD67.svg?style=flat-square"/></a>
</p>

> Template repository for quickly creating new universal web app (SSR) with VueJs, Nuxt, and Firebase, and deploying it on Firebase Hosting within seconds 🚀

## ❤ This is made for you

These libraries and tools are already setup:

- 🤘 Vue
- ☄ Nuxt
- 📱 PWA
- 👤 Firebase Auth (and server side verification)
- 🔥 [Nuxt-Fire](https://github.com/lupas/nuxt-fire) : all Firebase tools in Nuxt
- 🏠 Firebase Hosting deployment
- 🌍 Google App Engine deployment
- 💄 Prettier : code formatting rules
- 🚨 Eslint : control code quality
- ✅ Jest (testing)

## 🚀 Get started

### 🍺 Let's start

``` bash
# clone Spaceship 🚀🪐 repository
$ git clone https://github.com/EBfVince/Spaceship.git MyAwesomeProject
$ cd MyAwesomeProject

# install dependencies
$ yarn
```

### 👨‍🔧 Configuring the project

- Create a Firebase project. For more informations, [see here](https://firebase.google.com/).
- Open `package.json` file. Replace the `name` with your project name.
- Open `nuxt.config.js` file. On the top of the file, replace the `firebaseConfig` values by yours.
- That's it ! 🎉🍻

### 💻 Serve locally

``` bash
# serve with hot reload at localhost:3000
$ yarn dev
```

### 🔥 Serve locally with Firebase

> Spaceship 🚀🪐 is using NodeJS 10 in order to run the project with Firebase functions. To make it work properly, you need to install and use NodeJS 10. See [NVM](https://github.com/coreybutler/nvm-windows) for using multiple versions of Node on the same computer.

``` bash
# serve with firebase functions at localhost:5000
$ yarn build:firebase
$ yarn start:firebase
```

## 🚀 Easy Deploy

### 🔥 Deploy on Firebase

``` bash
# deploy on Firebase functions and hosting
$ yarn build:firebase
$ yarn deploy
```

### 👩‍🚀 Deploy on Google App Engine

The `[project-id]` is the project ID of your Google Cloud project.

``` bash
# deploy on Firebase functions and hosting
$ yarn build
$ gcloud app deploy app.yaml --project [project-id]
```

Made with ❤ by EBfStudio and Vince

-----

## Important stuff

- `~` or `@` for [srcDir](https://nuxtjs.org/api/configuration-srcdir)
- `~~` or `@@` for [rootDir](https://nuxtjs.org/api/configuration-rootdir)

## Firebase Authentication

Domain names need to be verified by Firebase Auth.
See here : <https://console.firebase.google.com/u/0/project/[project-id]/authentication/providers>

## Important links

> Here are some pages you should check to avoid struggling with your project.

### Nuxt Fire 🔥

- [Github](https://github.com/lupas/nuxt-fire)
- [Documentation](https://nuxtfire.netlify.com/)

### Deploy Nuxt on Firebase 🚀

- [Tutorial](https://dev.to/kiritchoukc/deploy-nuxt-on-firebase-4ad8)

-----

## Création d'un nouveau projet

> Cette section permet d'aider à la création de nouveaux projets dans l'avenir. Ca n'a rien à voir avec ce projet en particulier.

### Installation de Firebase

Sur un nouveau projet, si on installe Firebase avec `yarn` via `yarn add firebase` et qu'on tente de lancer le projet, on va se rendre compte qu'il ne compile plus. (Pourtant ça marche correctement avec `npm`)

Pour installer firebase correctement voici la méthode :

``` bash
# install firebase
$ yarn add firebase

# install missing dependencies
$ yarn add -D core-js@2 @babel/runtime-corejs2
```

Et là, ça devrait compiler normalement.

---

### Nom de domaine personnalisé avec **Google Signin**

Lorsque l'on souhaite ajouter un domaine personalisé à son projet il faut savoir plusieurs chose.

Il y a 2 cas : 
- On héberge le site sur Firebase
- On héberge le site sur un serveur externe (comme Google App Engine)

***Sur Firebase***

Dans ce cas il n'y a pas grand chose à faire.
- On se rend sur https://console.firebase.google.com/u/0/project/[project-id]/hosting/main
- On ajoute son nom de domaine en suivant les instructions
- On attend quelques heures le temps qu'ils ajoutent le certificat SSL (pour https) à notre site
- Voilà 🎉

***Sur un serveur externe***

Là, ça se complique car lorsqu'on utilise Google Authentication, lorsqu'on essaie de se connecter avec Google, Google (Firebase en réalité) essaie de stocker des cookies de connexion sur notre site. Comme le domaine de Firebase (project.firebaseapp.com) n'est pas le même que notre domaine custom, cela ne fonctionnera pas si le client a activé l'option dans son navitateur **"Bloquer les cookies tiers"**.

Pour faire fonctionner notre domaine perso, voici la méthode pour Google App Engine :

> Notre nom de domaine custom sera pour l'exemple *antoine.be* 

**Dans un premier temps on va changer le domaine sur App Engine**
- Se rendre sur https://console.cloud.google.com/appengine/settings/domains?project=[gcp-project-id]
- On ajoute son domaine custom *antoine.be*

**Maintenant, on va s'occuper de Firebase, pour lui indiquer l'adresse qu'il utilisera pour connecter l'utilisateur**
- On se rend sur https://console.firebase.google.com/u/0/project/[firebase-project-id]/hosting/main
- On ajoute un sous domaine de son domaine custom comme par exemple *auth.antoine.be*

**Pour finir, quelques modifs dans le projet**
- On se rend dans notre projet Javascript au niveau du fichier de config de Firebase (dans Spaceship -> nuxt.config.js)
- On remplace la valeur de `authDomain` qui doit être `'[firebase-project-id].firebaseapp.com'` par le sous domaine qu'on a ajouté dans Firebase soit `'auth.antoine.be'`

On attend quelques heures le temps que les CDN se mettent en place et voilà 🎉
