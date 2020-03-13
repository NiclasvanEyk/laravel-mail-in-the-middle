import * as React from 'react'
import {StoredMail} from "../StoredMail";
import {Recipients} from "../meta/Recipients";
import classNames from 'classnames'
import {View} from "../../icons/View";

export interface Props {
    mail: StoredMail;
    onView: () => void;
    active: boolean;
}

export function MailListEntry({mail, onView, active}: Props) {
    return <li className={classNames({
        'list-group-item': true,
        'list-group-item-action': true,
        'mail-sidebar-entry': true,
        'overflow-hidden': true,
        'position-relative': true,
        active,
    })}>
        <div className="d-flex flex-row">
            <div
                className="flex-grow-1"
                style={{
                    overflow: 'hidden',
                    textOverflow: 'ellipsis',
                }}
            >
                <span style={{whiteSpace: 'nowrap'}} className="font-weight-bold">
                    {mail.subject}
                </span>

                    <br/>

                    <span style={{whiteSpace: 'nowrap'}}>
                    <Recipients addresses={mail.to} viewMode="compact"/>
                </span>
            </div>
            <button
                onClick={onView}
                style={{
                    position: "absolute",
                    right: 0,
                    top: 0,
                    bottom: 0,
                    fontWeight: 'bold',
                }}
                className={classNames({
                    'btn': true,
                    'btn-primary': active,
                    'btn-white': !active,
                    'bg-white': !active,
                    'view-mail': true,
                })}
            >
                <View />
                <span style={{verticalAlign: 'middle'}}>View</span>
            </button>
        </div>
    </li>
}

