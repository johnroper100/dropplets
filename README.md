# fire-nuxt-vince

> My stellar Nuxt.js project

## Build Setup

``` bash
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

------

## Création d'un nouveau projet

> Cette section permet d'aider à la création de nouveaux projets dans l'avenir.

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
