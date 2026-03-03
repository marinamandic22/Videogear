export interface PaginatedResponse<T> {
  items: T[];
  _meta: {
    totalCount: number;
    pageCount: number;
    currentPage: number;
    perPage: number;
  };
  _links: {
    self: {
      href: string;
    };
    first?: {
      href: string;
    };
    last?: {
      href: string;
    };
    next?: {
      href: string;
    };
    prev?: {
      href: string;
    };
  };
}
