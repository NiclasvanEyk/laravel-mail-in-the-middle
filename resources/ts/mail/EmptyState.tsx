import React from 'react'
import {Empty} from "../icons/Empty";

export function EmptyMails() {
    return <div
        className="d-flex flex-column text-center align-items-center"
        style={{maxHeight: '400px'}}>

        <p className="text-muted mb-5">No mails found.</p>

        <Empty/>

        <p className="mb-0 mt-5 text-muted" style={{
            maxWidth: '300px',
        }}>
            Send a <code>Mailable</code> using the
            &nbsp;<code style={{whiteSpace: 'nowrap'}}>mail-in-the-middle</code>
            -driver and it will show up here!
        </p>
    </div>
}
