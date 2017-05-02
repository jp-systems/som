// Custom file to access firebase database used to store/get module chats and user avatars
import firebase from 'firebase'

firebase.initializeApp({
  apiKey: 'AIzaSyBwzLrePM_AEKRsknDBz3JJPQpHBVKJmqc',
  authDomain: 'jp-som.firebaseapp.com',
  databaseURL: 'https://jp-som.firebaseio.com',
  projectId: 'jp-som',
  storageBucket: 'jp-som.appspot.com',
  messagingSenderId: '960635004247'
})

export default {
  postMessage (moduleID, user, message) {
    firebase.database().ref('chat/module/' + moduleID).push({
      username: user.username,
      message,
      timestamp: +(new Date())
    })
  },
  watchMessages (moduleID, callback) {
    firebase.database().ref('chat/module/' + moduleID).on('value', callback)
  },
  unwatchMessages (moduleID) {
    firebase.database().ref('chat/module/' + moduleID).off('value')
  },
  saveUserAvatar (userID, avatar) {
    let storage = firebase.storage().ref()
    let ref = storage.child('avatars/' + userID)
    return ref.put(avatar)
  },
  getUserAvatar (userID) {
    return firebase.storage().ref('avatars/' + userID).getDownloadURL()
  }
}
