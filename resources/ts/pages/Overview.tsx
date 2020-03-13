import React, {useEffect, useState} from 'react'
import {Container, Detail, Master} from "../layout/MasterDetail";
import {List} from "../layout/List";
import {MailListEntry} from "../mail/list/MailListEntry";
import {MailDetail} from "../mail/detail/MailDetail";
import {getMails} from "../http/api";
import {StoredMail, WithAttachments} from "../mail/StoredMail";
import {Inbox} from '../icons/Inbox'
import {Spacer} from "../layout/Spacer";
import {PaginationResult} from "../types/laravel";
import {Pagination} from "../pagination/Pagination";
import {RefreshButton} from "../mail/list/RefreshButton";
import {EmptyMails} from "../mail/EmptyState";

export function Overview() {
    const [mails, setMails] = useState<PaginationResult<WithAttachments<StoredMail>>|null>(null)
    const [page, setPage] = useState(1);
    const [activeIndex, setActiveIndex] = useState(0)
    const [loading, setLoading] = useState(false);

    function loadMails(page: number, options = {
        resetActiveIndex: true,
    }) {
        setLoading(true)

        return getMails(page).then(response => {
            if (options.resetActiveIndex) {
                setActiveIndex(0)
            } else {
                setActiveIndex(Math.min(response.data.length - 1, activeIndex))
            }

            setMails(response)
        }).finally(() => {
            setLoading(false)
        })
    }

    useEffect(() => {
        loadMails(page)
    }, [page])

    return <Container>
        <Master>
            <div className="list-group-item border-right-0 d-flex flex-row align-items-center">
                <h1 className="m-0">
                    <Inbox />
                    <span style={{
                        marginLeft: '15px',
                        verticalAlign: 'middle',
                        fontWeight: 'bold',
                    }}>
                        Mails
                    </span>
                </h1>

                <Spacer/>

                <RefreshButton onClick={() => loadMails(page, {
                    resetActiveIndex: false
                })}/>
            </div>
            <List data={mails ? mails.data : []} renderItem={((data, i) =>
                <MailListEntry
                    key={i}
                    active={activeIndex === i}
                    mail={data}
                    onView={() => setActiveIndex(i)}
                />
            )}/>

            <Spacer/>

            {
                mails
                    ? <Pagination setPage={setPage} meta={mails}/>
                    : null
            }
        </Master>

        <Detail>
            {
                mails && mails.data.length > 0
                    ? <MailDetail
                        mail={mails.data[activeIndex]}
                        mailDeleted={() => loadMails(page)}
                      />
                    : <div className="d-flex align-items-center justify-content-center w-100 vh-100">
                        {
                            loading
                                ? <span>Loading...</span>
                                : <EmptyMails />
                        }
                    </div>
            }
        </Detail>
    </Container>
}
