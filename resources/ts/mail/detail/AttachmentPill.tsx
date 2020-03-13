import React from 'react'
import {FileTypeSpecificIcon} from "../../icons/files/FileTypeSpecificIcon";
import {Attachment, StoredMail} from "../StoredMail";
import {attachmentLink} from "../../http/api";

export function AttachmentPill({attachment, mail}: {
    attachment: Attachment,
    mail: StoredMail
}) {
    return  <a
        download
        className="text-reset file-download-pill"
        style={{
            verticalAlign: 'middle',
            wordWrap: 'anywhere',
            textDecoration: 'none',
        } as any}
        href={attachmentLink(mail, attachment.id)}
    >
        <span className="mr-2">
            <FileTypeSpecificIcon
                mimeType={attachment.mime_type}
                name={attachment.name}
            />
        </span>
        <span>
          {attachment.name}
        </span>
    </a>
}
