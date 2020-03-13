import React from 'react'
import {MailDetail} from "../mail/detail/MailDetail";
import {useEffect, useState} from "react";
import {StoredMail, WithAttachments} from "../mail/StoredMail";
import {getMail, wasNotFound} from "../http/api";
import {Empty} from "../icons/Empty";

export function Detail() {
    const [mail, setMail] = useState<WithAttachments<StoredMail>|null>(null)
    const [notFound, setNotFound] = useState(false)

    useEffect(() => {
        const pieces = window.location.pathname.split('/')
        const id = pieces.pop() as any
        getMail(id).then(response => {
            if (wasNotFound(response)) {
                setNotFound(true)
                return
            }

            setMail(response)
        })
    }, [])

    return <div className="container">
        {mail
            ? <MailDetail mail={mail}/>
            : notFound
                ? <div className="d-flex flex-column text-center justify-content-center align-items-center vh-100">
                    <div style={{maxWidth: '400px'}}>
                        <Empty/>

                        <p className="text-muted mt-5">Mail not found.</p>
                    </div>
                </div>
                : <span>Loading...</span>
        }
    </div>
}
