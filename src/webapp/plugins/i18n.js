import { UpdateLocaleMutation } from '@/graphql/auth/update_locale.mutation'

export default function ({ app, store, error }) {
  // Set the 'Accept-Language' header default value.
  app.$graphql.setHeader('Accept-Language', app.i18n.locale)

  store.subscribe(async (mutation) => {
    if (mutation.type === 'i18n/setLocale') {
      // Update the 'Accept-Language' header so that validation
      // errors are translated to the correct locale.
      app.$graphql.setHeader('Accept-Language', mutation.payload)

      // If the user is authenticated, update his locale.
      if (store.getters['auth/isAuthenticated']) {
        try {
          await app.$graphql.request(UpdateLocaleMutation, {
            locale: mutation.payload.toUpperCase(),
          })

          store.commit('auth/setUserLocale', mutation.payload)
        } catch (e) {
          error(e)
        }
      }
    }
  })
}
