import { http, httpFile } from "./http_service";

//NO VA jsonwebtoken, errors
//import jwt from "jsonwebtoken";

//jsontokens
import { TokenSigner, decodeToken } from 'jsontokens';

//store
import store from '../store';

//laravel auth
export function registreUser(user) {
    return http().post('/auth/register', user);
}

//laravel retorna dades usuari i dades token accés
//si resposta 200, guarda totes aquestes dades (objecte) a localstorage
export function loginUser(user) {
    return http().post('/auth/login', user)
    //un cop fet login, configura token
    .then(response => {
        if (response.status === 200) {
            setToken(response.data)
        }
        return response.data
    });
}

//guardar token a local storage amb l'objecte encriptat 
//és a dir, generem un altre token, que guardem a localstorage, 
//el qual guarda l'altre token accés laravel, l'objecte usuari i altres dades
function setToken(user) {
    //localStorage.setItem('laravel_vue_token', JSON.stringify(user));
    
    const rawPrivateKey = 'larararalalalavueeeeeeeeeee';
    //const tokenPayload = {"iat": 1440713414.85}
    
    //dades usuari
    const tokenPayload = user;
    
    //signar amb clau privada i encriptar
    var token = new TokenSigner('ES256K', rawPrivateKey).sign(tokenPayload)
    
    //guardar a local storage
    localStorage.setItem('laravel_vue_token', token);

    //crida a l'acció del store vuex per guardar dades usuari
    store.dispatch('authenticate', user.user);
}

//saber si user logat
//obtenir el token i retornar si no és null
export function isLoggedIn() {
    const token = localStorage.getItem('laravel_vue_token');
    console.log('isLoggedIn: ' + token != null);
    return token != null;
}

//logout
export function logoutUser() {
    return http().get('/auth/logout')
}

export function delToken() {
    localStorage.removeItem('laravel_vue_token');
}

//recuperem token d'storage, desencriptem i retornem user.access_token
//(el token accés de laravel)
//i per afegir a capçaleres en servei http
//Authorization: 'Bearer' + token
export function getAccessToken() {
    const token = localStorage.getItem('laravel_vue_token');
    if (!token) {
        return null;
    }
    const tokenData = decodeToken(token);
    //console.log('* TOKENDATA *' + JSON.stringify(tokenData));
    //console.log(tokenData.payload.access_token);
    return tokenData.payload.access_token;
}

export function getUserRole() {
    const token = localStorage.getItem('laravel_vue_token');
    if (!token) {
        return null;
    }
    const tokenData = decodeToken(token);
    return tokenData.payload.user.role;
}

//get profile
export function getProfile() {
    return http().get('/auth/profile');
}

export function updateProfile(data) {
    return http().post('/auth/update-profile', data);
}

export function updatePassword(data) {
    return http().post('/auth/update-password', data);
}