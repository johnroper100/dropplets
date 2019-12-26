# Spaceship 🚀🪐

> My stellar Nuxt.js project

## Build Setup

``` bash
# install dependencies
$ yarn

# serve with hot reload at localhost:3000
$ yarn dev

# serve with firebase functions
$ yarn build:firebase
$ yarn start:firebase

# deploy project on Firebase Hosting
$ yarn build:firebase
$ yarn deploy

```

For detailed explanation on how things work, check out [Nuxt.js docs](https://nuxtjs.org).

Si `yarn build:firebase` met une erreur à la fin du process, le contournement que j'ai trouvé est :

- Aller dans `functions/package.json`
- Remplacer la ligne `"node": "10"` par `"node": ">=10"`
- Relancer `yarn build:firebase`
- Quand la commande est finie, remettre ligne sur `"node": "10"`
- Continuer le process

## Changer le nom du projet

- Ouvrir package.json
- Changer la ligne `"name"`

## Changer les configs Firebase 🔥 du projet

- Ouvrir `nuxt.config.js`
- Ctrl+f de `nuxt-fire`
- Remplacer les valeurs par celles du [projet Firebase](https://firebase.google.com/docs/web/setup)

## Trucs important

- `~` or `@` for [srcDir](https://nuxtjs.org/api/configuration-srcdir)
- `~~` or `@@` for [rootDir](https://nuxtjs.org/api/configuration-rootdir)

## Liens important

> Pour éviter de galérer, voici la liste des liens qui sont importants pour le projet.

### Librarie Nuxt Fire 🔥

- [Github](https://github.com/lupas/nuxt-fire)
- [Documentation](https://nuxtfire.netlify.com/)

### Deploy Nuxt on Firebase 🚀

- [Tuto](https://dev.to/kiritchoukc/deploy-nuxt-on-firebase-4ad8)

------

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
