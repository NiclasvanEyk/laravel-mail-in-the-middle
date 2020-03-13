import React from 'react'
import {ImageFile} from "./ImageFile";
import {VideoFile} from "./VideoFile";
import {UnknownFile} from "./UnknownFile";
import {TextFile} from "./TextFile";
import {CodeFile} from "./CodeFile";
import {DocumentFile} from "./DocumentFile";
import {SpreadsheetFile} from "./SpreadsheetFile";
import {CalendarFile} from "./CalendarFile";
import {MusicFile} from "./MusicFile";
import {ArchiveFile} from "./ArchiveFile";
import {BookFile} from "./BookFile";

const codeFileExtensions = [
    'html', 'css', 'js', 'ts', 'vue', 'hbs', 'rb', 'java', 'cs', 'php', 'swift',
    'go', 'rs', 'c', 'cpp', 'h', 'kt', 'sql', 'py', 'sh', 'ps', 'perl', 'r',
    'json', 'xml',
]
const documentExtensions = [
    'md', 'doc', 'docx'
]
const spreadSheetMimeTypes = [
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
]
const spreadSheetExtensions = ['xslx', 'xls', 'xlt', 'csv', 'tsv']
const calendarExtensions = ['ical', 'ics', 'ifb', 'icalendar']
const archiveExtensions = ['zip', '7zip', 'tar', 'gz', 'rar']
const bookExtensions = ['epub', 'mobi']

export function FileTypeSpecificIcon({ mimeType, name }: {mimeType: string, name: string}) {
    const parts = name.split('.')
    const extension = parts.length > 0 ? parts[parts.length - 1] : ''

    if (extension && archiveExtensions.indexOf(extension) !== -1) {
        return <ArchiveFile />
    }

    if (extension && bookExtensions.indexOf(extension) !== -1) {
        return <BookFile />
    }

    if (mimeType === 'text/calendar' ||
        (extension && calendarExtensions.indexOf(extension) !== -1)) {
        return <CalendarFile />
    }

    if (extension && documentExtensions.indexOf(extension) !== -1) {
        return <DocumentFile />
    }

    if (extension && codeFileExtensions.indexOf(extension) !== -1) {
        return <CodeFile />
    }

    if (spreadSheetMimeTypes.indexOf(mimeType) !== -1 ||
        (extension && spreadSheetExtensions.indexOf(extension) !== -1)) {
        return <SpreadsheetFile />
    }

    if (mimeType.startsWith('audio/')) {
        return <MusicFile />
    }

    if (mimeType.startsWith('video/')) {
        return <VideoFile />
    }

    if (mimeType.startsWith('text/')) {
        return <TextFile />
    }

    if (mimeType.startsWith('image/')) {
        return <ImageFile />
    }

    return <UnknownFile />
}
