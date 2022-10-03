import { http, httpFile } from "./http_service";

export function crear(data) {
    return http().post('/clients', data);
}

export function getTipus() {
    return http().get('/get-tipus');
}

export function mostra(pag) {
    return http().get('/clients?page=' + pag);
}

export function eliminar(id) {
    return http().delete(`/clients/${id}`);
}

//enviem per 'post', però en data s'indica mètode 'put'
export function update(id, data) {
    return http().post(`/clients/${id}`, data);
}
