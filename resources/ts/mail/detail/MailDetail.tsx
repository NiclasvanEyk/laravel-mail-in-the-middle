import React from 'react'
import {formatRelative} from 'date-fns'
import { enGB } from 'date-fns/locale'
import {StoredMail, WithAttachments} from "../StoredMail";
import {ContentPreview} from "./ContentPreview";
import {Recipients} from "../meta/Recipients";
import {Address} from "../meta/Address";
import {PermaLink} from "./PermaLink";
import {Info} from "../../icons/Info";
import {DeleteButton} from "./DeleteButton";
import {AttachmentPill} from "./AttachmentPill";

interface Props {
    mail: WithAttachments<StoredMail>,
    mailDeleted?: (mail: StoredMail) => any
}

export function MailDetail({mail, mailDeleted}: Props) {
    return <div className="pb-3 d-flex flex-column" style={{height: '100vh'}}>
        <div className="card" id="meta-data" style={{marginTop: '10px'}}>
            <div className="card-header d-flex flex-row">
                <h2 className="mb-0 flex-grow-1"
                    style={{cursor: 'pointer'}}
                    data-toggle="collapse"
                    data-target="#meta-data-list" aria-controls="meta-data-list"
                    aria-expanded="true"
                >
                    <Info/>
                    <span className="ml-2" style={{verticalAlign: 'middle'}}>Metadata</span>
                </h2>

                <div>
                    <div className="btn-group">
                        <DeleteButton mail={mail} didDelete={mailDeleted}/>
                        <PermaLink mail={mail}/>
                    </div>
                </div>
            </div>
            <ul className="list-group list-group-flush collapse show"
                id="meta-data-list" data-parent="#meta-data"
                style={{maxHeight: '30vh', overflowY: 'auto'}}
            >
                <li className="list-group-item">
                    Subject: {mail.subject}
                </li>
                <li className="list-group-item">
                    Sent: <time title={mail.created_at}>
                        {formatRelative(new Date(mail.created_at), new Date(), {
                            // I just prefer the 24-hour format
                            locale: enGB,
                        })}
                    </time>
                </li>
                <li className="list-group-item">
                    From: <Address address={mail.from}/>
                </li>
                <li className="list-group-item">
                    To: <Recipients addresses={mail.to}/>
                </li>
                {
                    mail.cc ? <li className="list-group-item">
                        CC: <Recipients addresses={mail.cc}/>
                    </li> : null
                }
                {
                    mail.bcc ? <li className="list-group-item">
                        BCC: <Recipients addresses={mail.bcc}/>
                    </li> : null
                }
                {
                    Array.isArray(mail.attachments) ? <li className="list-group-item d-flex flex-row flex-wrap">
                        <div className="d-flex align-items-center mr-1">
                            Attachments:
                        </div>
                        {
                            mail.attachments.map((attachment, i) =>
                                <div key={i} className="mx-1 my-1" title={`download ${attachment.name}`}>
                                    <AttachmentPill
                                        mail={mail}
                                        attachment={attachment}
                                    />
                                </div>
                            )
                        }
                    </li> : null
                }
            </ul>
        </div>

        <div className="mt-5 d-flex flex-column flex-grow-1">
            <ContentPreview mail={mail}/>
        </div>
    </div>
}
