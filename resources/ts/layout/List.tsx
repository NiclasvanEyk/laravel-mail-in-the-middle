import * as React from "react";

export interface ListItemComponent<T> {
    renderItem: (data: T, i: number) => React.ReactElement
    data: T[]
}

export function List<T>({renderItem, data}: ListItemComponent<T>) {
    return <ul className="list-group list-group-flush">
        {data.map(renderItem)}
    </ul>;
}

List.displayName = 'List'
