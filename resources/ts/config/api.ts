import {StoredMail} from "../mail/StoredMail";

export const BASE_URL = (window as any).MAIL_IN_THE_MIDDLE_BASE_URL
    || 'base-route-not-found!'

export function route(path = '') {
    return BASE_URL + path;
}

export const ROUTES = {
    OVERVIEW: route(),
    MAIL_DETAIL: (mail: StoredMail) => route(`/${encodeURIComponent(mail.id)}`),
    MAIL_CONTENT: (mail: StoredMail) => route(`/${encodeURIComponent(mail.id)}/content`),
}
