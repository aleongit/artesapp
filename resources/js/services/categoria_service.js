import { http, httpFile } from "./http_service";

export function crear(data) {
    return http().post('/categories', data);
}

export function mostrar(pag) {
    return http().get('/categories?page=' + pag);
}

export function eliminar(id) {
    return http().delete(`/categories/${id}`);
}

//enviem per 'post', però en data s'indica mètode 'put'
export function update(id, data) {
    return http().post(`/categories/${id}`, data);
}
