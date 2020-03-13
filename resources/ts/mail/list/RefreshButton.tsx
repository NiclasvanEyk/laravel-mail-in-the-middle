import React, {useState} from 'react'
import {Refresh} from "../../icons/Refresh";
import classNames from "classnames";

export function RefreshButton({onClick}: {onClick: () => Promise<any>}) {
    const [refreshing, setRefreshing] = useState(false)

    function spinWhileLoading() {
        setRefreshing(true);
        onClick().finally(() => {
            setRefreshing(false)
        })
    }

    return <button className="btn" onClick={() => spinWhileLoading()}>
        <div className={classNames({refreshing})}>
            <Refresh/>
        </div>
    </button>;
}
