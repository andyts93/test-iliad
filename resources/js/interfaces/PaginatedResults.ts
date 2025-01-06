export default interface PaginatedResults<T = unknown> {
    from: number;
    to: number;
    current_page: number;
    total: number;
    per_page: number;
    data: T[];
    last_page: number;
}