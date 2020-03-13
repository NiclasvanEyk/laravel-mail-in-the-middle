import React, {useState, Fragment, ReactNode, useRef, useEffect} from 'react'
import {StoredMail} from "../StoredMail";
import {ROUTES} from "../../config/api";
import {Mail} from "../../icons/Mail";
import classNames from "classnames";
import {HasChildren} from "../../types/react";
import {ViewData} from "../../icons/ViewData";
import {TemplateSource} from "../../icons/TemplateSource";

export type Tab = 'content' | 'source' | 'view-data'
const LABELS = {
    content: 'Content',
    source: 'Source',
    'view-data': 'View Data',
}

function Pane({display, title, children, setTab, nextTab}: {
    display: boolean,
    title: string|ReactNode,
    setTab: () => void,
    nextTab: string,
} & HasChildren) {
    return <div className={classNames({
        'd-flex': display,
        'card': true,
        'flex-column': true,
        'flex-grow-1': true,
        'd-none': !display
    })}>
        <div className="card-header d-flex flex-row">
            <h2 className="mb-0 flex-grow-1">
                {title}
            </h2>

            <button className="btn btn-outline-dark" onClick={setTab}>
                Switch to {nextTab}
            </button>
        </div>
        <div className="card-body p-0 position-relative flex-grow-1 mh-100">
            <div className="position-absolute h-100 w-100 overflow-auto">
                {children}
            </div>
        </div>
    </div>
}

export function ContentPreview({mail}: {mail: StoredMail}) {
    const [tab, setTab] = useState<Tab>('content')
    const varDump = useRef<HTMLDivElement>(null)
    // We have to memorize the id of the last dumped element, because
    // otherwise another dropdown indicator will get added to the
    // var-dumpeach time the refresh-button is pressed!
    const lastDumped = useRef('');

    useEffect(() => {
        if (varDump.current?.firstChild) {
            const id = (varDump.current.firstChild as HTMLElement).getAttribute('id');

            if (id !== null && id !== lastDumped.current) {
                lastDumped.current = id;

                ((window as any).Sfdump as any)(id)
            }
        }
    }, [varDump, mail])

    return <Fragment>
        <Pane
            display={tab === 'content'}
            setTab={() => setTab('source')}
            nextTab={LABELS['source']}
            title={<Fragment>
                <Mail/>
                <span className="ml-2" style={{verticalAlign: 'middle'}}>
                    {LABELS['content']}
                </span>
            </Fragment>}
        >
            <iframe
                id="mail-preview"
                height="100%"
                width="100%"
                className="d-block h-100 w-100 border-0 position-absolute"
                src={ROUTES.MAIL_CONTENT(mail)}
            ></iframe>
        </Pane>

        <Pane
            display={tab === 'source'}
            setTab={() => setTab('view-data')}
            nextTab={LABELS['view-data']}
            title={<Fragment>
                <TemplateSource/>
                <span className="ml-2" style={{verticalAlign: 'middle'}}>
                    {LABELS['source']}
                </span>
            </Fragment>}
        >
            <pre className="mail-src"><code>{ mail.src }</code></pre>
        </Pane>

        <Pane
            display={tab === 'view-data'}
            setTab={() => setTab('content')}
            nextTab={LABELS['content']}
            title={<Fragment>
                <ViewData />
                <span className="ml-2" style={{verticalAlign: 'middle'}}>
                    {LABELS['view-data']}
                </span>
            </Fragment>}
        >

            <div ref={varDump} className="var-dump" dangerouslySetInnerHTML={{__html: mail.viewData}}></div>
        </Pane>
    </Fragment>
}
