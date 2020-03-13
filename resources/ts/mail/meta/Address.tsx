import React from 'react'

export type ViewMode = 'full' | 'compact';

export interface Props {
    address: string;
    viewMode?: ViewMode;
}

export function Address({
    address,
    viewMode = 'full',
}: Props) {
    const all = address.trim()
    const addressStart = all.indexOf('<')
    const addressEnd = all.indexOf('>')
    const addressInBrackets = address.trim().slice(addressStart + 1, addressEnd).trim()
    const name = all.slice(0, addressStart).trim()

    if (addressInBrackets && name) {
        if (viewMode === 'compact') {
            return <a
                href={`mailto:${addressInBrackets}`}
                title={addressInBrackets}
                className="mailto"
            >
                {name}
            </a>
        }

        return <>
            <span>{name}</span>&nbsp;&lt;<a className="mailto" href={`mailto:${addressInBrackets}`}>{addressInBrackets}</a>&gt;
        </>
    } else {
        return <a className="mailto" href={`mailto:${all}`}>
            {addressInBrackets ? addressInBrackets : all}
        </a>
    }
}
