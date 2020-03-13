import React from 'react'
import {ChevronLeft} from "../icons/ChevronLeft";
import {Spacer} from "../layout/Spacer";
import {ChevronRight} from "../icons/ChevronRight";
import {PaginationResult} from "../types/laravel";

export function Pagination<T = any>({setPage, meta}: {setPage: (page: number) => void, meta: PaginationResult<T>}) {
    return <div className="d-flex flex-row">
        {
            meta.prev_page_url ? <button
                className="btn p-3"
                style={{fontSize: '2em'}}
                onClick={() => setPage(Math.max(meta.current_page - 1, 1))}
            >
                <ChevronLeft/>
            </button> : null
        }

        <Spacer/>

        {
            meta.next_page_url ? <button
                className="btn p-3"
                style={{fontSize: '2em'}}
                onClick={() => setPage(meta.current_page + 1)}
            >
                <ChevronRight/>
            </button> : null
        }
    </div>
}
