export interface PaginationResult<T> {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
}
