export default ({ app }, inject) => {
    inject('storeAuthCheck', () => {return app.$auth.loggedIn})
}