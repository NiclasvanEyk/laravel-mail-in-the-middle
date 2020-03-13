import {BASE_URL} from "../config/api";
import {PaginationResult} from "../types/laravel";
import {StoredMail, WithAttachments} from "../mail/StoredMail";

export const API_BASE_URL = `${BASE_URL}/api`;

export function route(path = '') {
    return API_BASE_URL + path;
}

export function attachmentLink(mail: StoredMail, attachmentId: number): string {
    return `${API_BASE_URL}/mails/${mail.id}/attachments/${attachmentId}`
}

export interface ApiError {
    error: number;
}

export interface NotFound extends ApiError {
    error: 404;
}

export function wasNotFound(response: any): response is NotFound {
    return response && response.error && response.error === 404;
}

export function getMails(page = 1): Promise<PaginationResult<WithAttachments<StoredMail>>> {
    return fetch(route(`/mails?page=${page}`))
        .then(response => response.json());
}

export function getMail(id: number|string) {
    return fetch(route(`/mails/${id}`))
        .then(response => {
            if (response.status === 404) {
                return new Promise(resolve => {
                    resolve({
                        error: 404
                    })
                })
            }

            return response.json() as Promise<WithAttachments<StoredMail>>
        }) as Promise<WithAttachments<StoredMail> | NotFound>
}

export function deleteMail(id: number|string) {
    return fetch(route(`/mails/${encodeURIComponent(id)}`), {
        method: 'DELETE',
    });
}
