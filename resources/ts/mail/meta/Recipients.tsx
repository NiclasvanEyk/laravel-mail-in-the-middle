import React from 'react'
import {Address, ViewMode} from "./Address";

export interface Props {
    addresses: string|string[]|null|undefined;
    viewMode?: ViewMode;
}

export function Recipients({
   addresses,
   viewMode = 'full'
}: Props) {
    if (typeof addresses === 'string') {
        addresses = addresses.split(', ')
    }

    if (!Array.isArray(addresses) || addresses.length <= 0) {
        return null
    }

    if (addresses.length === 1) {
        return <Address address={addresses[0]} viewMode={viewMode}/>
    }

    const lastIndex = addresses.length - 1

    return <>
        {addresses.map((address, i) => <React.Fragment key={i}>
            <Address address={address} viewMode={viewMode} />{ i !== lastIndex ? ', ' : ''}
        </React.Fragment>)}
    </>
}
