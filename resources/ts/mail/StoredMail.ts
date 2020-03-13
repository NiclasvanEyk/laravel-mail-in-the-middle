import {Model} from "../eloquent/Model";

export interface StoredMail extends Model {
    subject: string;
    from: string;
    to: string;
    cc: string|null;
    bcc: string|null;
    priority: string;
    src: string;
    rendered: string;
    viewData: string;
}

export type WithAttachments<T> = T & {
    attachments: Attachment[];
}

export interface Attachment {
    id: number;
    mime_type: string;
    mail_id: string|number;
    created_at: string;
    name: string;
}
