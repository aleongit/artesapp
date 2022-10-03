import { http, httpFile } from "./http_service";

export function crear(data) {
    return http().post('/comandes', data);
}

export function getEstats() {
    return http().get('/get-estats');
}

export function getClients() {
    return http().get('/get-clients');
}

export function getCategoriesProductes() {
    return http().get('/get-categories-productes');
}

export function mostra(pag) {
    return http().get('/comandes?page=' + pag);
}

export function eliminar(id) {
    return http().delete(`/comandes/${id}`);
}

//enviem per 'post', però en data s'indica mètode 'put'
export function update(id, data) {
    return http().post(`/comandes/${id}`, data);
}

//per només canvi estat
export function updateEstat(id, data) {
    return http().post(`/set-estat/${id}`, data);
}

