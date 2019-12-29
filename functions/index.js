const admin = require('firebase-admin')
const JWTDecode = require('jwt-decode')
const functions = require('firebase-functions')
const { Nuxt } = require('nuxt')
const express = require('express')
// const config = require('../nuxt.config.js')

const app = express()

const config = {
  dev: false
}

const nuxt = new Nuxt(config)

let isReady = false
const readyPromise = nuxt
  .ready()
  .then(() => {
    isReady = true
    return null
  })
  .catch(() => {
    process.exit(1)
  })

async function handleRequest(req, res) {
  if (!isReady) {
    await readyPromise
  }
  res.set('Cache-Control', 'public, max-age=1, s-maxage=1')

  // On check si la requete a l'authorization header qui est envoyé par le service worker firebase-auth-sw.js
  if (req.headers.authorization) {
    // On initialise Firebase si c'est pas déjà fait
    if (!admin.apps.length) {
      const options = {
        config: {
          apiKey: 'AIzaSyC3qpcRaVJVT63YMfIMgNJKRsmtPmEB6VM',
          authDomain: 'bento-vince.firebaseapp.com',
          databaseURL: 'https:\u002F\u002Fbento-vince.firebaseio.com',
          projectId: 'bento-vince',
          storageBucket: 'bento-vince.appspot.com',
          messagingSenderId: '419042376123',
          appId: '1:419042376123:web:f2a4223fcbff6f078b6c9e',
          measurementId: '\u003CmeasurementId\u003E'
        },
        services: {
          auth: {
            initialize: {
              onSuccessAction: 'handleSuccessfulAuthentication',
              ssr: true
            }
          }
        }
      }
      admin.initializeApp(options.config)
    }

    // Parse the injected ID token from the request header.
    const authorizationHeader = req.headers.authorization || ''
    const components = authorizationHeader.split(' ')
    const idToken = components.length > 1 ? components[1] : ''

    // Get authUser object from JWT
    const decodedAuthUser = JWTDecode(idToken)
    const authUser = {
      // Reproduce attributes of "official" authUser object
      uid: decodedAuthUser.user_id,
      email: decodedAuthUser.email,
      emailVerified: decodedAuthUser.email_verified
    }

    // Try to verify the id token:
    try {
      const decodedToken = await admin.auth().verifyIdToken(idToken)
      const uid = decodedToken.uid
      if (uid) {
        // If UID can be retrieved, user is officially verified.
        // Set authUser object to res so it can be accesses in nuxtServerInit with in `ctx.res`
        res.verifiedFireAuthUser = authUser
      }
    } catch (e) {
      console.error(e)
    }
  }

  // ATTENTION : en appellant nuxt.render, on n'execute par les serverMiddleware !!!
  await nuxt.render(req, res)
}

app.get('*', handleRequest)
app.use(handleRequest)
exports.nuxtssr = functions.https.onRequest(app)
