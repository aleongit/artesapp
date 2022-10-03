import { http, httpFile } from "./http_service";

export function crear(data) {
    return http().post('/productes', data);
}

export function getCategories() {
    return http().get('/get-categories');
}

export function mostra(pag) {
    return http().get('/productes?page=' + pag);
}

//http://localhost:8000/api/productes-categoria/2?page=1
export function mostraAmbCategoria(pag, categoria) {
    return http().get(`/productes-categoria/${categoria}?page=' + ${pag}`);
}

export function eliminar(id) {
    return http().delete(`/productes/${id}`);
}

//enviem per 'post', però en data s'indica mètode 'put'
export function update(id, data) {
    return http().post(`/productes/${id}`, data);
}