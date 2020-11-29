<?php


namespace VanEyk\MITM\View\Components;


use Illuminate\Support\Str;
use Illuminate\View\Component;

class AttachmentIcon extends Component
{
    const CODE_FILE_EXTENSIONS = [
        'html', 'css', 'js', 'ts', 'vue', 'hbs', 'rb', 'java', 'cs', 'php', 'swift',
        'go', 'rs', 'c', 'cpp', 'h', 'kt', 'sql', 'py', 'sh', 'ps', 'perl', 'r',
        'json', 'xml',
    ];

    const DOCUMENT_FILE_EXTENSIONS = [
        'md', 'doc', 'docx'
    ];

    const SPREADSHEET_MIME_TYPES = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    const SPREADSHEET_FILE_EXTENSIONS = ['xslx', 'xls', 'xlt', 'csv', 'tsv'];

    const CALENDAR_FILE_EXTENSIONS = ['ical', 'ics', 'ifb', 'icalendar'];

    const ARCHIVE_FILE_EXTENSIONS = ['zip', '7zip', 'tar', 'gz', 'rar'];

    const BOOK_FILE_EXTENSIONS = ['epub', 'mobi'];

    public string $mimeType;

    public string $fileName;

    public function __construct(string $mimeType, string $fileName)
    {
        $this->mimeType = $mimeType;
        $this->fileName = $fileName;
    }

    public function iconName(): string
    {
        $parts = explode('.', $this->fileName);
        $extension = $parts[array_key_last($parts)];

        if (in_array($extension, self::ARCHIVE_FILE_EXTENSIONS)) {
            return 'file-earmark-zip';
        }

        if (in_array($extension, self::BOOK_FILE_EXTENSIONS)) {
            return 'book';
        }

        if (in_array($extension, self::CALENDAR_FILE_EXTENSIONS)) {
            return 'calendar';
        }

        if (in_array($extension, self::DOCUMENT_FILE_EXTENSIONS)) {
            return 'file-richtext';
        }

        if (in_array($extension, self::CODE_FILE_EXTENSIONS)) {
            return 'file-earmark-code';
        }

        if (in_array($this->mimeType, self::SPREADSHEET_MIME_TYPES)
            || in_array($extension, self::SPREADSHEET_FILE_EXTENSIONS)) {
            return 'file-earmark-spreadsheet';
        }

        if (Str::startsWith($this->mimeType, 'audio/')) {
            return 'music-note-beamed';
        }

        if (Str::startsWith($this->mimeType, 'video/')) {
            return 'film';
        }

        if (Str::startsWith($this->mimeType, 'text/')) {
            return 'file-earmark-text';
        }

        if (Str::startsWith($this->mimeType, 'image/')) {
            return 'image';
        }

        return 'file-earmark';
    }

    public function render()
    {
        return "<x-bi-{$this->iconName()} />";
    }
}
