//import { createApp } from 'vue'
import { createStore } from 'vuex'

//servei auth
import * as auth from './services/auth_service';

//store
//https://vueschool.io/articles/vuejs-tutorials/what-is-a-store-in-vue-js/

//mutacions i accions
//https://elabismodenull.wordpress.com/2017/06/05/vuejs-las-mutaciones-y-acciones-en-vuex/

// Create a new store instance.
const store = createStore({
  state: {
    isLoggedIn: null,
    apiURL: 'http://localhost:8000/api',
    serverPath: 'http://localhost:8000',
    profile: {},
    test: 'test store',
  },
  //Les mutacions són funcions que s'encarreguen de canviar el valor del nostre estat, 
  //ja que no es pot canviar les propietats de l'estat directament
  //funcionen igual que un event, i es passa l'state
  //són síncrones, no usar funcions asíncrones
  //poden ser cridades pels components
  //payload = càrrega útil = dades enviades
  mutations: {
    authenticate(state, payload) {
      //comprovem si token a localstore
      state.isLoggedIn = auth.isLoggedIn();
      //si token
      if (state.isLoggedIn) {
        //agafem dades profile
        state.profile = payload;
      } else {
        state.profile = {};
      }
      console.log('mutation!');
      console.log(state.profile);
    }
  },
  //Las acciones funcionen igual que les mutacions. 
  //Però no muten l'estat – això ho deleguen a les mutacions – commit mutació
  //es permeten totes les operacions asíncrones que necessitem.
  //context es una estància del propi store
  actions: {
    authenticate(context, payload) {
      //crida a la mutació
      context.commit('authenticate', payload)
      console.log('ACTION!!!!');
    }
  },

})

export default store;

//const app = createApp({ /* your root component */ })
// Install the store instance as a plugin
//app.use(store)