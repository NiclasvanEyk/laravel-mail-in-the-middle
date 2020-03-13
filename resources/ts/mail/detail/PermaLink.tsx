import React from 'react'
import {ROUTES} from "../../config/api";
import {BoxArrowUpRight} from "../../icons/BoxArrowUpRight";
import {StoredMail} from "../StoredMail";

export function PermaLink({mail}: {mail: StoredMail}) {
    return <div className="btn-group">
        <a
            href={ROUTES.MAIL_DETAIL(mail)}
            target="_blank"
            rel="noopener noreferrer"
            className="btn btn-outline-dark"
        >
            <BoxArrowUpRight/>
            <span style={{verticalAlign: 'middle'}}>Permalink</span>
        </a>
    </div>
}
