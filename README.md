# Spaceship 🚀

<p align="center">
  <img src="https://repository-images.githubusercontent.com/230308423/51f50100-6386-11ea-873f-0b12486b8383"/>
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

## Build Setup

```bash
# install dependencies
$ yarn install

# serve with hot reload at localhost:3000
$ yarn dev

# build for production and launch server
$ yarn build
$ yarn start

# generate static project
$ yarn generate
```

For detailed explanation on how things work, check out [Nuxt.js docs](https://nuxtjs.org).