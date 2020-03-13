import React from 'react'
import {StoredMail} from "../StoredMail";
import {Trash} from "../../icons/Trash";
import {deleteMail} from "../../http/api";

function onDeleteClicked(mail: StoredMail, didDelete: (mail: StoredMail) => any) {
    return function () {
        if (confirm('Are you sure?')) {
            deleteMail(mail.id).then(() => {
                didDelete(mail)
            }).catch(error => alert(error.message))
        }
    }
}

interface Props {
    mail: StoredMail,
    didDelete?: (mail: StoredMail) => any
}

export function DeleteButton({mail, didDelete}: Props) {
    return <button
        className="btn btn-outline-danger"
        onClick={onDeleteClicked(mail, didDelete || function () {})}
    >
        <Trash/>
        <span style={{verticalAlign: 'middle'}}>Delete</span>
    </button>
}
