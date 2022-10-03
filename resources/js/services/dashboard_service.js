import { http, httpFile } from "./http_service";

export function getComptadors() {
    return http().get('/get-comptadors');
}


